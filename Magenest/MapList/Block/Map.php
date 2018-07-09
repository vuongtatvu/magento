<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/14/16
 * Time: 13:31
 */

namespace Magenest\MapList\Block;

class Map extends Block
{
    public function getMap()
    {
        $map = $this->_coreRegistry->registry('maplist_map_model');
        $mapData = new \stdClass();
        $mapData->currentMenu = 'map';
        $mapData->config = $this->getConfig();
        try {
            $mapData->map = $map->getData();
        } catch (\Exception $e) {
            $mapData->map = null;
        }

        try {
            $mapLocationModel = $this->_mapLocationFactory->create();
            $mapLocationData = $mapLocationModel->getCollection()
                ->join(
                    ['cp_table' => 'magenest_maplist_location'],
                    'main_table.location_id = cp_table.location_id'
                )
                ->addFieldToFilter('map_id', $map->getId())
                ->addFieldToFilter('cp_table.is_active', '1')
                ->getData();

            $locationId = [];
            foreach ($mapLocationData as $key => $locationValue) {
                $locationId[] = $locationValue['location_id'];
                $mapLocationData[$key]['small_image_url'] = $this->getImageUrl($locationValue['small_image']);
                $mapLocationData[$key]['gallery_image_url'] = $locationValue['gallery'];
            }

            $this->getCategoryForLocation($mapLocationData, $locationId);
            $mapData->location = $mapLocationData;
        } catch (\Exception $e) {
            $mapData->location = [];
        }

        return $mapData;
    }

    private function getCategoryForLocation(&$mapLocationData, $locationId)
    {
        $locationCategoryModel = $this->_locationCategoryFactory->create();
        $locationCategoryData = $locationCategoryModel->getCollection()
            ->join(
                ['cp_table' => 'magenest_maplist_category'],
                'main_table.category_id = cp_table.category_id',
                'title'
            )
            ->addFieldToFilter('location_id', $locationId)->getData();
        $locationCategoryArray = [];
        foreach ($locationCategoryData as $locationCategory) {
            $locationCategoryArray[$locationCategory['location_id']][] = [
                'categoryId' => $locationCategory['category_id'],
                'title' => $locationCategory['title'],
            ];
        }
        foreach ($mapLocationData as $locationKey => $location) {
            if (array_key_exists($location['location_id'], $locationCategoryArray)) {
                $mapLocationData[$locationKey]['category'] = $locationCategoryArray[$location['location_id']];
            }
        }
    }

    public function getCountry()
    {
        $countrys = $this->_country->toOptionArray();
        $countrys[0] = array(
            'value' => '',
            'label' => 'Select Country'
        );
        return $countrys;
    }

}
