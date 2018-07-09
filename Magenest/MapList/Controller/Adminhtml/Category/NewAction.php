<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:51
 */

namespace Magenest\MapList\Controller\Adminhtml\Category;

use Magenest\MapList\Controller\Adminhtml\Category;

class NewAction extends Category
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
