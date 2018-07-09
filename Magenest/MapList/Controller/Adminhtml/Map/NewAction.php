<?php
/**
 * Created by Magenest.
 * Author: Pham Quang Hau
 * Date: 17/06/2016
 * Time: 09:45
 */

namespace Magenest\MapList\Controller\Adminhtml\Map;

use Magenest\MapList\Controller\Adminhtml\Map;

class NewAction extends Map
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
