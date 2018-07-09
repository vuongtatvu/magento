<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 23:05
 */

namespace Magenest\MapList\Model;

use Magento\Framework\Model\AbstractModel;
use Magenest\MapList\Helper\Constant;

class MapLocation extends AbstractModel
{
    protected $_idFieldName = 'map_location_id';

    protected function _construct()
    {
        $this->_init(Constant::MAP_LOCATION_RESOURCE_MODEL);
    }

    public function getMapId()
    {
        return $this->getData('map_id');
    }

    public function getLocationId()
    {
        return $this->getData('location_id');
    }

    public function settMapId($mapId)
    {
        return $this->setData('map_id', $mapId);
    }

    public function setLocationId($locationId)
    {
        return $this->setData('location_id', $locationId);
    }
}
