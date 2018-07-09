<?php
namespace Magenest\Movie\Controller\Adminhtml\MagenestMovie;

use    Magento\Backend\App\Action\Context;
use    Magento\Framework\View\Result\PageFactory;

class Delete extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {

        $movie_id = $this->getRequest()->getParam('movie_id');

        $movieCollection = $this->_objectManager->create('Magenest\Movie\Model\MagenestMovie')->load($movie_id);

        $movieCollection->delete();

        $this->messageManager->addSuccess(__('You delete movie success.'));

        $this->_redirect('movie/magenestmovie');

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Movie::magenestmovie');
    }
}