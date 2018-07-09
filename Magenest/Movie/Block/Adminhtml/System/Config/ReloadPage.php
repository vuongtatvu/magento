<?php
namespace Magenest\Movie\Block\Adminhtml\System\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
class ReloadPage extends \Magento\Config\Block\System\Config\Form\Field{
    
    const BUTTON_TEMPLATE = 'system/config/reloadpage.phtml';

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()){
            $this->setTemplate(static::BUTTON_TEMPLATE);
        }
        RETURN $this;
    }
    
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->addData(
            [
                'id'=> 'addbutton_button',
                'button_label'=> __('Reload'),
                'onclick'=>'javascript:check(); '
            ]
        );
        return $this->_toHtml();
    }
}