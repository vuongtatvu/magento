<?php
namespace Magenest\StockStatus\Setup;
use	Magento\Framework\Setup\UpgradeSchemaInterface;
use	Magento\Framework\Setup\ModuleContextInterface;
use	Magento\Framework\Setup\SchemaSetupInterface;
class UpgradeSchema	implements	UpgradeSchemaInterface	{
    public function	upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface	$context
    ){
        if (version_compare($context->getVersion(), '2.1.0') < 0) {
            $installer = $setup;
            $installer->startSetup();

            $magenest_icon = $installer->getConnection()->newTable(
                $installer->getTable('magenest_icon')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
                'Entity Id'
            )->addColumn(
                'stockstatus_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [],
                'Stock Status ID'
            )->addColumn(
                'path_image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Path of Icon Image'
            )->setOption('type', 'InnoDB')->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($magenest_icon);

            $magenest_stockstatus_managerqtyrule = $installer->getConnection()->newTable(
                $installer->getTable('magenest_stockstatus_managerqtyrule')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
                'Entity Id'
            )->addColumn(
                'qty_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'nullable' => false
            ],
                'From Quantity'
            )->addColumn(
                'qty_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'nullable' => false
            ],
                'To Quantity'
            )->addColumn(
                'rule',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'nullable' => true
            ],
                'Stock Status Id'
            )->addColumn(
                'status_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null, [
                'unsigned' => true,
                'nullable' => false
            ],
                'Stock Status Rule Id'
            )->setOption('type', 'InnoDB')->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($magenest_stockstatus_managerqtyrule);

            $installer->endSetup();
        }
    }
}