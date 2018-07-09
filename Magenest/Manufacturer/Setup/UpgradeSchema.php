<?php

namespace Magenest\Manufacturer\Setup;


use    Magento\Framework\Setup\UpgradeSchemaInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $setup->startSetup();


            $table =$setup->getConnection()->newTable($setup->getTable('manufacturer_entity')
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
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Name'
            )->addColumn(
                'enabled',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 1,
                [],
                'Enabled'
            )->addColumn(
                'address_street',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Address Street'
            )->addColumn(
                'address_city',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 100,
                [],
                'Address City'
            )->addColumn(
                'address_country',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 5,
                [],
                'Address Country'
            )->addColumn(
                'contact_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 100,
                [],
                'Contact Name'
            )->addColumn(
                'contact_phone',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 20,
                [],
                'Contact Phone'
            )->setComment(
                'manufacturer_entity'
            );

            $setup->getConnection()->createTable($table);

            $setup->endSetup();
        }

    }
}