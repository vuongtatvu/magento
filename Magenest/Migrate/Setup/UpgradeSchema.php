<?php
/**
 * Created by PhpStorm.
 * User: keysnt
 * Date: 31/05/2018
 * Time: 15:34
 */
namespace Magenest\Migrate\Setup;
use	Magento\Framework\Setup\UpgradeSchemaInterface;
use	Magento\Framework\Setup\ModuleContextInterface;
use	Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context){
        if	(version_compare($context->getVersion(),	'1.0.2')	<	0)	{
            $installer = $setup->startSetup();
            $table = $installer->getConnection()->newTable(
                $installer->getTable('woocommerce_product_tmp')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,[
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
                'entity id'
            )->addColumn(
                'woocommerce_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [],
                'product in woocommerce'
            )->addColumn(
                'product_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'product name'
            )->addColumn(
                'product_parent',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [],
                'product parent'
            )->addColumn(
                'product_magento',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [],
                'product magento'
            )->setComment('Product Tmp Woocommerce');
            $installer->getConnection()->createTable($table);
            $installer->endSetup();
        }
    }
}