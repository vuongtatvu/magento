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
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * Index constructor.
     *
     * @param Context                                                        $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
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
                'Brand Name'        => 'Brand1',
                'Product'           => '1',
                'Url'               => 'brand1',
                'Slogan'            => 'slogan',
                'Description'       => '<p><span>Brand description</span></p>',
                'Page Title'        => 'Page Title',
                'Meta Keywords'     => 'Meta keywords',
                'Meta Description'  => 'Meta description',
                'Groups'            => '1',
                'Logo'              => '/logo/photo.jpg',
                'Banner'            => '/logo/photo.jpg',
                'Sort Order'        => '1',
                'Summary'           => 'Summary',
                'Featured Brand'    => '1',
                'Status'            => '1',
                'Store'             => '0'
            ],
            2 => [
                'Brand Name'        =>  'Brand2',
                'Product'           =>  '1',
                'Url'               =>  'brand2',
                'Slogan'            =>  'slogan',
                'Description'       =>  '<p><span>Brand description</span></p>',
                'Page Title'        =>  'Page Title',
                'Meta Keywords'     =>  'Meta keywords',
                'Meta Description'  =>  'Meta description',
                'Groups'            =>  '1',
                'Logo'              =>  '/logo/photo.jpg',
                'Banner'            =>  '/logo/photo.jpg',
                'Sort Order'        =>  '1',
                'Summary'           =>  'Summary',
                'Featured Brand'    =>  '1',
                'Status'            =>  '1',
                'Store'             =>  '0'
            ]
        ];

        $heading = [
            __('Brand Name'),
            __('Product'),
            __('Url'),
            __('Slogan'),
            __('Description'),
            __('Page Title'),
            __('Meta Keywords'),
            __('Meta Description'),
            __('Groups'),
            __('Logo'),
            __('Banner'),
            __('Sort Order'),
            __('Summary'),
            __('Featured Brand'),
            __('Status'),
            __('Store'),

        ];
        $outputFile = "SampleFile.csv";
        $handle = fopen($outputFile, 'w');
        fputcsv($handle, $heading);
        foreach ($dataSample as $item) {
            $row = [
                $item['Brand Name'],
                $item['Product'],
                $item['Url'],
                $item['Slogan'],
                $item['Description'],
                $item['Page Title'],
                $item['Meta Keywords'],
                $item['Meta Description'],
                $item['Groups'],
                $item['Logo'],
                $item['Banner'],
                $item['Sort Order'],
                $item['Summary'],
                $item['Featured Brand'],
                $item['Status'],
                $item['Store'],

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
            header('Content-Disposition: attachment; filename='.basename($file));
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
