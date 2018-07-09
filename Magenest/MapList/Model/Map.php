<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 10:00
 */

namespace Magenest\MapList\Model;

use Magenest\MapList\Model\ResourceModel\Map as MapResource;
use Magenest\MapList\Model\ResourceModel\Map\Collection as Collection;
use Magenest\MapList\Model\ModelInterface\MapInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magenest\MapList\Helper\Constant;

class Map extends AbstractModel implements MapInterface
{
    protected $_eventPrefix = 'map';

    const CACHE_TAG = 'maplist_map';
    protected $_idFieldName = 'map_id';

    public function __construct(
        Context $context,
        Registry $registry,
        MapResource $resource,
        Collection $resourceCollection,
        $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init(Constant::MAP_RESOURCE_MODEL);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setTitle($title)
    {
        return $this->getData(self::TITLE, $title);
    }

    public function setDescription($description)
    {
        return $this->getData(self::DESCRIPTION, $description);
    }

    public function setCreatedAt($unixTime)
    {
        return $this->getData(self::CREATED_AT, $unixTime);
    }

    public function setUpdatedAt($unixTime)
    {
        return $this->getData(self::UPDATED_AT, $unixTime);
    }
}
