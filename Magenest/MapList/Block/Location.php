<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/16/16
 * Time: 21:55
 */

namespace Magenest\MapList\Block;

class Location extends Block
{
    public function getMap()
    {
        $mapData = new \stdClass();
        $mapData->map = null;
        $mapData->currentMenu = 'location';
        $mapData->config = $this->getConfig();
        $locationModel = $this->_coreRegistry->registry('maplist_location_model');

        try {
            $mapLocationData = $locationModel->getData();
            $mapLocationData['small_image_url'] = $this->getImageUrl($mapLocationData['small_image']);
//            $mapLocationData['big_image_url'] = $this->getBigImageUrl($mapLocationData['big_image']);
            $mapLocationDataArr = [$mapLocationData];
            $this->getCategoryForLocation($mapLocationDataArr, $locationModel->getId());
            $mapData->location = $mapLocationDataArr;
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
}
