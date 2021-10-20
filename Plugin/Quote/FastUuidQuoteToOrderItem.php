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

declare(strict_types=1);

namespace Fast\Checkout\Plugin\Quote;

use Closure;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Model\Order\Item;

/**
 * Class SetFastOrderData
 */
class FastUuidQuoteToOrderItem
{
    /**
     * @param ToOrderItem $subject
     * @param Closure $proceed
     * @param AbstractItem $item
     * @param array $additional
     * @return Item
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function aroundConvert(
        ToOrderItem $subject,
        Closure $proceed,
        AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem Item */
        $orderItem = $proceed($item, $additional);
        $orderItem->setFastOrderItemUuid($item->getFastOrderItemUuid());
        return $orderItem;
    }
}
