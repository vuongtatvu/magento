<?php

namespace Magenest\StockStatus\Model\Product\Attribute\Source;

class Status extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => \Magento\Catalog\Model\Category::DM_PRODUCT, 'label' => __('Products only')],
                ['value' => \Magento\Catalog\Model\Category::DM_PAGE, 'label' => __('Static block only')],
                ['value' => \Magento\Catalog\Model\Category::DM_MIXED, 'label' => __('Static block and products')],
            ];
        }
        return $this->_options;
    }
}