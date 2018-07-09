<?php
/**
 * Created by PhpStorm.
 * User: thien
 * Date: 07/04/2018
 * Time: 09:19
 */

namespace Magenest\MapList\Controller\Index;

use Magenest\MapList\Controller\DefaultController;
use Magenest\MapList\Helper\Constant;

class Liststore extends DefaultController
{
    public function execute()
    {
        $center = $this->getRequest()->getParam('center');
        $rad = $this->getRequest()->getParam('rad');
        $country = $this->getRequest()->getParam('country');
        $zip = $this->getRequest()->getParam('zip');
        $storeName = $this->getRequest()->getParam('storename');
        $stateProvince = $this->getRequest()->getParam('state_province');
        $city = $this->getRequest()->getParam('city');
        $map_id = $this->getRequest()->getParam('map_id');

        $unit = $this->getRequest()->getParam('unit');
        $unit = ($unit == 'google.maps.UnitSystem.METRIC') ? 6371 : 3959;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName(Constant::LOCATION_TABLE);

        // select by distance
        if ($center != '' && $rad != '') {
            $sql = "SELECT " . $tableName . ".location_id, magenest_maplist_map_location.map_location_id, title, short_description, address, website as mapWebsite, phone_number, email,
        ( " . $unit . " * acos(cos(radians(" . $center['lat'] . ")) * cos(radians(latitude)) * cos(radians(longitude) -
        radians(" . $center['lng'] . ")) + sin(radians(" . $center['lat'] . ")) * sin(radians(latitude ))) )
        AS distance FROM " . $tableName . "
        JOIN magenest_maplist_map_location ON magenest_maplist_map_location.location_id = " . $tableName . ".location_id
        WHERE magenest_maplist_map_location.map_id = " . $map_id . "
        AND is_active = 1
        HAVING distance < " . $rad . "       
        ORDER BY distance";
            // select location nearest
        } elseif ($center != '' && $rad == '') {

            $sql = "SELECT " . $tableName . ".location_id, magenest_maplist_map_location.map_location_id , latitude as lat, longitude as lng, title, short_description, address, website as mapWebsite, phone_number, email,
        ( 6371 * acos(cos(radians(" . $center['lat'] . ")) * cos(radians(latitude)) * cos(radians(longitude) -
        radians(" . $center['lng'] . ")) + sin(radians(" . $center['lat'] . ")) * sin(radians(latitude ))) )
        AS distance FROM " . $tableName . "
        JOIN magenest_maplist_map_location ON magenest_maplist_map_location.location_id = " . $tableName . ".location_id
        WHERE magenest_maplist_map_location.map_id = " . $map_id . "
        AND is_active = 1
        ORDER BY distance
        LIMIT 1";
            // select by area
        } else {
            $sql = $connection->select()
                ->from(
                    ['l' => $tableName],
                    ['l.location_id',
                        'latitude as lat',
                        'longitude as lng',
                        'ml.map_location_id',
                        'title',
                        'r.region_id',
                        'short_description',
                        'address',
                        'website as mapWebsite',
                        'phone_number',
                        'email',
                        ]
                )
                ->joinleft(
                    ['r' => 'directory_country_region'],
                    'r.default_name=l.state_province and r.default_name=l.country'

                )
                ->join(
                    ['ml' => 'magenest_maplist_map_location'],
                    'ml.location_id = l.location_id'

                )
                ->where('is_active = ?', 1)
                ->where('ml.map_id = ?',$map_id);
            if($country != null) {
                $sql->where('l.country LIKE ?', '%'.$country.'%');
            }
            if($storeName != null) {
                $sql->where('l.title LIKE ?', '%'.$storeName.'%');
            }
            if($stateProvince != null) {
                $sql->where('r.default_name LIKE ?', '%'.$stateProvince.'%');
            }
            if($zip != null) {
                $sql->where('l.zip LIKE ?', '%'.$zip.'%');
            }
            if($city != null) {
                $sql->where('l.city LIKE ?', '%'.$city.'%');
            }
        }
        $result = $connection->fetchAll($sql); // gives associated array, table fields as key in array.
        echo json_encode($result);
        return;

    }
}