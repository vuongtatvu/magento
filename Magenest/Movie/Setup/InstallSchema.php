<?php

namespace Magenest\Movie\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table =$setup->getConnection()->newTable($setup->getTable('magenest_movie')
        )->addColumn(
            'movie_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
            [
                'identity' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Movie id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
            [],
            'Name'
        )->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
            [],
            'Description'
        )->addColumn(
            'rating',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
            [],
            'Rating'
        )->addColumn(
            'director_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
            [],
            'Directot id'
        )->setComment(
            'Magenest Movie Table'
        );

        $setup->getConnection()->createTable($table);

        $table =$setup->getConnection()->newTable(
            $setup->getTable('magenest_director')
        )->addColumn(
            'director_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
            [
                'identity' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Director id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
            [],
            'Name'
        )->setComment(
            'Magenest Director Table'
        );

        $setup->getConnection()->createTable($table);

        $table =$setup->getConnection()->newTable(
            $setup->getTable('magenest_actor')
        )->addColumn(
            'actor_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
            [
                'identity' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Actor id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
            [],
            'Name'
        )->setComment(
            'Magenest Actor Table'
        );

        $setup->getConnection()->createTable($table);

        $table =$setup->getConnection()->newTable(
            $setup->getTable('magenest_movie_actor')
        )->addColumn(
            'movie_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
            [],
            'Movie id'
        )->addColumn(
            'actor_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255,
            [],
            'Actor id'
        )->setComment(
            'Magenest Movie Actor Table'
        );

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}