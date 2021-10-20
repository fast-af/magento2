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

namespace Fast\Checkout\Cron\Fast;

use Fast\Checkout\Api\DoRequestLogRepositoryInterface;
use Fast\Checkout\Model\Config\FastIntegrationConfig as FastConfig;
use Fast\Checkout\Service\DoRequest;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ResendFailedRequest
{
    /**
     * @var DoRequestLogRepositoryInterface
     */
    private $doRequestLogRepository;
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;
    /**
     * @var DoRequest
     */
    private $doRequest;
    private $fastConfig;

    public function __construct(
        DoRequestLogRepositoryInterface $doRequestLogRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        DoRequest $doRequest,
        FastConfig $fastConfig
    ) {
        $this->doRequestLogRepository = $doRequestLogRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->doRequest = $doRequest;
        $this->fastConfig = $fastConfig;
    }

    /**
     * Execute the cron
     *
     * @return void
     * @throws LocalizedException
     */
    public function execute()
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder->addFilter('retry_required', 1)
            ->addFilter('attempts', $this->fastConfig->getRetryCount(), 'lt')->create();
        $items = $this->doRequestLogRepository->getList($searchCriteria)->getItems();
        foreach ($items as $item) {
            $body = (array)json_decode($item->getBody());
            $this->doRequest->execute(
                $item->getUriEndpoint(),
                json_decode(json_encode($body['body']), true),
                $item->getRequestMethod(),
                (int)$item->getDorequestlogId()
            );
        }
    }
}
