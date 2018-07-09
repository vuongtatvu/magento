<?php

namespace Magenest\Movie\Block\Adminhtml\System\Config;
use Magento\Framework\View\Element\Template;

class RowActor extends \Magento\Config\Block\System\Config\Form\Field
{

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $actorCollection = $objectManager->create('\Magenest\Movie\Model\ResourceModel\MagenestActor\Collection');
        $collection = $actorCollection->load();
        return count($collection);
    }


}