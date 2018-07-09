<?php

namespace Magenest\Vu\Setup;


use    Magento\Framework\Setup\UpgradeSchemaInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $setup->startSetup();


            $table =$setup->getConnection()->newTable($setup->getTable('magenest_test_vendor_vu')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11  ,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Id'
            )->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 11,
                [
                    'required' => true
                ],
                'Customer Id'
            )->addColumn(
                'first_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'First Name'
            )->addColumn(
                'last_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Last Name'
            )->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [
                    'required' => true
                ],
                'Email'
            )->addColumn(
                'company',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null,
                [],
                'Company'
            )->addColumn(
                'phone_number',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 15,
                [],
                'Phone Number'
            )->addColumn(
                'fax',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20,
                [],
                'Fax'
            )->addColumn(
                'address',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null,
                [],
                'Address'
            )->addColumn(
                'street',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null,
                [],
                'Street'
            )->addColumn(
                'country',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null,
                [],
                'Country'
            )->addColumn(
                'city',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 50,
                [],
                'City'
            )->addColumn(
                'postcode',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20,
                [],
                'Postcode'
            )->addColumn(
                'total_sales',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 11,
                [],
                'total_sales'
            )->setComment(
                'magenest_test_vendor_vu'
            );

            $setup->getConnection()->createTable($table);

            $setup->endSetup();
        }

    }
}