<?php
namespace Magenest\Movie\Setup;

use    Magento\Framework\Setup\UpgradeSchemaInterface;
use    Magento\Framework\Setup\ModuleContextInterface;
use    Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $setup->startSetup();

            $setup->getConnection()->addForeignKey(
                $setup->getFkName(
                    'magenest_movie',
                    'director_id',
                    'magenest_director',
                    'director_id'
                ),
                $setup->getTable('magenest_movie'),
                'director_id',
                $setup->getTable('magenest_director'),
                'director_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
            $setup->getConnection()->addForeignKey(
                $setup->getFkName(
                    'magenest_movie_actor',
                    'movie_id',
                    'magenest_movie',
                    'movie_id'
                ),
                $setup->getTable('magenest_movie_actor'),
                'movie_id',
                $setup->getTable('magenest_movie'),
                'movie_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
            $setup->getConnection()->addForeignKey(
                $setup->getFkName(
                    'magenest_movie_actor',
                    'actor_id',
                    'magenest_actor',
                    'actor_id'
                ),
                $setup->getTable('magenest_movie_actor'),
                'actor_id',
                $setup->getTable('magenest_actor'),
                'actor_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );

            $setup->endSetup();
        }

    }
}