<?php

namespace Magenest\StockStatus\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_stock_status',
            [
                'group' => 'Product Details',
                'type' => 'int',
                'input' => 'select',
                'backend' => '',
                'frontend' => '',
                'label' => 'Custom Stock Status',
                'class' => '',
                'source' => '',
                'global' =>  \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_stock_status_qty_rule',
            [
                'group' => 'Product Details',
                'type' => 'int',
                'input' => 'select',
                'backend' => '',
                'frontend' => '',
                'label' => 'Custom Stock Status Quantity Rule',
                'class' => '',
                'source' => '',
                'global' =>  \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'notice_number_left',
            [
                'group' => 'Product Details',
                'type' => 'int',
                'input' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Notice when the number is left',
                'class' => '',
                'source' => '',
                'global' =>  \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]
        );
        
        $attStockStatusId = $eavSetup->getAttributeId(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_stock_status'
        );
        $attStockStatusQtyRuleId = $eavSetup->getAttributeId(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_stock_status_qty_rule'
        );
        foreach ($eavSetup->getAllAttributeSetIds(\Magento\Catalog\Model\Product::ENTITY) as $attSetupId){
            try{
                $attGroupId = $eavSetup->getAttributeGroupId(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attSetupId,
                    'General'
                );
            }catch (\Exception $e){
                $attGroupId = $eavSetup->getAttributeGroupId(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $attSetupId
                );
            }
            $eavSetup->addAttributeToSet(
                \Magento\Catalog\Model\Product::ENTITY,
                $attSetupId,
                $attGroupId,
                $attStockStatusId
            );
        }
//        $tableName = $setup->getTable('magenest_stockstatus_managerqtyrule');
//        $setup->run("
//            CREATE TABLE IF NOT EXISTS `{$tableName}`  (
//                `entity_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
//                `qty_from` INT NOT NULL ,
//                `qty_to` INT NOT NULL ,
//                `rule` INT NULL,
//                `status_id` INT UNSIGNED NOT NULL
//            ) ENGINE = InnoDB ;
//        ");
    }
}