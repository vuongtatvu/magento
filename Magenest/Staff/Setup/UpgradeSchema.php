<?php

namespace Magenest\Staff\Setup;


use    Magento\Framework\Setup\UpgradeSchemaInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $setup->startSetup();


            $table =$setup->getConnection()->newTable($setup->getTable('magenest_staff')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Id'
            )->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Customer Id'
            )->addColumn(
                'nick_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Nick Name'
            )->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10,
                [],
                'Type'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 10,
                [],
                'status'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 5,
                [],
                'Updated At'
            )->setComment(
                'magenest_staff'
            );

            $setup->getConnection()->createTable($table);
            
            $setup->endSetup();
        }

    }
}