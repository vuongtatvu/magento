<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 19/09/2016
 * Time: 13:50
 */

namespace Magenest\MapList\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magenest\MapList\Controller\Adminhtml\Category;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class Edit extends Category
{
    protected $locationCategoryFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        \Magenest\MapList\Model\CategoryFactory $categoryFactory,
        \Magenest\MapList\Model\LocationCategoryFactory $locationCategoryFactory,
        LoggerInterface $logger
    ) {
        $this->locationCategoryFactory = $locationCategoryFactory;
        parent::__construct(
            $context,
            $resultPageFactory,
            $coreRegistry,
            $categoryFactory,
            $logger
        );
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Magenest\MapList\Model\Map $model */
        $model = $this->categoryFactory->create();

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This category doesn\'t exist'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }

            $mapLocationModel = $this->locationCategoryFactory->create();
            $mapLocationData = $mapLocationModel->getCollection()
                ->join(
                    ['cp_table' => 'magenest_maplist_location'],
                    'main_table.location_id = cp_table.location_id'
                )
                ->addFieldToFilter('category_id', $id)->getData();
            $this->coreRegistry->register('maplist_location_data', $mapLocationData);
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->coreRegistry->register('maplist_category_edit', $model);
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()
            ->prepend(
                $model->getId() ? __('Edit Tag', $model->getData('name')) : __('New Tag')
            );

        return $resultPage;
    }
}
