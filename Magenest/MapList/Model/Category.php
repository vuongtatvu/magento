<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 10:00
 */

namespace Magenest\MapList\Model;

use Magenest\MapList\Model\ResourceModel\Category as CategoryResource;
use Magenest\MapList\Model\ResourceModel\Category\Collection as Collection;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magenest\MapList\Helper\Constant;

class Category extends AbstractModel
{
    protected $_eventPrefix = 'category';
    protected $_idFieldName = 'category_id';

    public function __construct(
        Context $context,
        Registry $registry,
        CategoryResource $resource,
        Collection $resourceCollection,
        $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init(Constant::CATEGORY_RESOURCE_MODEL);
    }

    public function getCategoryNameById($id)
    {
        return $this->load($id)->getData('title');
    }
}
