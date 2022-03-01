<?php
/**
 * Fast_Checkout
 *
 * PHP version 7.3
 *
 * @package   Fast_Checkout
 * @author    Fast <hi@fast.co>
 * @copyright 2022 Copyright Fast AF, Inc., https://www.fast.co/
 * @license   https://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link      https://www.fast.co/
 */

declare(strict_types=1);

namespace Fast\Checkout\Model\GuestCart;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\GuestShipmentEstimationInterface as QuoteGuestShipmentEstimationInterface;
use Fast\Checkout\Api\GuestShipmentEstimationInterface;
use Fast\Checkout\Model\Config\FastIntegrationConfig;
use Fast\Checkout\Logger\Logger;

class GuestShippingMethodManagement implements GuestShipmentEstimationInterface
{

    /**
     * @var QuoteGuestShipmentEstimationInterface
     */
    private $guestShipmentEstimation;

    /**
     * @var FastIntegrationConfig
     */
    private $fastIntegrationConfig;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param QuoteGuestShipmentEstimationInterface $guestShipmentEstimation
     * @param FastIntegrationConfig $fastIntegrationConfig
     * @param Logger $logger
     */
    public function __construct(
        QuoteGuestShipmentEstimationInterface $guestShipmentEstimation,
        FastIntegrationConfig                 $fastIntegrationConfig,
        Logger                                $logger
    )
    {
        $this->guestShipmentEstimation = $guestShipmentEstimation;
        $this->fastIntegrationConfig = $fastIntegrationConfig;
        $this->logger = $logger;
    }

    /**
     * @param $cartId
     * @param AddressInterface $address
     * @return array|\Magento\Quote\Api\Data\ShippingMethodInterface[]
     */
    public function estimateByExtendedAddress($cartId, AddressInterface $address): array
    {
        $fastShippingMethods = [];
        $shippingMethods = $this->guestShipmentEstimation->estimateByExtendedAddress($cartId, $address);
        $configImplodeString = $this->fastIntegrationConfig->getShippingRestrictions();

        if (!$configImplodeString) {
            return $shippingMethods;
        }

        $restrictions = array_filter(explode(',', $configImplodeString));
        foreach ($shippingMethods as $method) {
            if (!in_array($method->getCarrierCode(), $restrictions)) {
                $fastShippingMethods[] = $method;
            }
        }

        if (sizeof($fastShippingMethods) > 0) {
            return $fastShippingMethods;
        }

        return $shippingMethods;
    }

}
