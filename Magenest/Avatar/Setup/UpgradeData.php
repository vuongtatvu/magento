<?php

namespace Magenest\Avatar\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;

class UpgradeData implements UpgradeDataInterface
{

    /** @var CustomerSetupFactory $customerSetupFactory */
    protected $customerSetupFactory;

    /** @var SetFactory $attributeSetFactory */
    protected $attributeSetFactory;

    protected $eavSetup;

    function __construct(
        CustomerSetupFactory $customerSetupFactory,
        SetFactory $attributeSetFactory,
        EavSetup $eavSetup
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetup = $eavSetup;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            /** @var CustomerSetup $customerSetup */
            $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
            $setup->startSetup();
            $customerSetup->addAttribute('customer', 'image', [
                'label' => 'Logo Image',
                'type' => 'varchar',
                'frontend' => '',
                'input' => 'image',
                'visible' => true,
                'required' => false,
                'system' => false,
                'user_defined' => false,
                'sort_order' => 504,
                'position' => 1008,
            ]);

            $image = $customerSetup->getEavConfig()->getAttribute('customer', 'image');
            $image->setData('used_in_forms', ['adminhtml_customer']);
            $image->save();
            $setup->endSetup();
        }
    }
}
