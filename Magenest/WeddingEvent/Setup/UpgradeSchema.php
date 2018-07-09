<?php

namespace Magenest\WeddingEvent\Setup;


use    Magento\Framework\Setup\UpgradeSchemaInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.0') < 0) {

            $setup->startSetup();

            $table =$setup->getConnection()->newTable($setup->getTable('magenest_wedding_event')
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
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Title'
            )->addColumn(
                'commission',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Commission'
            )->addColumn(
                'bride_firstname',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Bride Firstname'
            )->addColumn(
                'bride_lastname',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Bride Lastname'
            )->addColumn(
                'bride_email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Bride Email'
            )->addColumn(
                'sent_to_bride',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN, 10,
                [],
                'Sent To Bride'
            )->addColumn(
                'groom_firstname',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Groom Firstname'
            )->addColumn(
                'groom_lastname',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Groom Lastname'
            )->addColumn(
                'groom_email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Groom Email'
            )->addColumn(
                'sent_to_groom',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN, 10,
                [],
                'Sent To Groom'
            )->addColumn(
                'message',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                [],
                'Message'
            )->setComment(
                'magenest_wedding_event'
            );

            $setup->getConnection()->createTable($table);

            $setup->endSetup();
        }

    }
}