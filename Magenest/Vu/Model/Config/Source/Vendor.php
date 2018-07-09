<?php namespace Magenest\Vu\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

/** * Custom Attribute Renderer * * @author Webkul Core Team <support@webkul.com> */
class Vendor extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /** * @var OptionFactory */
    protected $_vendorCollectionFactory;

    /**
     * Construct
     *
     * @param \Magenest\Vu\Model\ResourceModel\Vendor\CollectionFactory $vendorCollectionFactory
     */
    public function __construct(
        \Magenest\Vu\Model\ResourceModel\Vendor\CollectionFactory $vendorCollectionFactory
    ) {
        $this->_vendorCollectionFactory = $vendorCollectionFactory;
    }

    /**
     * Return option array
     *
     * @param bool $addEmpty
     * @return array
     */
    public function getAllOptions($addEmpty = true)
    {
        /** @var      * @param \Test\PartTime\Model\ResourceModel\vendor\CollectionFactory $vendorCollectionFactory
        $collection */
        $collection = $this->_vendorCollectionFactory->create();

        $collection->load();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a vendor --'), 'value' => ''];
        }
        foreach ($collection as $vendor) {
            $options[] = ['label' => $vendor->getFirstName().' '.$vendor->getLastName(), 'value' => $vendor->getId()];
        }

        return $options;
    }
}