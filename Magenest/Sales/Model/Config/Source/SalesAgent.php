<?php
namespace Magenest\Sales\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;

/*  Custom Attribute Renderer   @author Webkul Core Team <support@webkul.com> */
class SalesAgent extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    public function getAllOptions($addEmpty = true)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productCollection = $objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\Collection');
        $collection = $productCollection->addAttributeToFilter('is_sales_agent', 1)->load();

        $options = [];

        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a sales --'), 'value' => ''];
        }
        foreach ($collection as $customer) {
            $options[] = ['label' => $customer->getName(), 'value' => $customer->getEntityId()];
        }

        return $options;
    }
}