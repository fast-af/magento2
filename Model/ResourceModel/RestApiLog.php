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

namespace Fast\Checkout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * resource model
 * Class RestApiLog
 */
class RestApiLog extends AbstractDb
{
    /**
     * RestApiLog Abstract Resource Constructor
     * @return void
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init('fast_checkout_rest_api_log', 'api_log_id');
    }
}
