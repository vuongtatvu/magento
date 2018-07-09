<?php
namespace Magenest\StockStatus\Plugin;

class FinalPricePlugin {
    /**
     * @param \Magento\Catalog\Pricing\Render\FinalPriceBox $subject
     * @param $template
     */
    public function beforeSetTemplate(\Magento\Catalog\Pricing\Render\FinalPriceBox $subject, $template){
//        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        $scopeConfig = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
//        $display_CSS_onLisProduct = $scopeConfig->getValue('stockstore/hellopage/display_stock_status_on_product_list_page', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
//        $display_CSS_onShoppingCart = $scopeConfig->getValue('stockstore/hellopage/display_stockstatus_on_shoppingcart', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
//        $ruleQty = $scopeConfig->getValue('stockstore/hellopage/activate_rules', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
//        $defaultCSS = $scopeConfig->getValue('stockstore/hellopage/hide_default_stockstatus', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
//        if($display_CSS_onLisProduct == 1){
        if($template == 'Magento_Catalog::product/price/final_price.phtml'){
            return ['Magenest_StockStatus::product/price/final_price.phtml'];
        }else{
            return [$template];
        }
//        }else{
//            return [$template];
//        }
    }
}