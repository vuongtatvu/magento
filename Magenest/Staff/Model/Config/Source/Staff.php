<?php
namespace Magenest\Staff\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;

/*  Custom Attribute Renderer   @author Webkul Core Team <support@webkul.com> */
class Staff extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    public function getAllOptions($addEmpty = true)
    {

        return [
            [
                'value' => '1',
                'label' => __('Lv1')
            ],
            [
                'value' => '2',
                'label' => __('Lv2')
            ],
            [
                'value' => '0',
                'label' => __('Not Staff')
            ],
        ];
    }
}