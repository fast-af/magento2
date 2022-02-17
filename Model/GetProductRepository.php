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

namespace Fast\Checkout\Model;

use Fast\Checkout\Api\GetProductRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Fast\Checkout\Logger\Logger;
use Magento\Framework\Exception\NoSuchEntityException;

class GetProductRepository implements GetProductRepositoryInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var Logger
     */
    private $logger;


    /**
     * @param ProductRepositoryInterface $productRepository
     * @param Logger $logger
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Logger                     $logger
    )
    {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
    }

    /**
     * @param $productId
     * @param $storeId
     * @param $forceReload
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($productId, $storeId = null, $forceReload = false)
    {
        $this->logger->addInfo("CustomAPI ProductRequest: ID => {$productId}");
        return $this->productRepository->getById($productId, false, $storeId, $forceReload);
    }


    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws NoSuchEntityException
     */
    public function getByPostFields(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $sku = trim($product->getSku());
        $this->logger->addInfo("CustomAPI ProductRequest: SKU => {$sku}");
        if(!$sku){
            throw new NoSuchEntityException(
                __("The product SKU is empty or Invalid. Verify the SKU and try again.")
            );
        }
        return $this->productRepository->get($sku);
    }

}