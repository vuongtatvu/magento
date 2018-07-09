<?php
/**
 * Created by PhpStorm.
 * User: thien
 * Date: 21/09/2017
 * Time: 09:14
 */

namespace Magenest\ShopByBrand\Controller\Adminhtml\Brand;

use Magenest\ShopByBrand\Controller\Adminhtml\Brand;
use Magento\Backend\App\Action\Context;
use Magento\Framework\DomDocument\DomDocumentFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magenest\ShopByBrand\Model\BrandFactory;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

/**
 * Class MassExport
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml\Brand
 */
class MassExport extends Brand
{

    public function __construct(
        DomDocumentFactory $document,
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        BrandFactory $brandFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        Filter $filter,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewrite,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $configInterface,
        \Magento\Store\Model\StoreManagerInterface $storeManagement,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\ImportExport\Model\Import $importModel,
        \Magento\ImportExport\Model\Import\Source\CsvFactory $sourceCsvFactory,
        \Magento\ImportExport\Model\Export\Adapter\CsvFactory $outputCsvFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        LoggerInterface $logger
    ) {
    
        $this->domDocument=$document->create();
        parent::__construct($context, $coreRegistry, $resultPageFactory, $brandFactory, $resultRawFactory, $layoutFactory, $filter, $urlRewrite, $resultForwardFactory, $productFactory, $categoryFactory, $configInterface, $storeManagement, $cacheTypeList, $cacheFrontendPool, $importModel, $sourceCsvFactory, $outputCsvFactory, $uploaderFactory, $filesystem, $logger);
    }

    public function execute()
    {
        $brandCollection = $this->_objectManager
            ->create('Magenest\ShopByBrand\Model\ResourceModel\Brand\Collection');
        $collections     = $this->_filter->getCollection($brandCollection);
        $status          = (int) $this->getRequest()->getParam('status');
        if ($status) {
            $this->exportXML($collections);
        } else {
            $this->exportCSV($collections);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    private function exportCSV($data)
    {
        $result=array();
        foreach ($data as $brand) {
            $lisProduct=$this->getListProduct($brand->getBrandId());
            $dataSample = [
                    'Brand Name'        => $brand->getName(),
                    'Product'           => $lisProduct,
                    'Url'               => $brand->getUrlKey(),
                    'Slogan'            => $brand->getSlogan(),
                    'Description'       => $brand->getDescription(),
                    'Page Title'        => $brand->getPageTitle(),
                    'Meta Keywords'     => $brand->getMetaKeywords(),
                    'Meta Description'  => $brand->getMeTaDesciption(),
                    'Groups'            => $brand->getGroups(),
                    'Logo'              => $brand->getLogo(),
                    'Banner'            => $brand->getBanner(),
                    'Sort Order'        => $brand->getSortOrder(),
                    'Summary'           => $brand->getSummary(),
                    'Featured Brand'    => $brand->getFeatured(),
                    'Status'            => $brand->getStatus(),
                    'Show In Sidebar'   => $brand->getStatus(),
                    'Store'             => $this->getStore($brand->getBrandId())
                ];
            $result[]=$dataSample;
        }
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
        $outputFile = "Export.csv";
        $handle = fopen($outputFile, 'w');
        fputcsv($handle, $heading);
        foreach ($result as $item) {
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
            throw $exception;
        }
    }

    /**
     * @param $collection
     * @throws \Exception
     */
    private function exportXML($collection)
    {
        $xml=$this->domDocument;
        $this->domDocument=new $xml('1.0', 'UTF-8');
        $workbook = $this->domDocument->createElement("Workbook");
        $workbook = $this->domDocument->appendChild($workbook);

        $worksheet = $this->domDocument->createElement("Worksheet");
        $worksheet = $workbook->appendChild($worksheet);

        $table = $this->domDocument->createElement("Table");
        $table = $worksheet->appendChild($table);

        $head = $this->domDocument->createElement("Head");
        $head = $table->appendChild($head);
        $arrayHead=['Brand Name','Product','Url','Slogan','Description','Page Title','Meta Keywords','Meta Description','Groups','Logo','Banner', 'Sort Order','Summary','Featured Brand','Status','Store'];


        foreach ($arrayHead as $item) {
            $data = $this->domDocument->createElement("Data", $item);
            $cell = $this->domDocument->createElement('Cell');
            $x=$head->appendChild($cell);
            $x->appendChild($data);
        }
        $collection=$collection->getData();
        $arrayHead=['name','Product','url_key','slogan','description','page_title','meta_keywords','meta_description','groups','logo' ,'banner', 'sort_order','summary','featured','status','store'];
        foreach ($collection as $dataBrand) {
            $dataBrand['Product']=$this->getListProduct($dataBrand['brand_id']);
            $dataBrand['store']=$this->getStore($dataBrand['brand_id']);
            $brand=$this->domDocument->createElement('Brand');
            foreach ($arrayHead as $head) {
                $data = $this->domDocument->createElement("Data", $dataBrand[$head]);
                $cell = $this->domDocument->createElement('Cell');
                $x=$brand->appendChild($cell);
                $x->appendChild($data);
            }
            $table->appendChild($brand);
        }

        $this->domDocument->FormatOutput = true;
        $string_value = $this->domDocument->saveXML();
        $this->domDocument->save("Export.xml");
        $outputFile = "Export.xml";
        try {
            $this->downloadCsv($outputFile);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $idBrand
     * @return string
     */
    public function getStore($idBrand)
    {
        $collection=$this->_brandFactory->create()->getCollection()
            ->addBrandStore($idBrand)
            ->getData();
        $data       = array();
        foreach ($collection as $row) {
            $data[] = $row['store_id'];
        }
        return implode(",", $data);
    }

    /**
     * @param $idBrand
     * @return string
     */
    public function getListProduct($idBrand)
    {
        $collection = $this->_brandFactory->create()->getCollection()
            ->addBrandIdToFilter($idBrand)
            ->getData();
        $data       = array();
        foreach ($collection as $row) {
            $data[] = $row['product_id'];
        }
        return implode(",", $data);
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
}
