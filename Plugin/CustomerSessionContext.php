<?php
/**
 * Fast_Checkout
 *
 * PHP version 7.3
 *
 * @package   Fast_Checkout
 * @author    Borngroup <support@borngroup.com>
 * @copyright 2020 Copyright BORN Commerce Pvt Ltd, https://www.borngroup.com/
 * @license   https://www.borngroup.com/ Borngroup
 * @link      https://www.fast.co/
 */

namespace Fast\Checkout\Plugin;

use Closure;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;

/**
 * Class CustomerSessionContext
 */
class CustomerSessionContext
{
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;
    /**
     * @var Context
     */
    protected $httpContext;
    /**
     * @var QuoteFactory
     */
    protected $quoteFactory;
    /**
     * @var Quote
     */
    protected $quote;

    /**
     * CustomerSessionContext constructor.
     * @param Session $customerSession
     * @param CheckoutSession $checkoutSession
     * @param Quote $quote
     * @param QuoteFactory $quoteFactory
     * @param Context $httpContext
     */
    public function __construct(
        Session $customerSession,
        CheckoutSession $checkoutSession,
        Quote $quote,
        QuoteFactory $quoteFactory,
        Context $httpContext
    ) {
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->httpContext = $httpContext;
        $this->quote = $quote;
        $this->quoteFactory = $quoteFactory;
    }

    /**
     * @param ActionInterface $subject
     * @param callable|Closure $proceed
     * @param RequestInterface $request
     * @return mixed
     * @throws LocalizedException
     * @throws NoSuchEntityException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function aroundDispatch(
        ActionInterface $subject, //NOSONAR
        Closure $proceed,
        RequestInterface $request
    ) {
        $this->httpContext->setValue(
            'customer_id',
            $this->customerSession->getCustomerId(),
            false
        );
        $this->httpContext->setValue(
            'customer_name',
            $this->customerSession->getCustomer()->getName(),
            false
        );
        $this->httpContext->setValue(
            'customer_email',
            $this->customerSession->getCustomer()->getEmail(),
            false
        );
        //$customerId = $this->customerSession->getCustomer()->getId();
        //if ($customerId > 0) {
        $currentQuote = $this->checkoutSession->getQuote();
        $this->httpContext->setValue(
            'customer_cart_id',
            $currentQuote->getId(),
            false
        );
        $visibleCartItems = $currentQuote->getAllVisibleItems();
        $this->httpContext->setValue(
            'customer_cart_items',
            $visibleCartItems,
            false
        );
        $this->httpContext->setValue(
            'customer_cart_coupon',
            $currentQuote->getCouponCode(),
            false
        );
        return $proceed($request);
    }
}
