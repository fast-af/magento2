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

namespace Fast\Checkout\Model;

use Fast\Checkout\Api\Data\DoRequestLogInterface;
use Fast\Checkout\Api\Data\DoRequestLogInterfaceFactory;
use Fast\Checkout\Model\ResourceModel\DoRequestLog\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * Class DoRequestLog
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class DoRequestLog extends AbstractModel
{

    /**
     * @var string
     */
    protected $_eventPrefix = 'fast_checkout_dorequestlog';

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DoRequestLogInterfaceFactory
     */
    protected $dorequestlogDataFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param DoRequestLogInterfaceFactory $dorequestlogDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\DoRequestLog $resource
     * @param Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DoRequestLogInterfaceFactory $dorequestlogDataFactory,
        DataObjectHelper $dataObjectHelper,
        ResourceModel\DoRequestLog $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        $this->dorequestlogDataFactory = $dorequestlogDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve dorequestlog model with dorequestlog data
     * @return \Fast\Checkout\Api\Data\DoRequestLogInterface
     */
    public function getDataModel(): DoRequestLogInterface
    {
        $dorequestlogData = $this->getData();

        $dorequestlogDataObject = $this->dorequestlogDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $dorequestlogDataObject,
            $dorequestlogData,
            DoRequestLogInterface::class
        );

        return $dorequestlogDataObject;
    }
}
