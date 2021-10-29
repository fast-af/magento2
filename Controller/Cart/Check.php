<?php
/**
 * Fast_Checkout
 *
 * PHP version 7.3
 *
 * @author    Fast <hi@fast.co>
 * @copyright 2021 Copyright Fast AF, Inc., https://www.fast.co/
 * @license   https://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link      https://www.fast.co/
 */

declare(strict_types=1);

namespace Fast\Checkout\Controller\Cart;

use Fast\Checkout\Model\Config\FastIntegrationConfig as FastConfig;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;

/**
 * Class Check
 *
 * removes items from carts in Magento
 */
class Check extends Action
{
    /**
     * @var ResultJsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var CustomerSession
     */
    protected $customerSession;
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var FastConfig
     */
    protected $fastConfig;

    /**
     * Check constructor.
     * @param Context $context
     * @param ResultJsonFactory $resultJsonFactory
     * @param CheckoutSession $checkoutSession
     * @param CustomerSession $customerSession
     * @param FastConfig $fastConfig
     */
    public function __construct(
        Context $context,
        ResultJsonFactory $resultJsonFactory,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        FastConfig $fastConfig
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->fastConfig = $fastConfig;
        parent::__construct($context);
    }

    /**
     * clear the current user's shopping cart
     */
    public function execute()
    {
        $cartIsFast = true;
        foreach ($this->checkoutSession->getQuote()->getAllVisibleItems() as $cartItem) {
            if ((int)$cartItem->getProduct()->getData('hide_fast_option') == 0) {
                $cartIsFast = false;
            }
            if ($cartItem->getProductType() === 'bundle' ||
                $cartItem->getProductType() === 'downloadable') {
                $cartIsFast = false;
            }
        }
        $result = $this->resultJsonFactory->create();
        return $result->setData([
            'areAllProductsFast' => $cartIsFast,
            'theme' => $this->fastConfig->useDarkTheme() ? 'dark' : ''
        ]);
    }
}
