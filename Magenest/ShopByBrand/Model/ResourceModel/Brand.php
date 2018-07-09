<?php

namespace   Magenest\ShopByBrand\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use function print_r;

/**
 * Class Brand
 *
 * @package Magenest\ShopByBrand\Model\ResourceModel
 */
class Brand extends AbstractDb
{
    /**
     * @var
     */
    protected $_brandProductTable;

    /**
     * Store model
     *
     * @var \Magento\Store\Model\Store
     */
    protected $_store = null;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_product;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * Core event manager proxy
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager = null;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManage;
    /**
     * @param \Psr\Log\LoggerInterface                                       $logger
     * @param \Magento\Framework\Model\ResourceModel\Db\Context              $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory
     * @param null                                                           $connectionName
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManagement,
        $connectionName = null
    ) {

        parent::__construct($context, $connectionName);
        $this->_product = $productFactory;
        $this->_logger = $logger;
        $this->_storeManage = $storeManagement;
    }

    public function _construct()
    {
        $this->_init('magenest_shop_brand', 'brand_id');
    }

    /**
     * Update Order Brand
     *
     * @param  $productId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function updateOrderBrand($productId)
    {
        $mainTable = $this->getMainTable();

        $productTable = $this->getTable('magenest_brand_product');

        $adapter = $this->_getConnection('read');

        $select = $adapter->select()->from(
            ['main_table' => $mainTable],
            '*'
        )
            ->join(['product_table' => $productTable], 'main_table.brand_id = product_table.brand_id')
            ->where('product_table.product_id='.$productId);

        $row = $adapter->fetchAssoc($select);

        return $row;
    }

    /**
     * Perform operations after object load
     *
     * @param  \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(AbstractModel $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_ids', $stores);
        }

        return parent::_afterLoad($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $rule
     * @return $this
     */
    protected function _afterDelete(AbstractModel $rule)
    {
        $connection = $this->getConnection();
        $connection->delete(
            $this->getTable('magenest_brand_store'),
            ['brand_id=?' => $rule->getId()]
        );
        $connection->delete(
            $this->getTable('magenest_brand_product'),
            ['brand_id=?' => $rule->getId()]
        );

        return parent::_afterDelete($rule);
    }

    /**
     * Assign rule to store views
     *
     * @param  \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object)
    {
        $oldIds = $this->lookupStoreIds($object->getId());
        $newIds = (array)$object->getStoreIds();
        if (empty($newIds)) {
            $newIds = (array)$object->getStoreId();
        }
        if (!empty($newIds)) {
            $this->_updateForeignKey($object, $newIds, $oldIds, 'magenest_brand_store', 'store_id');
            $this->_saveBrandProducts($object);
            $this->_logger->debug("Da xoa2");
        }
        return parent::_afterSave($object);
    }
    public function getAfterSave()
    {
    }
    /**
     * @param $brandId
     * @param $productId
     */
    public function _saveProductBrand($brandId, $productId)
    {

        $connection = $this->getConnection();
        $data = [];
        $data[] = [
            'brand_id' => (int)$brandId,
            'product_id' => (int)$productId,
            'position' => 0,
        ];

        $connection->insertMultiple($this->getBrandProductTable(), $data);
    }

    public function _deleteProductBrand($productId)
    {
        $connection = $this->getConnection();
        $data = [];
        $conditions = [
            'product_id = '.$productId,
        ];

        $connection->delete($this->getBrandProductTable(), $conditions);
    }

    /**
     * Save category products relation
     *
     * @param                                        \Magento\Catalog\Model\Category $category
     * @return                                       $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _saveBrandProducts($category)
    {

        $id = $category->getId();

        $products = (array)json_decode($category->getBrandProducts());
        $this->_logger->debug("Test product");
        $this->_logger->debug(print_r($products, true));
        $featuredProduct = $category->getFeaturedProduct();

        /**
         * Example re-save category
         */
        if ($products === null) {
            return $this;
        }

