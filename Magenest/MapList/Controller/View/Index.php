<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/16/16
 * Time: 21:53
 */

namespace Magenest\MapList\Controller\View;

use Magenest\MapList\Controller\DefaultController;

class Index extends DefaultController
{
    public function execute()
    {
//        $url_key = trim($this->getRequest()->getPathInfo(),'/maplist/view/index/');
//        $url_key = rtrim($url_key, '/');
        $locationId = $this->getRequest()->getParam('id');
        $locationModel = $this->_locationFactory->create();
        $location = $locationModel->load($locationId);
        if (!$location->getData()) {  //if $location id not found
            $this->_forward('noroute');
        }
        //if current location is not active ~~> forward not found
        if ($location->getData('is_active') == 0) {
            $this->_forward('noroute');
        }

        $this->_view->getPage()->getConfig()->getTitle()->set(__('Location Detail'));
        $this->_coreRegistry->register('maplist_location_model', $location);

        return $this->resultPageFactory->create();
    }
}
