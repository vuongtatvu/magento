<?php
namespace Magenest\Movie\Controller\Adminhtml\MagenestMovie;

use    Magento\Backend\App\Action\Context;
use    Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Backend\App\Action
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
        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Magenest_Movie::magenestmovie');
        $resultPage->addBreadcrumb(__('Movie'), __('Movie'));
        $resultPage->addBreadcrumb(__('Manage Movie'), __('Manage Movie'));
        $resultPage->getConfig()->getTitle()->prepend(__('Create Movie'));


        if ($_POST) {

            $movie_id = $this->getRequest()->getParam('movie_id');
            $name = $this->getRequest()->getParam('name');
            $description = $this->getRequest()->getParam('description');
            $rating = $this->getRequest()->getParam('rating');
            $director_id = $this->getRequest()->getParam('director_id');



            $movieCollection = $this->_objectManager->create('Magenest\Movie\Model\MagenestMovie')->load($movie_id);
            
            $movieCollection->setName($name);
            $movieCollection->setDescription($description);
            $movieCollection->setRating($rating);
            $movieCollection->setDirectorId($director_id);
            $movieCollection->save();
            $this->messageManager->addSuccess(__('You edit movie success.'));


//            $customer->setFirstName('Magenest');
//            $customerRepositoryInterface->save($customer);
//

            $this->_redirect('movie/magenestmovie');


        }
        return $resultPage;

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Movie::magenestmovie');
    }
}