<?php

namespace Magenest\Sales\Setup;


use    Magento\Framework\Setup\UpgradeSchemaInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $setup->startSetup();

            $table =$setup->getConnection()->newTable($setup->getTable('magenest_sales')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Entity Id'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Order Id'
            )->addColumn(
                'order_item_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Order Item Id'
            )->addColumn(
                'order_item_sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Order Item Sku'
            )->addColumn(
                'order_item_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Order Item Price'
            )->addColumn(
                'commision_percent',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Commision Percent'
            )->addColumn(
                'commission_value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Commission Value'
            )->setComment(
                'magenest_sales'
            );

            $setup->getConnection()->createTable($table);

            $setup->endSetup();
        }

    }
}