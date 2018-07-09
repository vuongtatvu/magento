<?php
namespace Magenest\Manufacturer\Model;

use Magenest\Manufacturer\Model\ResourceModel\Manufacturer\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**      * @var array */
    protected $_loadedData;

    public function __construct($name, $primaryFieldName, $requestFieldName, CollectionFactory $employeeCollectionFactory, array $meta = [], array $data = [])
    {
        $this->collection = $employeeCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $manufacturer) {
            $this->_loadedData[$manufacturer->getId()] = $manufacturer->getData();
        }

        return $this->_loadedData;
    }
}