<?php

namespace Fast\Checkout\Observer;

use Exception;
use Fast\Checkout\Helper\FastCheckout as FastHelper;
use Fast\Checkout\Logger\Logger;
use Fast\Checkout\Model\SalesOrderGrid\OrderGridUpdater;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

/**
 * Class UpdateGuestOrderWithCustomerData
 */
class UpdateSalesOrderGrid implements ObserverInterface
{
    /**
     * @var OrderGridUpdater
     */
    protected $orderGridUpdater;
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var FastHelper
     */
    protected $fastHelper;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Order
     */
    protected $order;


    /**
     * OrderGridUpdater constructor.
     * @param OrderGridUpdater $orderGridUpdater
     * @param OrderRepositoryInterface $orderRepository
     * @param FastHelper $fastHelper
     * @param Order $order
     * @param Logger $logger
     */
    public function __construct(
        OrderGridUpdater $orderGridUpdater,
        OrderRepositoryInterface $orderRepository,
        FastHelper $fastHelper,
        Order $order,
        Logger $logger
    ) {
        $this->orderGridUpdater = $orderGridUpdater;
        $this->orderRepository = $orderRepository;
        $this->fastHelper = $fastHelper;
        $this->logger = $logger;
        $this->order = $order;
    }

    /**
     * @param EventObserver $observer
     * @throws Exception
     */
    public function execute(EventObserver $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getEntityId();
        $fastOrderId = $order->getData('fast_order_id');
        if ($fastOrderId) {
            $this->orderGridUpdater->update($orderId);
        }
    }
}
