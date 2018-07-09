<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 11:10
 */

namespace Magenest\MapList\Controller\Adminhtml\Map;

use Exception;
use Magenest\MapList\Controller\Adminhtml\Map;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\Registry;

class Save extends Map
{
    protected $locationCategoryFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        \Magenest\MapList\Model\MapFactory $mapFactory,
        \Magenest\MapList\Model\MapLocationFactory $mapLocationFactory,
        \Magenest\MapList\Model\LocationCategoryFactory $locationCategoryFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        LoggerInterface $logger
    ) {
        $this->locationCategoryFactory = $locationCategoryFactory;
        parent::__construct(
            $context,
            $resultPageFactory,
            $coreRegistry,
            $mapFactory,
            $mapLocationFactory,
            $resultRawFactory,
            $layoutFactory,
            $logger
        );
    }

    public function execute()
    {
        $params = $this->getRequest()->getParams();

        $locationCategory = isset($params['location_categories']) ? $params['location_categories'] : [];
        $locationList = json_decode($params['location_list']);
        $model = $this->mapFactory->create();
        $resultRedirect = $this->resultRedirectFactory->create();
        if (isset($params['map_id'])) {
            $model->load($params['map_id']);
        }
        try {
            $data = [];
            foreach ($params as $param => $value){
                if($param == 'opening_hours'){
                    $data['opening_hours'] = json_encode($value);
                }else{
                    $data[$param] = $value;
                }

            }
            if(!isset($params['is_use_default_opening_hours'])){
                $data['is_use_default_opening_hours'] = 0;
            }
            if(isset($params['templateSpecialDate'])){
                $data['special_date'] = json_encode($params['templateSpecialDate']);
            }
            $model->addData($data)->save();
            $this->messageManager->addSuccess(__('Map successfully saved.'));

            try {
                $this->saveMapLocationTable($model->getId(), $locationList, $locationCategory);
                //$this->messageManager->addSuccess(__('Location successfully saved.'));
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }

            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e, __('Something went wrong while saving the methods.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($params);

            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    private function saveMapLocationTable($mapId, $locationList, $locationCategory)
    {
        $currentMapLocationData = $this->mapLocationFactory->create()
            ->getCollection()
            ->addFieldToFilter('map_id', $mapId)
            ->getData();
        $parseCurrentMapLocationData = [];
        try {
            foreach ($currentMapLocationData as $mapLocationData) {
                $parseCurrentMapLocationData[] = $mapLocationData['location_id'];
            }
        } catch (\Exception $e) {
        }
        $removeListId = array_diff($parseCurrentMapLocationData, $locationList);
        $addListId = array_diff($locationList, $parseCurrentMapLocationData);

        foreach ($addListId as $locationId) {
            if (!!$locationId) {
                try {
                    $this->mapLocationFactory->create()
                        ->addData(['map_id' => $mapId, 'location_id' => $locationId])
                        ->save();
                } catch (\Exception $e) {
                }
            }
        }

        try {
            $locationLocationWillRemove = $this->mapLocationFactory->create()
                ->getCollection()
                ->addFieldToFilter('location_id', $removeListId)
                ->getData();

            if (!!$locationLocationWillRemove) {
                foreach ($locationLocationWillRemove as $value) {
                    try {
                        $this->mapLocationFactory->create()
                            ->load($value['map_location_id'])
                            ->delete();
                    } catch (\Exception $e) {
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
}
