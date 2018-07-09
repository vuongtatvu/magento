<?php

namespace Magenest\MapList\Controller\Index;

use Magenest\MapList\Controller\DefaultController;

class Index extends DefaultController
{
    public function execute()
    {
        $mapId = $this->getRequest()->getParam('id');
        $mapModel = $this->_mapFactory->create();
        $map = $mapModel->load($mapId);
        if (!$map->getData()) {  //if map id not found
            $this->_forward('noroute');
        }
        //if current map is not active ~~> forward not found
        if ($map->getData('is_active') == 0) {
            $this->_forward('noroute');
        }

        $this->_view->getPage()->getConfig()->getTitle()->set(__('Store Locator'));
        $this->_coreRegistry->register('maplist_map_model', $map);

        return $this->resultPageFactory->create();
    }
}
