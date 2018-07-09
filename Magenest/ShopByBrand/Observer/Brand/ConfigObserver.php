<?php
/**
 * Created by PhpStorm.
 * User: canhnd
 * Date: 18/02/2017
 * Time: 10:58
 */
namespace Magenest\ShopByBrand\Observer\Brand;

use Magenest\ShopByBrand\Helper\Brand;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use function print_r;
use Psr\Log\LoggerInterface as Logger;
use const true;

/**
 * Class ConfigObserver
 *
 * @package Magenest\ShopByBrand\Observer\Brand
 */
class ConfigObserver implements ObserverInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scoreConfig;

    /**
     * @var \Magento\Store\Model\Store
     */
    protected $_store;

    /**
     * @var \Magento\UrlRewrite\Model\UrlRewriteFactory
     */
    protected $_urlRewrite;

    /**
     * @var Brand
     */
    protected $_brandHelper;
    protected $_resourceBrand;
    /**
     * ConfigObserver constructor.
     *
     * @param Logger                                             $logger
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $configInterface
     * @param \Magento\Store\Model\StoreFactory                  $store
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory        $urlRewrite
     */
    public function __construct(
        Logger $logger,
        Brand $brandHelper,
        \Magenest\ShopByBrand\Model\ResourceModel\Brand $resourceBrand,
        \Magento\Framework\App\Config\ScopeConfigInterface $configInterface,
        \Magento\Store\Model\StoreFactory $store,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewrite
    ) {
        $this->logger           = $logger;
        $this->_scoreConfig     = $configInterface;
        $this->_store           = $store;
        $this->_urlRewrite      = $urlRewrite;
        $this->_brandHelper     = $brandHelper;
        $this->_resourceBrand   = $resourceBrand;
    }

    /**
     * Url Rewrite
     *
     * @param EventObserver $observer
     */
    public function execute(EventObserver $observer)
    {
        $allBrand=$this->getAllBrand();
        $collection = $this->_scoreConfig->getValue('shopbybrand/page/url');
        $stores = $this->_store->create()->getCollection()->getData();
        $this->logger->debug(print_r($stores, true));
        $this->logger->debug($collection);
        if ($collection) {
            $this->logger->debug('asdasdasd');
            $params = [
                'entity_type'   => 'brand',
                'entity_id'     => '0',
                'request_path'  => $collection,
                'target_path'   => 'shopbybrand/brand/index/'
            ];

            $datas = $this->urlRewrite();

            foreach ($allBrand as $brand) {
                $this->addUrlRewrite($brand, $brand['brand_id']);
            }
            if ($datas) {
                foreach ($datas as $data) {
                    $model = $this->_urlRewrite->create();
                    $model->load($data['url_rewrite_id']);
                    $model->delete();
                }
            }
            $model = $this->_urlRewrite->create();
            $this->logger->debug(print_r($stores, true));
            foreach ($stores as $item) {
                if ($item['code']!='admin') {
                    $params['store_id'] = $item['store_id'];
                    $model->setData($params);
                    $model->save();
                }
            }
        }
    }

    public function urlRewrite()
    {
        $urls = $this->_urlRewrite->create()
            ->getCollection()
            ->addFieldToFilter('target_path', 'shopbybrand/brand/index/')
            ->getData();
        return $urls;
    }
    public function getAllBrand()
    {
        return $this->_brandHelper->getAllBrand();
    }
    /**
     * @param $data
     * @param $id
     */
    public function addUrlRewrite($data, $id)
    {
        $arrayBrandStore=$this->_resourceBrand->lookupStoreIds($id);
        $model = $this->_urlRewrite->create();
        $rewritePage = $this->_scoreConfig->getValue('shopbybrand/page/url');
        if (!empty($data['brand_id'])) {
            $collection = $model->getCollection()
                ->addFieldToFilter('entity_type', 'brand')
                ->addFieldToFilter('entity_id', $id);
            foreach ($collection as $model) {
                $model->delete();
            }
            $page = [];
            $page['url_rewrite_id'] = null;
            $page['entity_type'] = 'brand';
            $page['entity_id'] = $id;
            $page['request_path'] = $rewritePage . '/' . $data['url_key'];
            $page['target_path'] = 'shopbybrand/brand/view/brand_id/' . $id;
            $model = $this->_urlRewrite->create();
            foreach ($arrayBrandStore as $id) {
                $page['store_id'] = $id;
                $model->setData($page);
                $model->save();
            }
        }
    }
}
