<?php

namespace Magenest\PartTime\Setup;


use    Magento\Framework\Setup\UpgradeSchemaInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $setup->startSetup();

            $table =$setup->getConnection()->newTable($setup->getTable('magenest_part_time')
            )->addColumn(
                'member_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Customer Name'
            )->addColumn(
                'address',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Customer Address'
            )->addColumn(
                'phone',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Customer Phone'
            )->addColumn(
                'created_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE, 255,
                [],
                'Created time'
            )->addColumn(
                'updated_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE, 255,
                [],
                'Updated time'
            )->setComment(
                'magenest_part_time'
            );

            $setup->getConnection()->createTable($table);

            $setup->endSetup();
        }

    }
}