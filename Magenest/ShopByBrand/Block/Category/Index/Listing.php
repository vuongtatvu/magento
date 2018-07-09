<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 27/10/2016
 * Time: 14:50
 */
namespace Magenest\ShopByBrand\Block\Category\Index;

use Magento\Framework\UrlInterface;
use Magenest\ShopByBrand\Model\Config\Router;

/**
 * Class Listing
 *
 * @package Magenest\ShopByBrand\Block\Category\Index
 */
class Listing extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magenest\ShopByBrand\Model\Brand
     */
    protected $brand;

    /**
     * @var \Magenest\ShopByBrand\Model\Config\Router
     */
    protected $router;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    protected $_brandFactory;

    protected $_listCategory=[];
    /**
     * Listing constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magenest\ShopByBrand\Model\Brand                $brand
     * @param Router                                           $router
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magenest\ShopByBrand\Model\Brand $brand,
        \Magenest\ShopByBrand\Model\BrandFactory $brandFactory,
        \Magenest\ShopByBrand\Model\Config\Router $router,
        \Magento\Catalog\Model\CategoryFactory $categoryCollectionFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        array $data = []
    ) {
        $this->_categoryFactory = $categoryCollectionFactory;
        $this->_categoryHelper = $categoryHelper;
        $this->brand         = $brand;
        $this->router        = $router;
        $this->_brandFactory=$brandFactory;
        parent::__construct($context, $data);
        $this->prepareTemplate();
    }

    /**
     * Get Category Id
     *
     * @return mixed
     */
    public function getCategory()
    {
        return $this->getRequest()->getParam('category');
    }

    /**
     * Get Brand Filter By Category
     *
     * @return array
     */
    public function filterCategory()
    {

        $dataFilter = [];

        $categoryId = $this->getCategory();
        $allBrand = $this->getAllBrand();

        foreach ($allBrand as $brand) {
            $check = false;
            if (json_decode($brand['categories'])) {
                $brandCategory = $this->convertArray(json_decode($brand['categories'], true));
                for ($i = 0; $i < count($brandCategory); $i++) {
                    if ($brandCategory[$i] == $categoryId) {
                        $check = true;
                    }
                }
                if ($check) {
                    $dataFilter[] = $brand;
                }
            }
        }

        return $dataFilter;
    }

    /**
     * @return array
     */
    public function getAllBrand()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        return $this->_brandFactory->create()->getCollection()
            ->setOrder('sort_order', 'ASC')
            ->addStoreToFilter($storeId)
            ->setOrder('name', 'ASC')
            ->addFieldToFilter('status', 1)
            ->getData();
    }

    /**
     * params $data
     *
     * return first word brands
     */
    public function getBrandsStyle($data)
    {
        return strtoupper(substr($data, 0, 1));
    }

    /**
     * @param $key
     * @return bool
     */
    public function checkFirstBrand($key)
    {
        $datas = $this->getAllBrand();
        foreach ($datas as $data) {
            $first = $this->getBrandsStyle($data['name']);
            if ($first == $key) {
                return true;
            }
        }
        return false;
    }


    /**
     * @return array
     */
    public function getAllBrandCategory()
    {
        $allCategory = [];
        $storeId = $this->_storeManager->getStore()->getId();
        $collections = $this->_brandFactory->create()->getCollection()
            ->addFieldToFilter('status', 1)
            ->addStoreToFilter($storeId)
            ->getData();
        foreach ($collections as $collection) {
            if (json_decode($collection['categories'])) {
                $allCategory = array_merge(json_decode($collection['categories'], true), $allCategory);
            }
        }

        $mergeCategory = array_unique($allCategory);

        return $mergeCategory;
    }


    /**
     * @param $arr
     * @return array
     */
    public function convertArray($arr)
    {
        $bestArr = array_values($arr);
        return $bestArr;
    }


    /**
     * @param $categoryId
     * @return mixed
     */
    public function getDataCategory($categoryId)
    {
        return $this->_categoryFactory->create()->load($categoryId);
    }
    public function getLevel2Category($category_id, $level)
    {
        $cateData = $this->getDataCategory($category_id);
        while (1) {
            if ($cateData->getLevel()=='2' or $cateData->getLevel()==$level or $cateData->getLevel()=='1') {
                break;
            }
            $cateData = $this->getDataCategory($cateData->getParentId());
        }
        return $cateData;
    }
    public function getListLeve2Category($arrConvert)
    {
        $x=[];
        $listCategory=[];
        for ($i = 0; $i < count($arrConvert); $i++) {
            $cateData = $this->getLevel2Category($arrConvert[$i], '2');
            $x[]=$cateData->getEntityId();
            $temp=json_encode(
                [
                'id_catagory'=>$cateData->getEntityId(),
                'name' => $cateData->getName(),
                'level' => $cateData->getLevel(),
                'parent' => $cateData->getParentId(),
                ]
            );
            $listCategory[] =  $temp;
        }
        for ($i = 0; $i < count($arrConvert); $i++) {
            $cateData = $this->getLevel2Category($arrConvert[$i], '3');
            $x[]=$cateData->getEntityId();
            $temp=json_encode(
                [
                'id_catagory'=>$cateData->getEntityId(),
                'name' => $cateData->getName(),
                'level' => $cateData->getLevel(),
                'parent' => $cateData->getParentId(),
                ]
            );
            $listCategory[] =  $temp;
        }
        for ($i = 0; $i < count($arrConvert); $i++) {
            $cateData = $this->getLevel2Category($arrConvert[$i], '4');
            $x[]=$cateData->getEntityId();
            $temp=json_encode(
                [
                'id_catagory'=>$cateData->getEntityId(),
                'name' => $cateData->getName(),
                'level' => $cateData->getLevel(),
                'parent' => $cateData->getParentId(),
                ]
            );
            $listCategory[] =  $temp;
        }
        $result = array_unique($listCategory);
        $listCategory=[];
        foreach ($result as $item) {
            $temp=(array)json_decode($item);
            $listCategory[] = $temp;
        }

        return $listCategory;
    }
    public function dataChildCategory($arrConvert)
    {
        $catItem = [];
        for ($i = 0; $i < count($arrConvert); $i++) {
            $cateData = $this->getDataCategory($arrConvert[$i]);
            $catItem[] = [
                'id' => $arrConvert[$i],
                'name' => $cateData->getName(),
                'level' => $cateData->getLevel(),
                'parent' => $cateData->getParentId(),
            ];
        }
        return $catItem;
    }

    public function getChildCategory($arrConvert, $rank)
    {
        $arrayCategory = [];
        $allCategory=$this->getListLeve2Category($arrConvert);
        $this->_logger->debug('all');
        $this->_logger->debug(print_r($allCategory, true));

        foreach ($allCategory as $item) {
            if ($item['level'] == $rank) {
                $arrayCategory[] = $item;
            }
            $this->_logger->debug($rank);
            $this->_logger->debug(print_r($arrayCategory, true));
        }
        return $arrayCategory;
    }

    /**
     * setting
     */
    public function prepareTemplate()
    {
    }


    /**
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getScopeConfig()
    {
        return $this->_scopeConfig;
    }

    /**
     * @return \Magento\Framework\UrlInterface
     */
    public function getUrlBuilder()
    {
        return $this->_urlBuilder;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return mixed
     */
    public function getBrandConfig()
    {
        return $this->_scopeConfig->getValue('shopbybrand/brandpage/categories_brand');
    }


    /**
     * @return string
     */
    public function getBaseMediaUrl()
    {
        return $this->_urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);
    }

    /**
     * @return \Magenest\ShopByBrand\Model\Config\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return string
     */
    public function getBrandUrl()
    {
        $configUrl = $this->getUrlRewrite();
        return $this->getBaseUrl().$configUrl;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getBaseMediaUrl().Router::ROUTER_MEDIA.'/';
    }

    /**
     * Get Url Rewrite
     *
     * @return mixed
     */
    public function getUrlRewrite()
    {
        $value = $this->_scopeConfig->getValue('shopbybrand/page/url');
        return $value;
    }
}
