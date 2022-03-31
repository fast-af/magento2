<?php

namespace Fast\Checkout\Plugin\Resolver;

use Closure;
use Magento\QuoteGraphQl\Model\Resolver\AddProductsToCart\Interceptor;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\App\ResourceConnection;

/**
 * Class AddProductsToCartWithFastItemUuid
 */
class AddProductsToCartWithFastItemUuid
{
    protected $resourceConnection;
    private $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        ResourceConnection $resourceConnection
    ) {
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
    }

    public function aroundResolve(
        Interceptor $subject,
        Closure $proceed,
        Field $field, 
        $context, 
        ResolveInfo $info, 
        array $value = null,
        array $args = null
    ) {
        $result = $proceed($field, $context, $info, $value, $args);

        $cart = $result['cart']['model'];
        $cartItems = $args['cartItems'];
        $connection = $this->resourceConnection->getConnection();
        $skuToFastId = [];
        foreach ($cartItems as $cartItem) {
            if (isset($cartItem['fast_order_item_uuid'])) {
                $skuToFastId[$cartItem['sku']] = $cartItem['fast_order_item_uuid'];
            }
        }

        $this->logger->info('add product run');

        foreach ($cart->getItems() as $cItem) {
            $fastOrderItemUuid = $skuToFastId[$cItem->getSku()];

            // update DB directly to save fast_order_item_uuid, similar to /Plugin/Uuid/AddFastOrderItemUuid
            $query = "UPDATE `quote_item` SET `fast_order_item_uuid`= '" . $fastOrderItemUuid . "' WHERE item_id = " . $cItem->getItemId();
            $connection->query($query);

            $extensionAttributes = $cItem->getExtensionAttributes();
            $cItem->setData('fast_order_item_uuid', $fastOrderItemUuid);
            $extensionAttributes->setFastOrderItemUuid($fastOrderItemUuid);
            $cItem->setExtensionAttributes($extensionAttributes);
        }

        return $result;
    }
}
