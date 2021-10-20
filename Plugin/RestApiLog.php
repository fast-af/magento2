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

namespace Fast\Checkout\Plugin;

use Exception;
use Fast\Checkout\Api\RestApiLogRepositoryInterface as RestApiLogRepository;
use Fast\Checkout\Model\RestApiLogFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Webapi\Controller\Rest;
use Psr\Log\LoggerInterface;

/**
 * log rest api calls to table
 * Class RestApiLog
 */
class RestApiLog
{
    /**
     * @var RestApiLogRepository
     */
    protected $restApiLogRepository;

    /**
     * @var RestApiLogFactory
     */
    protected $restApiLogFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * RestApiLog constructor.
     * @param RestApiLogRepository $restApiLogRepository
     * @param RestApiLogFactory $restApiLogFactory
     */
    public function __construct(
        RestApiLogRepository $restApiLogRepository,
        RestApiLogFactory $restApiLogFactory,
        LoggerInterface $logger
    ) {
        $this->restApiLogRepository = $restApiLogRepository;
        $this->restApiLogFactory = $restApiLogFactory;
        $this->logger = $logger;
    }

    /**
     * @param Rest $subject
     * @param RequestInterface $request
     *
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    public function beforeDispatch(
        Rest $subject, //NOSONAR
        RequestInterface $request
    ) {

        $restApiLog = $this->restApiLogFactory->create();
        $restApiLog->setSource($request->getClientIp());
        $restApiLog->setMethod($request->getMethod());
        $restApiLog->setPath($request->getPathInfo());
        $restApiLog->setContent($request->getContent());
        try {
            //FIX - deprecated
            $restApiLog->getResource()->save($restApiLog);
        } catch (Exception $e) {
            $this->logger->error('in Plugin/RestApiLog could not save log record to table ' . $e->getMessage());
        }

        return null;
    }
}
