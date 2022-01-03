<?php

namespace Fast\Checkout\Plugin\Api;

use Fast\Checkout\Model\Config\FastIntegrationConfig;
use Fast\Checkout\Model\Payment\FastPayment;
use Fast\Checkout\Service\CreateInvoice;
use Magento\Payment\Model\MethodInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

/**
 * Class OrderRepository
 */
class OrderRepository
{
    const FAST_ORDER_ID = 'fast_order_id';

    /**
     * Order Extension Attributes Factory
     *
     * @var OrderExtensionFactory
     */
    protected $extensionFactory;
    protected $fastIntegrationConfig;
    /**
     * @var CreateInvoice
     */
    private $createInvoice;

    /**
     * OrderRepositoryPlugin constructor
     *
     * @param OrderExtensionFactory $extensionFactory
     * @param FastIntegrationConfig $fastIntegrationConfig
     * @param CreateInvoice $createInvoice
     */
    public function __construct(
        OrderExtensionFactory $extensionFactory,
        FastIntegrationConfig $fastIntegrationConfig,
        CreateInvoice $createInvoice
    ) {
        $this->extensionFactory = $extensionFactory;
        $this->fastIntegrationConfig = $fastIntegrationConfig;
        $this->createInvoice = $createInvoice;
    }

    /**
     * Add "fast_order_id" extension attribute to order data object to make it accessible in API data
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     *
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        if ($this->fastIntegrationConfig->isEnabled()) {
            $fastOrderId = $order->getData(static::FAST_ORDER_ID);
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $extensionAttributes->setFastOrderId($fastOrderId);
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $order;
    }

    /**
     * Add "fast_order_id" extension attribute to order data object to make it accessible in API data
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     *
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        if ($this->fastIntegrationConfig->isEnabled()) {
            $orders = $searchResult->getItems();

            foreach ($orders as &$order) {
                $fastOrderId = $order->getData(static::FAST_ORDER_ID);
                $extensionAttributes = $order->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
                $extensionAttributes->setFastOrderId($fastOrderId);
                $order->setExtensionAttributes($extensionAttributes);
            }
        }

        return $searchResult;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $resultOrder
     *
     * @return $resultOrder
     */
    public function beforeSave(OrderRepositoryInterface $subject, OrderInterface $resultOrder)
    {
        if ($this->fastIntegrationConfig->isEnabled()) {
            foreach (['fast_order_id'] as $field) {
                $value = $resultOrder->getData($field);
                $resultOrder->setData($field, $value);
            }
            if ($resultOrder->getStatus() === FastPayment::FAST_FRAUD_SUCCESS_STATUS) {
                $orderStatus = $this->fastIntegrationConfig->getNewAfterFraudStatus();
                $resultOrder->setState(Order::STATE_PROCESSING)->setStatus($orderStatus);
                if (!$this->fastIntegrationConfig->isAuthCapture()) {
                    $this->createInvoice->doInvoice($resultOrder);
                }
            }
        }
        //return $resultOrder;
    }
}
