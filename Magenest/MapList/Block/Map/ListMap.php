<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 11/7/16
 * Time: 09:42
 */

namespace Magenest\MapList\Block\Map;

use Magenest\MapList\Helper\Constant;
use Magento\Catalog\Block\Product\Context;

class ListMap extends \Magento\Framework\View\Element\Template
{
    protected $_mapFactory;

    protected $_currency;

    public function __construct(
        \Magenest\MapList\Model\MapFactory $mapFactory,
        Context $context,
        array $data
    ) {
        $this->_mapFactory = $mapFactory;
        parent::__construct($context, $data);
    }

    public function getDataView()
    {
        return $this->_mapFactory->create()->getCollection()
            ->addFieldToFilter('is_active', Constant::MAP_STATUS_ACTIVE)
            ->getData();
    }
}
