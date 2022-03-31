<?php

namespace Fast\Checkout\Plugin\Resolver;

use Closure;
use \Magento\Framework\App\Http\Context;
use Magento\QuoteGraphQl\Model\Resolver\CreateEmptyCart\Interceptor;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class CreateEmptyCartWithFastOrderId
 */
class CreateEmptyCartWithFastOrderId
{
    const CONTEXT_FAST_ORDER_ID_KEY = 'fast_order_id';

    protected $httpContext;

    public function __construct(
        Context $httpContext
    ) {
        $this->httpContext = $httpContext;
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
        if (isset($args['input']['fast_order_id'])) {
            // pass along the fast_order_id, so Plugin/Api/CartManagement can pick it up
            $this->httpContext->setValue(self::CONTEXT_FAST_ORDER_ID_KEY, $args['input']['fast_order_id'], null);
        }

        return $proceed($field, $context, $info, $value, $args);
    }
}
