<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_ShopByBrand extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_ShopByBrand
 * @author   ChienLV <chienbigstar@gmail.com>
 */
namespace Magenest\ShopByBrand\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magenest\ShopByBrand\Model\BrandFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\App\Filesystem\DirectoryList;
use Psr\Log\LoggerInterface;

/**
 * Class Brand
 *
 * @package Magenest\ShopByBrand\Controller\Adminhtml
 */
abstract class Brand extends Action
{

    const FIELD_NAME_SOURCE_FILE = 'import_file';
    
    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\UrlRewrite\Model\UrlRewrite
     */
    protected $_urlRewrite;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scoreConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManagement;
    
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;
    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var BrandFactory
     */
    protected $_brandFactory;

    /**
     * @var \Magento\ImportExport\Model\Import
     */
    protected $importBean;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory  
     */
    protected $uploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;
    
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var Filter
     */
    protected $_filter;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $varDirectory;
    
    /**
     * Brand constructor.
     *
     * @param Context                                               $context
     * @param \DOMDocument                                          $document
     * @param Registry                                              $coreRegistry
     * @param PageFactory                                           $resultPageFactory
     * @param BrandFactory                                          $brandFactory
     * @param \Magento\Framework\Controller\Result\RawFactory       $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory                 $layoutFactory
     * @param Filter                                                $filter
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory           $urlRewrite
     * @param \Magento\Backend\Model\View\Result\ForwardFactory     $resultForwardFactory
     * @param \Magento\Catalog\Model\ProductFactory                 $productFactory
     * @param \Magento\Catalog\Model\CategoryFactory                $categoryFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface    $configInterface
     * @param \Magento\Store\Model\StoreManagerInterface            $storeManagement
     * @param \Magento\Framework\App\Cache\TypeListInterface        $cacheTypeList
     * @param \Magento\Framework\App\Cache\Frontend\Pool            $cacheFrontendPool
     * @param \Magento\ImportExport\Model\Import                    $importModel
     * @param \Magento\ImportExport\Model\Import\Source\CsvFactory  $sourceCsvFactory
     * @param \Magento\ImportExport\Model\Export\Adapter\CsvFactory $outputCsvFactory
     * @param \Magento\MediaStorage\Model\File\UploaderFactory      $uploaderFactory
     * @param \Magento\Framework\Filesystem                         $filesystem
     * @param LoggerInterface                                       $logger
     */
    public function __construct(
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
        $this->_storeManagement     = $storeManagement;
        $this->_categoryFactory     = $categoryFactory;
        $this->_productFactory      = $productFactory;
        $this->_context             = $context;
        $this->_coreRegistry        = $coreRegistry;
        $this->_resultPageFactory   = $resultPageFactory;
        $this->resultRawFactory     = $resultRawFactory;
        $this->layoutFactory        = $layoutFactory;
        $this->_brandFactory        = $brandFactory;
        $this->_filter              = $filter;
        $this->_urlRewrite          = $urlRewrite;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_scoreConfig         = $configInterface;
        $this->_cacheTypeList       = $cacheTypeList;
        $this->_cacheFrontendPool   = $cacheFrontendPool;
        $this->importBean           = $importModel;
        $this->sourceCsvFactory     = $sourceCsvFactory;
        $this->uploaderFactory      = $uploaderFactory;
        $this->outputCsvFactory     = $outputCsvFactory;
        $this->filesystem           = $filesystem;
        $this->_logger              = $logger;
        $this->varDirectory         =  $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_ShopByBrand::brand');
    }
}