        /**
         * old category-product relationships
         */
        $oldProducts = $category->getProductsPosition();

        $insert = array_diff_key($products, $oldProducts);
        $delete = array_diff_key($oldProducts, $products);

        /**
         * Find product ids which are presented in both arrays
         * and saved before (check $oldProducts array)
         */
        $listProductExits=$this->_lookupIdAllProduct($this->getBrandProductTable(), $products);
        $connection = $this->getConnection();

        foreach ($listProductExits as $product) {
            $sql = "Delete FROM " . $this->getBrandProductTable()." Where product_id = {$product['product_id']} And brand_id={$product['brand_id']}";
            try {
                $connection->query($sql);
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
        $update = array_intersect_key($products, $oldProducts);
        $update = array_diff_assoc($update, $oldProducts);



        /**
         * Delete products from category
         */
        if (!empty($delete)) {
            $cond = ['product_id IN(?)' => array_keys($delete), 'brand_id=?' => $id];
            $connection->delete($this->getBrandProductTable(), $cond);
        }

        /**
         * Add products to category
         */
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId => $position) {
                if ($featuredProduct) {
                    $status = 0;

                    foreach ($featuredProduct as $identityId => $proId) {
                        if ($productId == $proId) {
                            $status = 1;
                        }
                    }

                    $data[] = [
                        'brand_id' => (int)$id,
                        'product_id' => (int)$productId,
                        'position' => (int)$position,
                        'featured_product' => (int)$status,
                    ];
                } else {
                    $data[] = [
                        'brand_id' => (int)$id,
                        'product_id' => (int)$productId,
                        'position' => (int)$position,
                    ];
                }
            }
            $this->_logger->debug("Insert");
            $this->_logger->debug(print_r($insert, true));
            $connection->insertMultiple($this->getBrandProductTable(), $data);
            $this->_logger->debug("Da xoa3");
        }

        /**
         * Update product positions in category
         */
        if (!empty($update)) {
            foreach ($update as $productId => $position) {
                if ($featuredProduct) {
                    $status = 0;

                    foreach ($featuredProduct as $identityId => $proId) {
                        if ($productId == $proId) {
                            $status = 1;
                        }
                    }

                    $where = ['brand_id = ?' => (int)$id, 'product_id = ?' => (int)$productId];
                    $bind = ['position' => (int)$position];
                    $bind = ['featured_product' => (int)$status];
                    $connection->update($this->getBrandProductTable(), $bind, $where);
                } else {
                    $where = ['brand_id = ?' => (int)$id, 'product_id = ?' => (int)$productId];
                    $bind = ['position' => (int)$position];
                    $connection->update($this->getBrandProductTable(), $bind, $where);
                }
            }
        }

