<?php
namespace Magenest\Movie\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;

/*  Custom Attribute Renderer   @author Webkul Core Team <support@webkul.com> */
class DirectorSelect extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    public function getAllOptions($addEmpty = true)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('Magenest\Movie\Model\ResourceModel\MagenestDirector\Collection');
        $collection = $productCollection->load();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a customer --'), 'value' => ''];
        }
        foreach ($collection as $customer) {
            $options[] = ['label' => $customer->getName(), 'value' => $customer->getDirectorId()];
        }

        return $options;
    }
}