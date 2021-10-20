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

namespace Fast\Checkout\Api;

use Fast\Checkout\Api\Data\RestApiLogInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface RestApiRepositoryLog
 */
interface RestApiLogRepositoryInterface
{
    /**
     * @param RestApiLogInterface $restApiLog
     * @return RestApiLogInterface
     */
    public function save(RestApiLogInterface $restApiLog): RestApiLogInterface;

    /**
     * @param int $apiLogId
     * @return mixed
     */
    public function getById(int $apiLogId): RestApiLogInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * @param RestApiLogInterface $restApiLog
     * @return mixed
     */
    public function delete(
        RestApiLogInterface $restApiLog
    );

    /**
     * @param int $apiLogId
     * @return mixed
     */
    public function deleteById(int $apiLogId): bool;
}
