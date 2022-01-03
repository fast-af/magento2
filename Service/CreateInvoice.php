<?php

namespace Fast\Checkout\Service;

use Exception;
use Fast\Checkout\Helper\FastCheckout as FastCheckoutHelper;
use Magento\Framework\DB\Transaction;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Sales\Model\Service\InvoiceService;

class CreateInvoice
{
    /**
     * @var InvoiceService
     */
    private $invoiceService;
    /**
     * @var Transaction
     */
    private $transaction;
    /**
     * @var InvoiceSender
     */
    private $invoiceSender;
    /**
     * @var HistoryFactory
     */
    private $orderHistoryFactory;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var FastCheckoutHelper
     */
    private $fastCheckoutHelper;

    /**
     * CreateInvoice constructor.
     * @param InvoiceService $invoiceService
     * @param Transaction $transaction
     * @param InvoiceSender $invoiceSender
     * @param HistoryFactory $orderHistoryFactory
     * @param FastCheckoutHelper $fastCheckoutHelper
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        InvoiceService $invoiceService,
        Transaction $transaction,
        InvoiceSender $invoiceSender,
        HistoryFactory $orderHistoryFactory,
        FastCheckoutHelper $fastCheckoutHelper,
        OrderRepositoryInterface $orderRepository
    ) {

        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->invoiceSender = $invoiceSender;
        $this->orderHistoryFactory = $orderHistoryFactory;
        $this->orderRepository = $orderRepository;
        $this->fastCheckoutHelper = $fastCheckoutHelper;
    }


    /**
     * @param OrderInterface $order
     */
    public function doInvoice(OrderInterface $order)
    {
        try {
            $this->fastCheckoutHelper->log("generating invoice for order: " . $order->getIncrementId() . " fast order id " . $order->getData('fast_order_id'));

            if (!$order->canInvoice()) {
                $this->fastCheckoutHelper->log("order cannot be invoiced. exiting");
                return;
            }
            if ($order->getState() === 'new') {
                $this->fastCheckoutHelper->log("order in invalid state for invoicing. exiting");
                return;
            }

            $invoice = $this->invoiceService->prepareInvoice($order);
            $invoice->setRequestedCaptureCase(Invoice::CAPTURE_ONLINE);
            $invoice->register();
            $invoice->save();
            $transactionSave = $this->transaction->addObject(
                $invoice
            )->addObject(
                $invoice->getOrder()
            );
            $transactionSave->save();
            $this->invoiceSender->send($invoice);//Send Invoice mail to customer

            $history = $this->orderHistoryFactory->create()
                ->setStatus($order->getStatus())
                ->setEntityName(Order::ENTITY)
                ->setComment(__('Notified customer about invoice creation #%1.', $invoice->getId()))
                ->setIsCustomerNotified(true);

            $order->addStatusHistory($history);

        } catch (Exception $e) {
            $this->fastCheckoutHelper->log("invoice generation: FAILURE");
            $this->fastCheckoutHelper->log($e->getMessage());
        }
        $this->fastCheckoutHelper->log("invoice generation: SUCCESS");

    }
}