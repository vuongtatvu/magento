<?php
namespace Magenest\WeddingEvent\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

/** * Custom Attribute Renderer * * @author Webkul Core Team <support@webkul.com> */
class WeddingEvent extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * Return option array
     *
     * @param bool $addEmpty
     * @return array
     */
    public function getAllOptions($addEmpty = true)
    {
        /** @var      * @param \Magenest\WeddingEvent\Model\ResourceModel\WeddingEvent\CollectionFactory $weddingCollectionFactory
        $collection */

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('\Magenest\WeddingEvent\Model\ResourceModel\WeddingEvent\CollectionFactory');
        $collection = $productCollection->create();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a weddingEvent --'), 'value' => ''];
        }
        foreach ($collection as $wedding) {
            $options[] = ['label' => $wedding->getTitle(), 'value' => $wedding->getId()];
        }

        return $options;
    }
}