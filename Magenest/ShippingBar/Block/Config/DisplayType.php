<?php


namespace Magenest\ShippingBar\Block\Config;

class DisplayType extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context, array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = $element->getElementHtml();


        $html .= '<script type="text/javascript">
            require([
                "jquery"                
                ],
                function ($) {
               
                $(document).ready(function () {
                    var curr_value =  $("#' . $element->getHtmlId() . '").val(); 
                    
                    $("#' . $element->getHtmlId() . '").change(function(){
                    var value =  $("#' . $element->getHtmlId() . '").val();                   
                    $("#status").attr("class", "").addClass("wow " +value+ " animated").one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function(){
                          $(this).attr("class", "");
                        });
                   
                    });
                    
                });
            });
            </script>';
        return $html;
    }

}