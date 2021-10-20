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

namespace Fast\Checkout\Cron\RestApiLog;

use Fast\Checkout\Service\RestApiLog\CleanTableService;
use Psr\Log\LoggerInterface;

/**
 * Class CleanTableCron
 *
 * delete old rows from api log table
 */
class CleanTableCron
{
    /**
     * @var CleanTableService
     */
    private $cleanTableService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CleanTableCron constructor.
     *
     * @param CleanTableService $cleanTableService
     * @param LoggerInterface $logger
     */
    public function __construct(
        CleanTableService $cleanTableService,
        LoggerInterface $logger
    ) {
        $this->cleanTableService = $cleanTableService;
        $this->logger = $logger;
    }

    /**
     * Process clean api log table
     *
     * @return bool
     */
    public function execute(): bool
    {
        if ($this->cleanTableService->isEnabled()) {
            $this->logger->info('Clean Table Cron is enabled');
            $this->cleanTableService->execute();

            return true;
        }

        $this->logger->info('Clean Table Cron is disabled');

        return true;
    }
}
