<?php

namespace Magenest\Knockout\Block;
class ViewAbstract extends \Magento\Framework\View\Element\Template

{

    /**

     * @var \Magento\Checkout\Model\CompositeConfigProvider

     */

    protected $configProvider;
    /**

     * @var array

     */

    protected $_layoutProcessors;

    /**

     * Lists constructor.

     * @param \Magento\Framework\View\Element\Template\Context $context

     * @param array $layoutProcessors

     * @param array $data

     */

    public function __construct(

        \Magento\Framework\View\Element\Template\Context $context,

        \Magento\Checkout\Model\CompositeConfigProvider $configProvider,

        array $layoutProcessors = [],

        array $data = []

    ) {

        parent::__construct($context, $data);

        $this->configProvider = $configProvider;

        $this->_layoutProcessors = $layoutProcessors;

    }
    /**

     * @return string

     */

    public function getJsLayout()

    {

        foreach ($this->_layoutProcessors as $processor) {

            $this->jsLayout = $processor->process($this->jsLayout);

        }

        return parent::getJsLayout();

    }

}