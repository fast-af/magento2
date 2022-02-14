<?php

declare(strict_types=1);

namespace Fast\Checkout\Setup\Patch\Data;

use Fast\Checkout\Logger\Logger;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Data patch to add show/hide fast checkout button attribute
 */
class UpdateSalesOrderGridPatch implements DataPatchInterface
{
    const DEPENDENCIES = [];
    const ALIASES = [];
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Logger $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Logger $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger = $logger;
    }

    /**
     * Get Dependencies
     *
     * @return array
     */
    public static function getDependencies()
    {
        return static::DEPENDENCIES;
    }

    /**
     * Apply patch to update fast_order_id in grid
     */
    public function apply()
    {
        $connection = $this->moduleDataSetup->getConnection();
        $grid = $this->moduleDataSetup->getTable('sales_order_grid');
        $affiliate = $this->moduleDataSetup->getTable('sales_order');

        $connection->query(
            $connection->updateFromSelect(
                $connection->select()
                    ->join(
                        $affiliate,
                        sprintf('%s.entity_id = %s.entity_id', $grid, $affiliate),
                        'fast_order_id'
                    ),
                $grid
            )
        );
    }

    /**
     * Get Aliases
     *
     * @return array
     */
    public function getAliases()
    {
        return static::ALIASES;
    }
}
