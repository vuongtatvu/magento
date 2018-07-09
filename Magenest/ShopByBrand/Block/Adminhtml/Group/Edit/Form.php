<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_ShopByBrand extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_ShopByBrand
 * @author   CanhND <duccanhdhbkhn@gmail.com>
 */
namespace Magenest\ShopByBrand\Block\Adminhtml\Group\Edit;

use Magento\Backend\Block\Widget\Form\Generic as FormGeneric;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magenest\ShopByBrand\Model\Status;
use Magento\Store\Model\System\Store as SystemStore;

/**
 * Class Main
 *
 * @package Magenest\ShopByBrand\Block\Adminhtml\Brand\Edit\Tab
 */
class Form extends FormGeneric
{
    /**
     * @var Status
     */
    protected $_status;
    /**
     * @var SystemStore
     */
    protected $_systemStore;


    /**
     * Main constructor.
     *
     * @param Context                  $context
     * @param Registry                 $registry
     * @param FormFactory              $formFactory
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder    $searchCriteriaBuilder
     * @param DataObject               $objectConverter
     * @param Status                   $status
     * @param SystemStore              $systemStore
     * @param array                    $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $objectConverter,
        Status $status,
        SystemStore $systemStore,
        array $data = []
    ) {
        $this->_systemStore           = $systemStore;
        $this->_groupRepository       = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_objectConverter       = $objectConverter;
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }


    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('groups');
        $data  = $model->getData();

        /*
            * @var \Magento\Framework\Data\Form $form
         */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Group Information')]);

        if ($model->getId()) {
            $data['group_name'] = $data['name'];
            $fieldset->addField(
                'group_id',
                'hidden',
                ['name' => 'group_id']
            );
        }

        $fieldset->addField(
            'name',
            'text',
            [
             'name'     => 'name',
             'label'    => __('Category Name for Brand Group'),
             'title'    => __('Category Name for Brand Group'),
             'required' => true,
            ]
        );
        $fieldset->addField(
            'url_key',
            'text',
            [
                'name'     => 'url_key',
                'label'    => __('URL Key'),
                'title'    => __('URL Key'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
             'label'    => __('Status'),
             'title'    => __('Status'),
             'name'     => 'status',
             'required' => false,
             'options'  => $this->_status->getOptionArray(),
            ]
        );
        //        $fieldset->addField(
        //            'show_in_sidebar',
        //            'select',
        //            [
        //                'label'    => __('Show in SideBar'),
        //                'title'    => __('Show in SideBar'),
        //                'name'     => 'show_in_sidebar',
        //                'required' => false,
        //                'options'  => $this->_status->getOptionArray(),
        //            ]
        //        );

        //        $form->setValues($data);
        //        $this->setForm($form);
        $form->setUseContainer(true);
        $form->setValues($model->getData());
        $this->setForm($form);
        $form->setAction($this->getUrl('shopbybrand/group/save'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');
        return parent::_prepareForm();
    }

    /**
     * Check permission for passed action
     *
     * @param  string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
