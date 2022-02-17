<?php
/**
 * Fast_Checkout
 *
 * PHP version 7.3
 *
 * @package   Fast_Checkout
 * @author    Fast <hi@fast.co>
 * @copyright 2021 Copyright Fast AF, Inc., https://www.fast.co/
 * @license   https://opensource.org/licenses/OSL-3.0 OSL-3.0
 * @link      https://www.fast.co/
 */

declare(strict_types=1);

namespace Fast\Checkout\Api;

/**
 * @api
 */
interface GetProductRepositoryInterface{

    /**
     * Get info about product by product Id
     *
     * @param int $productId
     * @param int|null $storeId
     * @param bool $forceReload
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($productId, $storeId = null, $forceReload = false);


    /**
     * Create product
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function getByPostFields(\Magento\Catalog\Api\Data\ProductInterface $product);

}