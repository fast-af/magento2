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

namespace Fast\Checkout\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use Fast\Checkout\Api\Data\DoRequestLogInterface;

/**
 * Interface DoRequestLogSearchResultsInterface
 */
interface DoRequestLogSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get DoRequestLog list.
     * @return \Fast\Checkout\Api\Data\DoRequestLogInterface[]
     */
    public function getItems();

    /**
     * Set request_id list.
     * @param DoRequestLogInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
