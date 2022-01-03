<?php
/**
 * Fast_Checkout
 *
 * PHP version 7.3
 *
 * @package   Fast_Checkout
 * @author    Fast <hi@fast.co>
 * @copyright 2021 Copyright Fast AF, Inc., https://www.fast.co/
 * @license   https://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link      https://www.fast.co/
 */

declare(strict_types=1);

namespace Fast\Checkout\Observer;

use Fast\Checkout\Model\Config\FastIntegrationConfig as FastConfig;
use Fast\Checkout\Service\CreateInvoice;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

/**
 * Class CreateInvoiceOnShipment
 * create an invoice when an order is shipped
 */
class CreateInvoiceOnShipment implements ObserverInterface
{
    /**
     * @var FastConfig
     */
    protected $fastConfig;
    /**
     * @var CreateInvoice
     */
    private $createInvoice;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * CreateInvoiceOnShipment constructor.
     * @param FastConfig $fastConfig
     * @param CreateInvoice $createInvoice
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        FastConfig $fastConfig,
        CreateInvoice $createInvoice,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->fastConfig = $fastConfig;
        $this->createInvoice = $createInvoice;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getShipment()->getOrder();
        if ($this->fastConfig->isEnabled()
            && $this->fastConfig->isEnabledAutoInvoice()
            && $order->getData('fast_order_id')
            && $order->getPayment()->getMethod() === 'fast') {
            $this->createInvoice->doInvoice($order);
            $this->orderRepository->save($order);
        }
    }
}
