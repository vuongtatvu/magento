<?php
/**
 * Created by PhpStorm.
 * User: thien
 * Date: 15/09/2017
 * Time: 08:22
 */

namespace Magenest\ShopByBrand\Block\Adminhtml\Import\Edit;

use \Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param \Magento\Store\Model\System\Store       $systemStore
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        //        /** @var \Magenest\Thien\Model\NameModel $model */
        //        $model = $this->_coreRegistry->registry('model');

        /**
 * @var \Magento\Framework\Data\Form $form 
*/
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('shopbybrand/import/save'),//@TODO: Them action
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ],
            ]
        );

        $fieldsets['upload'] = $form->addFieldset('upload_file_fieldset', ['legend' => __('Import Brands')]);
        $fieldsets['upload']->addField(
            \Magento\ImportExport\Model\Import::FIELD_NAME_SOURCE_FILE,
            'file',
            [
                'name' => \Magento\ImportExport\Model\Import::FIELD_NAME_SOURCE_FILE,
                'label' => __('Import csv or xml file'),
                'title' => __('Select File to Import'),
                'required' => true,
                'class' => 'input-file',
            ]
        );
        $fieldsets['upload']->addField(
            'note',
            'note',
            [
                'after_element_html' => $this->getDownloadSampleFileHtml(),
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
    /**
     * Get download sample file html
     *
     * @return string
     */
    protected function getDownloadSampleFileHtml()
    {
        $linkDownloadSample = $this->getDownloadSampleFile();

        $html = "<div id='sample-file-span' class='no-display' style='display: inline;margin-left: calc( (100%) * .5);'><a id='sample-file-link' href='{$linkDownloadSample}'>"
            . __('Download Sample File')
            . "</a></div>";
        return $html;
    }

    public function getDownloadSampleFile()
    {
        return $this->getUrl('shopbybrand/download/index');
    }
}
