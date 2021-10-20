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

namespace Fast\Checkout\Model\ResourceModel\RestApiLog;

use Fast\Checkout\Model\ResourceModel\RestApiLog;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * collection class for RestApiLog
 * Class Collection
 */
class Collection extends AbstractCollection
{
    /**
     * RestApiLog Collection Constructor
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(
            \Fast\Checkout\Model\RestApiLog::class,
            RestApiLog::class
        );
    }
}
