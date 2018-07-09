<?php
/**
 * Created by PhpStorm.
 * User: thien
 * Date: 19/09/2017
 * Time: 08:51
 */

namespace Magenest\ShopByBrand\Controller\Adminhtml\Download;

use Magento\Framework\App\Action\Context;

/**
 * Class Sample
 *
 * @package Magenest\Pin\Controller\Adminhtml\Upload
 */
class Widget extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {

        $dataSample = [
            1 => [
                'Number of Brand'   => '4',
                'Sort Brand By'     => 'asc',
                'Display as Slider' => '1'
            ]
        ];
        $heading = [
            __('Number of Brand'),
            __('Sort Brand By'),
            __('Display as Slider')
        ];
        $outputFile = "SampleFile.csv";
        $handle = fopen($outputFile, 'w');
        fputcsv($handle, $heading);
        foreach ($dataSample as $item) {
            $row = [
                $item['Number of Brand'],
                $item['Sort Brand By'],
                $item['Display as Slider']
            ];
            fputcsv($handle, $row);
        }
        try {
            $this->downloadCsv($outputFile);
        } catch (\Exception $exception) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage("Something went wrong while download the csv sample file.");
            return $resultRedirect->setPath('*/*/');
        }
    }

    /**
     * @param $file
     */
    public function downloadCsv($file)
    {
        if (file_exists($file)) {
            //set appropriate headers
            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }
}
