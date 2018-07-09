<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:50
 */

namespace Magenest\MapList\Controller\Adminhtml\Category;

use Exception;
use Magenest\MapList\Controller\Adminhtml\Category;
use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

class Save extends Category
{
    /**
     * @var \Magenest\MapList\Model\CategoryFactory
     */
    protected $_categoryFactory;
    /**
     * @param Action\Context $context
     */
    protected $locationCategoryFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        \Magenest\MapList\Model\CategoryFactory $categoryFactory,
        \Magenest\MapList\Model\LocationCategoryFactory $locationCategoryFactory,
        LoggerInterface $logger
    ) {
        $this->_categoryFactory = $categoryFactory;
        $this->locationCategoryFactory = $locationCategoryFactory;
        parent::__construct($context, $resultPageFactory, $coreRegistry, $categoryFactory, $logger);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $locationList = json_decode($params['location_list']);
        $model = $this->_categoryFactory->create();
        $resultRedirect = $this->resultRedirectFactory->create();
        if (isset($params['category_id'])) {
            $model->load($params['category_id']);
        }
        try {
            $model->addData($params)->save();

            try {
                $this->saveLocationCategory($model->getId(), $locationList);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            $this->messageManager->addSuccess(__('Tag successfully saved.'));
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

    private function saveLocationCategory($categoryId, $locationList)
    {
        $currentLocationCategoryData = $this->locationCategoryFactory->create()
            ->getCollection()
            ->addFieldToFilter('category_id', $categoryId)
            ->getData();
        $parseLocationCategoryData = [];

        try {
            foreach ($currentLocationCategoryData as $locationCategoryData) {
                $parseLocationCategoryData[] = $locationCategoryData['location_id'];
            }
        } catch (\Exception $e) {
        }

        $removeListId = array_diff($parseLocationCategoryData, $locationList);
        $addListId = array_diff($locationList, $parseLocationCategoryData);

        foreach ($addListId as $id) {
            try {
                $this->locationCategoryFactory->create()
                    ->addData(['location_id' => $id, 'category_id' => $categoryId])
                    ->save();
            } catch (Exception $e) {
            }
        }

        try {
            $locationIdWillRemove = $this->locationCategoryFactory->create()
                ->getCollection()
                ->addFieldToFilter('location_id', $removeListId)
                ->getData();
            if (!!$locationIdWillRemove) {
                foreach ($locationIdWillRemove as $value) {
                    try {
                        $this->locationCategoryFactory->create()
                            ->load($value['location_category_id'])
                            ->delete();
                    } catch (\Exception $e) {
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
}
