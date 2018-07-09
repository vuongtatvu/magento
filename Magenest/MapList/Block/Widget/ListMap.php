<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 11/22/16
 * Time: 09:32
 */

namespace Magenest\MapList\Block\Widget;

use Magenest\MapList\Helper\Constant;

class ListMap extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{

    protected $_mapFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\MapList\Model\MapFactory $mapFactory,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->_mapFactory = $mapFactory;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('widget/listmap.phtml');
    }


    public function getDataView()
    {
        return $this->_mapFactory->create()->getCollection()
            ->addFieldToFilter('is_active', Constant::MAP_STATUS_ACTIVE)
            ->getData();
    }
}
