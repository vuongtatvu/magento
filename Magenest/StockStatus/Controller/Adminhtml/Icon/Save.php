<?php
namespace Magenest\StockStatus\Controller\Adminhtml\Icon;

class Save extends \Magento\Framework\App\Action\Action {
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magenest\StockStatus\Model\ManagerIconFactory
     */
    protected $managerIconFactory;
    /**
     * @var \Magenest\StockStatus\Helper\Image
     */
    protected $imageHelper;

    /**
     * Save constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magenest\StockStatus\Model\ManagerIconFactory $managerIconFactory
     * @param \Magenest\StockStatus\Helper\Image $imageHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\StockStatus\Model\ManagerIconFactory $managerIconFactory,
        \Magenest\StockStatus\Helper\Image $imageHelper
    ){
        $this->imageHelper = $imageHelper;
        $this->managerIconFactory = $managerIconFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute(){
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $managerIconCollection = $this->managerIconFactory->create();
        try{
            $request = $this->getRequest()->getParams();
            if(!empty($request['magenest_icon_delete'])){
                $params = $request['magenest_icon_delete'];
                foreach ($params as $optionId => $value){
                    $record = $managerIconCollection->load($optionId);
                    $record->delete();
                }
                $this->messageManager->addSuccessMessage(__('You successfully deleted the icon'));
                return $resultRedirect->setPath('*/icon/index');
            }
            if($_FILES['magenest_icon']){
                $files = $_FILES['magenest_icon'];
                foreach ($files['name'] as $optionId => $file){
                    if(!empty($file)){
                        $path = $this->imageHelper->uploadImage($optionId,$file);
                        $data = array(
                            'stockstatus_id' => $optionId,
                            'path_image' =>$path
                        );
                        $managerIconCollection->setData($data);
                        $managerIconCollection->save();
                    }
                }
            }
            $this->messageManager->addSuccessMessage(__('You have updated your data'));
            return $resultRedirect->setPath('*/icon/index');
        }catch (\Exception $e){
            $this->messageManager->addExceptionMessage($e,__('We can\'t submit your request, Please try again.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            return $resultRedirect->setPath('*/icon/index');
        }
    }

    public function _isAllowed(){
        return $this->_authorization->isAllowed('Magenest_StockStatus::magenest');
    }
}