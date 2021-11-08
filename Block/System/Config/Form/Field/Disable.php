<?php

namespace Fast\Checkout\Block\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Disable
 */
class Disable extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    //phpcs:disable
    protected function _getElementHtml(AbstractElement $element)
    {
        //phpcs:enable
        $element->setDisabled('disabled');
        return $element->getElementHtml();

    }
}
