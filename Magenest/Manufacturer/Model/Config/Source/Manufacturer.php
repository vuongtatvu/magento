<?php
namespace Magenest\Manufacturer\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

/** * Custom Attribute Renderer * * @author Webkul Core Team <support@webkul.com> */
class Manufacturer extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * Return option array
     *
     * @param bool $addEmpty
     * @return array
     */
    public function getAllOptions($addEmpty = true)
    {
        /** @var      * @param \Magenest\Manufacturer\Model\ResourceModel\Manufacturer\CollectionFactory $manufacturerCollectionFactory
        $collection */

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('\Magenest\Manufacturer\Model\ResourceModel\Manufacturer\CollectionFactory');
        $collection = $productCollection->create();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a manufacturer --'), 'value' => ''];
        }
        foreach ($collection as $manufacturer) {
            $options[] = ['label' => $manufacturer->getName(), 'value' => $manufacturer->getEntityId()];
        }

        return $options;
    }
}