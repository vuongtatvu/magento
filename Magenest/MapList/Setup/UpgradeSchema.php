<?php

    namespace Magenest\MapList\Setup;

    use Magento\Framework\Setup\UpgradeSchemaInterface;
    use Magento\Framework\Setup\ModuleContextInterface;
    use Magento\Framework\Setup\SchemaSetupInterface;
    use Magenest\MapList\Helper\Constant;
    use function PHPSTORM_META\type;

    /**
     * Class InstallSchema
     * Get the new tables up and running
     *
     *
     */
    class UpgradeSchema implements UpgradeSchemaInterface
    {

        /**
         * Upgrades DB schema for a module
         *
         * @param SchemaSetupInterface   $setup
         * @param ModuleContextInterface $context
         *
         * @return void
         */
        public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
        {
            $installer = $setup;
            $installer->startSetup();

            // Check the versions
            if (version_compare($context->getVersion(), '1.0.3') < 0) {
                // Check if the table already exists
                $tableName = $installer->getTable(Constant::LOCATION_TABLE);
                if ($installer->getConnection()->isTableExists($tableName) == true) {
                    // Declare data
                    $columns = [
                        'country' => [
                            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => true,
                            'comment'  => 'Country',
                        ],
                        'state_province' => [
                            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => true,
                            'comment'  => 'State/Province',
                        ],
                        'city' => [
                            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => true,
                            'comment'  => 'City',
                        ],
                        'zip' => [
                            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => true,
                            'comment'  => 'zip',
                        ],
                    ];

                    $connection = $installer->getConnection();
                    foreach ($columns as $name => $definition) {
                        $connection->addColumn($tableName, $name, $definition);
                    }
                }
            }
            if (version_compare($context->getVersion(), '1.2.0') < 0) {
                $tableName = $installer->getTable(Constant::MAP_TABLE);
                if($installer->getConnection()->isTableExists($tableName) == true){
                    $columns = [
                        'is_use_default_opening_hours' => [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => true,
                            'comment' => 'Use Default Opening Hours'
                        ],
                        'opening_hours' => [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => true,
                            'comment' => 'Opening Hours Json'
                        ],
                        'special_date' => [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => true,
                            'comment' => "Special Date Json"
                        ]
                    ];
                    $connection = $installer->getConnection();
                    foreach ($columns as $name => $definition){
                        $connection->addColumn($tableName,$name,$definition);
                    }
                }
            }
            if (version_compare($context->getVersion(), '1.2.0') < 0) {
                // Check if the table already exists
                $tableName = $installer->getTable(Constant::LOCATION_TABLE);
                if ($installer->getConnection()->isTableExists($tableName) == true) {
                    // Declare data
                    $columns = [
                        'gallery' => [
                            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => true,
                            'comment'  => 'Gallery',
                        ],
                    ];

                    $connection = $installer->getConnection();
                    foreach ($columns as $name => $definition) {
                        $connection->addColumn($tableName, $name, $definition);
                    }
                }
            }
            if (version_compare($context->getVersion(), '1.2.0') < 0) {
                $installer = $setup;
                $installer->startSetup();
                $connection = $installer->getConnection();
                $table = $installer->getConnection()->newTable($installer->getTable(Constant::LOCATION_GALLERY_TABLE)
                )->addColumn(
                    'location_gallery_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                    'Gallery ID'

                )->addColumn(
                    'name_image',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null, ['nullable' => false],
                    'Name Image'
                );
                $installer->getConnection()->createTable($table);
            }
            if (version_compare($context->getVersion(), '1.2.0') < 0) {
                // Check if the table already exists
                $tableName = $installer->getTable(Constant::LOCATION_GALLERY_TABLE);
                if ($installer->getConnection()->isTableExists($tableName) == true) {
                    // Declare data
                    $columns = [
                        'type_image' => [
                            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                            'nullable' => true,
                            'comment'  => 'Type Icon (1) & Type Gallery(2)',
                        ],
                    ];

                    $connection = $installer->getConnection();
                    foreach ($columns as $name => $definition) {
                        $connection->addColumn($tableName, $name, $definition);
                    }
                }
            }
            $installer->endSetup();
        }
    }