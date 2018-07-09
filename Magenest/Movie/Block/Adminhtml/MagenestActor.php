<?php
namespace	Magenest\Movie\Block\Adminhtml;
class	MagenestActor	extends	\Magento\Backend\Block\Widget\Grid\Container
{
    protected	function	_construct()
    {
        $this->_blockGroup	=	'Magenest_Movie';
        $this->_controller	=	'adminhtml_magenestactor';
        parent::_construct();
    }
}