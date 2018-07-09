<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 16/09/2016
 * Time: 09:18
 */

namespace Magenest\MapList\Controller\Adminhtml\Map;

use Magento\Backend\App\Action;
use Magenest\MapList\Controller\Adminhtml\Map;

class Edit extends Map
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Magenest\MapList\Model\Map $model */
        $model = $this->mapFactory->create();

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This map doesn\'t exist'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
            $mapLocationModel = $this->mapLocationFactory->create();
            $mapLocationData = $mapLocationModel->getCollection()
                ->join(
                    ['cp_table' => 'magenest_maplist_location'],
                    'main_table.location_id = cp_table.location_id'
                )
                ->addFieldToFilter('map_id', $id)->getData();
            $this->coreRegistry->register('maplist_location_data', $mapLocationData);
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->coreRegistry->register('maplist_map_edit', $model);
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()
            ->prepend(
                $model->getId() ? __('Edit Map', $model->getData('name')) : __('New Map')
            );

        return $resultPage;
    }
}
