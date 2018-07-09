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
namespace Magenest\ShopByBrand\Block\Adminhtml\Brand\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic as FormGeneric;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject;
use Magenest\ShopByBrand\Model\Status;
use Magento\Store\Model\System\Store as SystemStore;
use Magenest\ShopByBrand\Model\ListGroup;

/**
 * Class Main
 *
 * @package Magenest\ShopByBrand\Block\Adminhtml\Brand\Edit\Tab
 */
class Main extends FormGeneric
{
    /**
     * @var Status
     */
    protected $_status;
    /**
     * @var SystemStore
     */
    protected $_systemStore;


    protected $_listGroup;

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
        ListGroup $listGroup,
        array $data = []
    ) {
        $this->_listGroup = $listGroup;
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
        $model = $this->_coreRegistry->registry('shopbybrand');
        $data  = $model->getData();
        /*
            * @var \Magento\Framework\Data\Form $form
         */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Brand Information')]);

        if ($model->getId()) {
            $data['brand_name'] = $data['name'];
            $fieldset->addField(
                'brand_id',
                'hidden',
                ['name' => 'brand_id']
            );
        }

        $fieldset->addField(
            'brand_name',
            'text',
            [
             'name'     => 'brand_name',
             'label'    => __('Title'),
             'title'    => __('Title'),
                'validation' => ['validate-brand-name' => true],
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
        $fieldset->addField(
            'featured',
            'select',
            [
                'label'    => __('Featured'),
                'title'    => __('Featured'),
                'name'     => 'featured',
                'required' => false,
                'options'  => $this->_status->getOptionArray(),
            ]
        );
        $fieldset->addField(
            'store_ids',
            'multiselect',
            [
             'name'     => 'store_ids[]',
             'label'    => __('Store Views'),
             'title'    => __('Store Views'),
             'required' => true,
             'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
            ]
        );
        $fieldset->addField(
            'groups',
            'multiselect',
            [
                'name'     => 'groups[]',
                'label'    => __('Groups'),
                'title'    => __('Groups'),
                'required' => false,
                'values'   => $this->_listGroup->getAllOptions(true),
            ]
        );
        $fieldset->addField(
            'show_in_sidebar',
            'select',
            [
                'name'     => 'show_in_sidebar',
                'label'    => __('Show in sidebar'),
                'title'    => __('Show in sidebar'),
                'required' => false,
                'values'   => $this->_status->getOptionArray(),
            ]
        );
        $fieldset->addField(
            'sort_order',
            'text',
            [
             'name'     => 'sort_order',
             'label'    => __('Sort Order'),
             'title'    => __('Sort Order'),
             'required' => false,
            ]
        );

        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
