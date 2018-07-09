<?php

namespace Magenest\Blog\Block\Adminhtml\Category\Edit\Tab;

/**
 * Class Js
 * @package Magenest\Blog\Block\Adminhtml\Category\Edit\Tab
 */
class Js extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var string
     */
    protected $_template = 'category/js.phtml';

    /**
     * @var \Magenest\Blog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Js constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magenest\Blog\Model\CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magenest\Blog\Model\CategoryFactory $categoryFactory,
        array $data = []
    ) {
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * disabled radiobutton
     *
     * @return array
     */
    public function getDisabledButton(){
        $array = [];
        $idCategory = $this->getRequest()->getParam("id");
        if($idCategory){
            array_push($array,$idCategory);
            $categoryColletion = $this->_categoryFactory->create()->getCollection();
            foreach ($categoryColletion as $cate){
                $check = explode("/",$cate->getPath());
                if(array_search($idCategory,$check))
                    array_push($array,$cate->getId());
            }
        }else{
            return [];
        }
        return $array;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {

        return __(' ');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {

        return __(' ');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {

        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {

        return false;
    }

    /**
     * @param $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {

        return $this->_authorization->isAllowed($resourceId);
    }
}
