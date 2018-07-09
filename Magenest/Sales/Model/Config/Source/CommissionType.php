<?php 

namespace Magenest\Sales\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

/** * Custom Attribute Renderer * * @author Webkul Core Team <support@webkul.com> */
class CommissionType extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    
    public function getAllOptions($addEmpty = true)
    {
        return [
            [
                'value' => 'fixed',
                'label' => __('Fixed')
            ],
            [
                'value' => 'percent',
                'label' => __('Percent')
            ],
        ];
    }
}