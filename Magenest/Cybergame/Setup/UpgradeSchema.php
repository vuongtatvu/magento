<?php
namespace Magenest\Cybergame\Setup;

use    Magento\Framework\Setup\UpgradeSchemaInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.1') < 0) {

            $setup->startSetup();

            $table = $setup->getConnection()->newTable($setup->getTable('gamer_account_list')
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
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Product Id'
            )->addColumn(
                'account_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Account Name'
            )->addColumn(
                'password',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Password'
            )->addColumn(
                'hour',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Hour'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE, 255,
                [],
                'Created At'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE, 255,
                [],
                'Updated At'
            )->addIndex(
                $setup->getIdxName('gamer_account_list', ['product_id']), ['product_id']
            )->addIndex(
                $setup->getIdxName('gamer_account_list', ['account_name']), ['account_name']
            )->setComment(
                'Gamer Account List'
            );

            $setup->getConnection()->createTable($table);

            $table = $setup->getConnection()->newTable($setup->getTable('room_extra_option')
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
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Product Id'
            )->addColumn(
                'number_pc',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Number Pc'
            )->addColumn(
                'address',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Address'
            )->addColumn(
                'food_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Food Price'
            )->addColumn(
                'drink_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
                [],
                'Drink Price'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE, 255,
                [],
                'Created At'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE, 255,
                [],
                'Updated At'
            )->addIndex(
                $setup->getIdxName('room_extra_option', ['product_id']), ['product_id']
            )->setComment(
                'Room Extra Option'
            );

            $setup->getConnection()->createTable($table);


            $setup->endSetup();
        }


    }
}