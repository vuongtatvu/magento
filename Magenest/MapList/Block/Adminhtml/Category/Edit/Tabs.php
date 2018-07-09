<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:51
 */

namespace Magenest\MapList\Block\Adminhtml\Category\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('category_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Category Config'));
    }
}
