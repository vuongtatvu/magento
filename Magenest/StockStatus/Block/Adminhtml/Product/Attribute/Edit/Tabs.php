<?php
namespace Magenest\StockStatus\Block\Adminhtml\Product\Attribute\Edit;

class Tabs extends \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tabs {
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        array $data = []
    )
    {
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }
    protected function _beforeToHtml(){
        parent::_beforeToHtml();
//        $this->addTab(
//            'manager_qty_rule',
//            [
//                'label' => __('Manager Quantity Rule'),
//                'title' => __('Manager Quantity Rule'),
//                'content' => $this->getChildChildHtml('ranges')
//            ]
//        );
        return parent::_beforeToHtml();
//        return \Magento\Backend\Block\Widget\Tabs::_beforeToHtml();
    }
}