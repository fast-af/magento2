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

namespace Fast\Checkout\Setup\Patch\Data;

use Fast\Checkout\Model\Payment\FastPayment;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;

/**
 * Class AddFastFraudOrderStatusPatch
 *
 * Create Custom Order Status/State for Fast Fraud Detection
 */
class AddFastFraudOrderStatusPatch implements DataPatchInterface
{
    /**
     * @var array
     */
    const DEPENDENCIES = [];

    /**
     * @var array
     */
    const ALIASES = [];

    /**
     * WriterInterface
     *
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * StatusFactory
     *
     * @var StatusFactory
     */
    private $statusFactory;

    /**
     * StatusResourceFactory
     *
     * @var StatusResourceFactory
     */
    private $statusResourceFactory;

    /**
     * CreateOrderStateStatusPatch constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param WriterInterface $configWriter
     * @param StatusFactory $statusFactory
     * @param StatusResourceFactory $statusResourceFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WriterInterface $configWriter,
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->configWriter = $configWriter;
        $this->statusFactory = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * @return array
     */
    public static function getDependencies()
    {
        return static::DEPENDENCIES;
    }

    /**
     * PATCH for Create Custom Order Status
     *
     * @return DataPatchInterface|void
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $this->addFraudPendingStatus();
        $this->addFraudPassedStatus();
        $this->addFraudFailedStatus();
        $this->moduleDataSetup->endSetup();
    }

    /**
     * Create Fraud Check Pending Status
     */
    private function addFraudPendingStatus()
    {
        $statusResource = $this->statusResourceFactory->create();
        $status = $this->statusFactory->create();
        $status->setData(
            [
                'status' => FastPayment::FAST_FRAUD_PENDING_STATUS,
                'label' => FastPayment::FAST_FRAUD_PENDING_LABEL,
            ]
        );
        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        // Assign State
        $status->assignState(Order::STATE_PAYMENT_REVIEW, false, true);
    }

    /**
     * Create Fraud Check Passed Status
     */
    private function addFraudPassedStatus()
    {
        $statusResource = $this->statusResourceFactory->create();
        $status = $this->statusFactory->create();
        $status->setData(
            [
                'status' => FastPayment::FAST_FRAUD_SUCCESS_STATUS,
                'label' => FastPayment::FAST_FRAUD_SUCCESS_LABEL,
            ]
        );
        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        // Assign State
        $status->assignState(Order::STATE_PENDING_PAYMENT, false, true);
    }

    /**
     * Create Fraud Check Failed Status
     */
    private function addFraudFailedStatus()
    {
        $statusResource = $this->statusResourceFactory->create();
        $status = $this->statusFactory->create();
        $status->setData(
            [
                'status' => FastPayment::FAST_FRAUD_FAILED_STATUS,
                'label' => FastPayment::FAST_FRAUD_FAILED_LABEL,
            ]
        );
        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception) {
            return;
        }
        // Assign State
        $status->assignState(Order::STATE_HOLDED, false, true);
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return array
     */
    public function getAliases()
    {
        return static::ALIASES;
    }
}
