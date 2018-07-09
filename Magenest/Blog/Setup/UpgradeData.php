<?php

namespace Magenest\Blog\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magenest\Blog\Setup\InstallData\PostSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class UpgradeData implements UpgradeDataInterface
{

    protected $eavSetupFactory;
    protected $postSetupFactory;

    function __construct(
        EavSetupFactory $eavSetupFactory,
        PostSetupFactory $postSetupFactory
    )
    {
        $this->postSetupFactory = $postSetupFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            /** @var  \Magenest\Blog\Setup\InstallData\PostSetup $postSetup */
            $postSetup = $this->postSetupFactory->create(['setup' => $setup]);
            $setup->startSetup();
            $this->addStartCountdownAttribute($postSetup);
            $this->addEndCountdownAttribute($postSetup);
            $this->addCountVisitsAttribute($postSetup);
            $setup->endSetup();
        }
    }

    /**
     * @param $postSetup \Magenest\Blog\Setup\InstallData\PostSetup
     */
    private function addStartCountdownAttribute($postSetup)
    {
        $postSetup->addAttribute(\Magenest\Blog\Model\Post::ENTITY, 'start_countdown', [
            'label' => 'Start Countdown',
            'type' => 'datetime',
            'input' => 'date',
            'source' => 'Magento\Catalog\Model\Attribute\Backend\Startdate',
        ]);
    }

    private function addEndCountdownAttribute($postSetup)
    {
        $postSetup->addAttribute(\Magenest\Blog\Model\Post::ENTITY, 'end_countdown', [
            'label' => 'End Countdown',
            'type' => 'datetime',
            'input' => 'date',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
        ]);
    }

    private function addCountVisitsAttribute($postSetup)
    {
        $postSetup->addAttribute(\Magenest\Blog\Model\Post::ENTITY, 'count_visits', [
            'label' => 'Count Visits',
            'type' => 'int',
            'input' => 'text',
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
        ]);
    }
}