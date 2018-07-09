<?php

namespace Magenest\Sales\Setup;

use    Magento\Framework\Setup\UpgradeDataInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\ModuleDataSetupInterface;
use    Magento\Eav\Setup\EavSetupFactory;
use    Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use    Magento\Customer\Model\Customer;
use    Magento\Customer\Setup\CustomerSetupFactory;


class    UpgradeData implements UpgradeDataInterface

{
    private $eavSetupFactory;

    protected $customerSetupFactory;

    private $attributeSetFactory;


    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        EavSetupFactory $eavSetupFactory
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        //EAV product
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'sale_agent_id',
                [
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Sale Agent Id',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'Magenest\Sales\Model\Config\Source\SalesAgent',
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
            )->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'commission_type',
                [
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Commission Type',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'Magenest\Sales\Model\Config\Source\CommissionType',
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
            )->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'commission_value',
                [
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Commission Value',
                    'input' => 'input',
                    'class' => '',
                    'source' => '',
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
                    'apply_to' => '',
                    'fixed'
                ]
            );

            $setup->startSetup();

            /** @var CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);


            $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
            $attributeSetId = $customerEntity->getDefaultAttributeSetId();

            /** @var $attributeSet AttributeSet */
            $attributeSet = $this->attributeSetFactory->create();
            $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);


            $customerSetup->addAttribute(Customer::ENTITY, 'is_sales_agent', [
                'type' => 'varchar',
                'label' => 'Is Sales Agent',
                'input' => 'boolean',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'sort_order' => 1000,
                'position' => 1000,
                'default' => 0,
                'system' => 0,
                'default' => 0,
            ]);

            $specialOffers = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'is_sales_agent')
                ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer', 'checkout_register', 'customer_account_create', 'customer_account_edit'],
                ]);

            $specialOffers->save();

            $setup->endSetup();

        }
    }

}