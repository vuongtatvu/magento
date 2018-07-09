<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 11/22/16
 * Time: 10:07
 */

namespace Magenest\MapList\Block\Widget;

use Magenest\MapList\Helper\Constant;

class ListStore extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{

    protected $_mapFactory;
    protected $_mapLocationFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\MapList\Model\MapFactory $mapFactory,
        array $data,
        \Magenest\MapList\Model\MapLocationFactory $mapLocationFactory
    ) {
        parent::__construct($context, $data);
        $this->_mapFactory = $mapFactory;
        $this->_mapLocationFactory = $mapLocationFactory;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('widget/liststore.phtml');
    }


    public function getMap()
    {
        $dataConfig = $this->getData();
        $mapId = $dataConfig['map_id'];
        $showListLocation = $dataConfig['show_list'];
        $mapLocationData = $this->_mapLocationFactory->create()
            ->getCollection()
            ->join(
                ['cp_table' => 'magenest_maplist_location'],
                'main_table.location_id = cp_table.location_id'
            )
            ->addFieldToFilter('map_id', $mapId)
            ->addFieldToFilter('cp_table.is_active', '1')
            ->getData();
        $locationId = [];
        foreach ($mapLocationData as $key => $locationValue) {
            $locationId[] = $locationValue['location_id'];
            $mapLocationData[$key]['small_image_url'] = $this->getImageUrl($locationValue['small_image']);
            $mapLocationData[$key]['big_image_url'] = $this->getBigImageUrl($locationValue['big_image']);
        }

        $dataView = [
            'list_location' => $mapLocationData,
            'show_list_location' => $showListLocation
        ];

        return $dataView;
    }
}
