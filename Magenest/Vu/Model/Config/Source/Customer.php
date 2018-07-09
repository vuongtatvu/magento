<?php namespace Magenest\Vu\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

/** * Custom Attribute Renderer * * @author Webkul Core Team <support@webkul.com> */
class Customer extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /** * @var OptionFactory */
    protected $_customerCollectionFactory;

    /**
     * Construct
     *
     * @param
     */
    public function __construct(
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
    ) {
        $this->_customerCollectionFactory = $customerCollectionFactory;
    }

    /**
     * Return option array
     *
     * @param bool $addEmpty
     * @return array
     */
    public function getAllOptions($addEmpty = true)
    {
        /** @var      * @param \Test\PartTime\Model\ResourceModel\customer\CollectionFactory $customerCollectionFactory
        $collection */
        $collection = $this->_customerCollectionFactory->create();

        $collection->load();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a customer --'), 'value' => ''];
        }
        foreach ($collection as $customer) {
            $options[] = ['label' => $customer->getName(), 'value' => $customer->getId()];
        }

        return $options;
    }
}