        return $this;
    }

    /**
     * Get Website ids to which specified item is assigned
     *
     * @param  int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        return $this->_lookupIds($id, 'magenest_brand_store', 'store_id');
    }

    /**
     * Get ids to which specified item is assigned
     *
     * @param  int    $id
     * @param  string $tableName
     * @param  string $field
     * @return array
     */
    protected function _lookupIds($id, $tableName, $field)
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from(
            $this->getTable($tableName),
            $field
        )->where(
            'brand_id = ?',
            (int)$id
        );

        return $adapter->fetchCol($select);
    }
    protected function _lookupIdAllProduct($tableName, $oldProducts)
    {
        $adapter=$this->getConnection();
        $select=$adapter->select()->from($this->getTable($tableName));
        $this->_logger->debug(print_r($adapter->fetchAll($select), true));
        return $this->validateProduct($oldProducts, $adapter->fetchAll($select));
    }
    protected function validateProduct($newProduct, $allProduct)
    {
        $result=array();
        foreach ($newProduct as $productId => $position) {
            $check=0;
            foreach ($allProduct as $productOld) {
                if ($productId==$productOld['product_id']) {
                    $check=$productOld;
                    array_push($result, $check);
                    break;
                }
            }
        }
        $this->_logger->debug("product trung");
        $this->_logger->debug(print_r($result, true));
        return $result;
    }
    /**
     * Update post connections
     *
     * @param  \Magento\Framework\Model\AbstractModel $object
     * @param  Array                                  $newRelatedIds
     * @param  Array                                  $oldRelatedIds
     * @param  String                                 $tableName
     * @param  String                                 $field
     * @return void
     */
    protected function _updateForeignKey(
        AbstractModel $object,
        array $newRelatedIds,
        array $oldRelatedIds,
        $tableName,
        $field
    ) {
        $table = $this->getTable($tableName);
        $insert = array_diff($newRelatedIds, $oldRelatedIds);

        // All store view
        if (!$newRelatedIds[0]) {
            $insert=$this->getAllStore();
            $where = ['brand_id = ?' => (int)$object->getId(), $field.' IN (?)' => $oldRelatedIds];
            $this->getConnection()->delete($table, $where);
        }
        $delete = array_diff($oldRelatedIds, $newRelatedIds);

        if ($delete) {
            $where = ['brand_id = ?' => (int)$object->getId(), $field.' IN (?)' => $delete];
            $this->getConnection()->delete($table, $where);
        }

        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    'brand_id' => (int)$object->getId(),
                    $field => (int)$storeId
                ];
            }

            $this->getConnection()->insertMultiple($table, $data);
        }
    }
    public function updateForeignKeyBrandStore($idBrand)
    {
        $oldIds = $this->lookupStoreIds($idBrand);
        $this->_logger->debug('old id');
        $this->_logger->log(100, print_r($oldIds, true));
        $newIds =[];
        $this->updateBrandStore($idBrand, $newIds, $oldIds, 'magenest_brand_store', 'store_id');
    }
    protected function updateBrandStore($idBrand, array $newRelatedIds, array $oldRelatedIds, $tableName, $field)
    {
        $table = $this->getTable($tableName);
        $insert=$this->getAllStore();
        if ($oldRelatedIds) {
            $where = ['brand_id = ?' => $idBrand, $field.' IN (?)' => $oldRelatedIds];
            $this->getConnection()->delete($table, $where);
        }
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    'brand_id' => $idBrand,
                    $field => (int)$storeId
                ];
            }
            $this->getConnection()->insertMultiple($table, $data);
        }
    }
    /**
     * Get all id store
     *
     * @return array
     */
    public function getAllStore()
    {
        $allStoreId = array();

        $allStore = $this->_storeManage->getStores($withDefault = false);

        foreach ($allStore as $store) {
            $allStoreId[] = $store->getStoreId();
        }

        return $allStoreId;
    }
    /**
     * Get positions of associated to category products
     *
     * @param  \Magento\Catalog\Model\Category $category
     * @return array
     */
    public function getProductsPosition($category)
    {
        $select = $this->getConnection()->select()->from(
            $this->getBrandProductTable(),
            ['product_id', 'position']
        )->where(
            'brand_id = :brand_id'
        );
        $bind = ['brand_id' => (int)$category->getId()];

        return $this->getConnection()->fetchPairs($select, $bind);
    }

    /**
     * Category product table name getter
     *
     * @return string
     */
    public function getBrandProductTable()
    {
        if (!$this->_brandProductTable) {
            $this->_brandProductTable = $this->getTable('magenest_brand_product');
        }
        return $this->_brandProductTable;
    }

    /**
     * Get all product id not in brand
     *
     * @param  $id
     * @return array
     */
    public function getListIdProductOut($id)
    {
        $select=$this->getConnection()->select()->from(
            $this->getBrandProductTable(),
            ['product_id']
        )->where(
            'brand_id <>'.$id
        )->group('product_id');

        return $this->getConnection()->fetchCol($select);
    }

    /**
     * Get all product id
     *
     * @return array
     */
    public function getListIdProduct()
    {
        $select=$this->getConnection()->select()->from(
            $this->getBrandProductTable(),
            ['product_id']
        )->group('product_id');

        return $this->getConnection()->fetchCol($select);
    }
}
