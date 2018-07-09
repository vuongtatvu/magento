<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/12/16
 * Time: 11:09
 */

namespace Magenest\MapList\Controller\Adminhtml\Location;

use Magento\Backend\App\Action;
use Magenest\MapList\Controller\Adminhtml\Location;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * Class Edit
 * @package Magenest\MapList\Controller\Adminhtml\Location
 */
class Edit extends Location
{
    /**
     * @var \Magenest\MapList\Model\LocationCategoryFactory
     */
    protected $locationCategoryFactory;

    /**
     * @var \Magenest\MapList\Model\LocationProductFactory
     */
    protected $locationProductFactory;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param \Magenest\MapList\Model\LocationFactory $locationFactory
     * @param \Magenest\MapList\Model\LocationCategoryFactory $locationCategoryFactory
     * @param \Magenest\MapList\Model\LocationProductFactory $locationProductFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        \Magenest\MapList\Model\LocationFactory $locationFactory,
        \Magenest\MapList\Model\LocationCategoryFactory $locationCategoryFactory,
        \Magenest\MapList\Model\LocationProductFactory $locationProductFactory,
        LoggerInterface $logger
    ) {
        $this->locationCategoryFactory = $locationCategoryFactory;
        $this->locationProductFactory = $locationProductFactory;
        parent::__construct($context, $coreRegistry, $resultPageFactory, $locationFactory, $logger);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Magenest\MapList\Model\Map $model */
        $model = $this->locationFactory->create();
        if ($id) {
            $model->load($id);
            try {
                $locationCategoryData = [];
                $categoryData = $this->locationCategoryFactory->create()->getCollection()
                    ->join(
                        ['cp_table' => 'magenest_maplist_category'],
                        'main_table.category_id = cp_table.category_id'
                    )
                    ->addFieldToFilter('location_id', $id)->getData();

                foreach ($categoryData as $category) {
                    $locationCategoryData[] = $category['category_id'];
                }

                $model->setData('location_categories', $locationCategoryData);
            } catch (\Exception $e) {
                $model->setData('location_categories', []);
                $this->messageManager->addError($e->getMessage());
            }

            if (!$model->getId()) {
                $this->messageManager->addError(__('This location doesn\'t exist'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }

            try {
                $locationProductData = $this->locationProductFactory->create()->getCollection()
                    ->addFieldToFilter('location_id', $id)->getData();
            } catch (\Exception $e) {
                $locationProductData = [];
            }
            $this->coreRegistry->register('maplist_location_selected_product', $locationProductData);
        }

        $this->coreRegistry->register('location', $model);

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        $this->coreRegistry->register('maplist_location_edit', $model);
        $location = [
            'latitude' => $model['latitude'],
            'longitude' => $model['longitude'],
        ];
        $this->coreRegistry->register('maplist_location_location', $location);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()
            ->prepend(
                $model->getId() ? __('Edit Location', $model->getData('name')) : __('New Location')
            );

        return $resultPage;
    }
}
