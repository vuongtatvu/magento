<?php

namespace Magenest\Movie\Block\Adminhtml\System\Config;
use Magento\Framework\View\Element\Template;

class RowMovie extends \Magento\Config\Block\System\Config\Form\Field
{
    protected $movieFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magenest\Movie\Model\ResourceModel\MagenestMovie\CollectionFactory $magenestMovieFactory,
        array $data = [])
    {
        $this->movieFactory = $magenestMovieFactory;
        parent::__construct($context, $data);
    }

    public function getMoviesAmount() {
        $collection = $this->movieFactory->create();
        return count($collection);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return  $this->getMoviesAmount()  ;
    }
}