<?php

namespace Magenest\Manufacturer\Setup;

use    Magento\Framework\Setup\UpgradeDataInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\ModuleDataSetupInterface;
use    Magento\Eav\Setup\EavSetupFactory;

class    UpgradeData implements UpgradeDataInterface

{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'manufacturer_id',
                [
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Manufacturer',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'Magenest\Manufacturer\Model\Config\Source\Manufacturer',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => ''
                ]
            );
        }
    }
}