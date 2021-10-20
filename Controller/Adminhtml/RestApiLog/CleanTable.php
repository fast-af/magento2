<?php
/**
 * Fast_Checkout
 *
 * PHP version 7.3
 *
 * @author    Borngroup <support@borngroup.com>
 * @copyright 2020 Copyright BORN Commerce Pvt Ltd, https://www.borngroup.com/
 * @license   https://www.borngroup.com/ Borngroup
 * @link      https://www.fast.co/
 */

declare(strict_types=1);

namespace Fast\Checkout\Controller\Adminhtml\RestApiLog;

use Fast\Checkout\Service\RestApiLog\CleanTableService;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class CleanTable
 *
 * Run cleantable process
 */
class CleanTable extends Action implements HttpGetActionInterface
{
    /**
     * @var CleanTableService
     */
    private $cleanTableService;
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * CleanTable constructor
     *
     * @param Context $context
     * @param CleanTableService $cleanTableService
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        CleanTableService $cleanTableService,
        JsonFactory $resultJsonFactory
    ) {
        $this->cleanTableService = $cleanTableService;
        $this->resultJsonFactory = $resultJsonFactory;

        parent::__construct($context);
    }

    /**
     * Run CleanTable process
     *
     * @return ResultJson
     */
    public function execute(): ResultJson
    {
        $resultJson = $this->resultJsonFactory->create();
        $data = ['message' => $this->cleanTableService->execute()];
        $resultJson->setData($data);

        return $resultJson;
    }
}
