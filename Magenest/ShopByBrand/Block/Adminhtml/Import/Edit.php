<?php
/**
 * @TODO: 20102015
 */
namespace Magenest\ShopByBrand\Block\Adminhtml\Import;

use \Magento\Backend\Block\Widget\Form\Container;
use \Magento\Backend\Block\Widget\Context;

class Edit extends Container
{
    /**
     * @var null
     */
    protected $_coreRegistry = null;

    public function __construct(
        Context $context,
        array $data = []
    ) {
    
        parent::__construct($context, $data);
    }


    public function _construct()
    {
        parent::_construct();

        $this->buttonList->remove('back');
        $this->buttonList->remove('reset');
        $this->buttonList->update('save', 'label', __('Upload'));
        $this->buttonList->update('save', 'id', 'upload_button');
        $this->_objectId = 'id';
        $this->_blockGroup = 'Magenest_ShopByBrand';
        $this->_controller = 'adminhtml_import';
    }
}
