<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/11/16
 * Time: 10:00
 */

namespace Magenest\MapList\Helper;

class Constant
{
    const MAP_STATUS_ACTIVE = 1;
    const MAP_STATUS_INACTIVE = 0;

    const MAP_TABLE = 'magenest_maplist_map';
    const LOCATION_TABLE = 'magenest_maplist_location';
    const MAP_LOCATION_TABLE = 'magenest_maplist_map_location';
    const CATEGORY_TABLE = 'magenest_maplist_category';
    const LOCATION_CATEGORY_TABLE = 'magenest_maplist_location_category';
    const LOCATION_PRODUCT_TABLE = 'magenest_maplist_location_product';
    const LOCATION_GALLERY_TABLE = 'magenest_maplist_location_gallery';


    const MAP_TABLE_ID = 'map_id';
    const LOCATION_TABLE_ID = 'location_id';
    const MAP_LOCATION_TABLE_ID = 'map_location_id';
    const CATEGORY_TABLE_ID = 'category_id';
    const LOCATION_CATEGORY_TABLE_ID = 'location_category_id';
    const LOCATION_PRODUCT_TABLE_ID = 'location_product_id';
    const LOCATION_GALLERY_TABLE_ID = 'location_gallery_id';


    const MAP_MODEL = 'Magenest\MapList\Model\Map';
    const LOCATION_MODEL = 'Magenest\MapList\Model\Location';
    const MAP_LOCATION_MODEL = 'Magenest\MapList\Model\MapLocation';
    const CATEGORY_MODEL = 'Magenest\MapList\Model\Category';
    const LOCATION_CATEGORY_MODEL = 'Magenest\MapList\Model\LocationCategory';
    const LOCATION_PRODUCT_MODEL = 'Magenest\MapList\Model\LocationProduct';
    const LOCATION_GALLERY_MODEL = 'Magenest\MapList\Model\LocationGallery';


    const MAP_RESOURCE_MODEL = 'Magenest\MapList\Model\ResourceModel\Map';
    const LOCATION_RESOURCE_MODEL = 'Magenest\MapList\Model\ResourceModel\Location';
    const MAP_LOCATION_RESOURCE_MODEL = 'Magenest\MapList\Model\ResourceModel\MapLocation';
    const CATEGORY_RESOURCE_MODEL = 'Magenest\MapList\Model\ResourceModel\Category';
    const LOCATION_CATEGORY_RESOURCE_MODEL = 'Magenest\MapList\Model\ResourceModel\LocationCategory';
    const LOCATION_PRODUCT_RESOURCE_MODEL = 'Magenest\MapList\Model\ResourceModel\LocationProduct';
    const LOCATION_GALLERY_RESOURCE_MODEL = 'Magenest\MapList\Model\ResourceModel\LocationGallery';
}
