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

namespace Fast\Checkout\Model\Config\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Shipping\Model\Config;

class ShippingMethods implements OptionSourceInterface
{

    /**
     * @var Config
     */
    private $shippingMethodConfig;

    /**
     * @param Config $shippingMethodConfig
     */
    public function __construct(Config $shippingMethodConfig)
    {
        $this->shippingMethodConfig = $shippingMethodConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $activeCarriers = [];
        foreach ($this->shippingMethodConfig->getActiveCarriers() as $carrier) {
            $activeCarriers[] = [
                'label' => __(ucfirst($carrier->getCarrierCode())),
                'value' => $carrier->getCarrierCode()
            ];
        }
        return $activeCarriers;
    }
}
