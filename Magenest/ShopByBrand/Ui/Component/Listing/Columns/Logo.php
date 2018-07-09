<?php
/**
 * Created by PhpStorm.
 * User: duccanh
 * Date: 23/05/2016
 * Time: 00:51
 */
namespace Magenest\ShopByBrand\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Logo
 *
 * @package Magenest\ShopByBrand\Ui\Component\Listing\Columns
 */
class Logo extends Column
{
    /**
     * Url path
    */
    const BLOG_URL_PATH_EDIT   = 'shopbybrand/brand/edit';
    const BLOG_URL_PATH_DELETE = 'shopbybrand/brand/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @param ContextInterface      $context
     * @param UiComponentFactory    $uiComponentFactory
     * @param UrlInterface          $urlBuilder
     * @param StoreManagerInterface $storemanager
     * @param array                 $components
     * @param array                 $data
     * @param string                $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storemanager,
        array $components = [],
        array $data = [],
        $editUrl = self::BLOG_URL_PATH_EDIT
    ) {
        $this->_storeManager = $storemanager;
        $this->urlBuilder    = $urlBuilder;
        $this->editUrl       = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param  array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();

        $logo = $objectManager->get('Magenest\ShopByBrand\Model\Theme\Logo');

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $template = new \Magento\Framework\DataObject($item);
                $imageUrl = $mediaDirectory.'shopbybrand/brand/image'.$template->getLogo();
                $item[$fieldName.'_src']      = $imageUrl;
                $item[$fieldName.'_alt']      = $template->getName();
                $item[$fieldName.'_link']     = $this->urlBuilder->getUrl(
                    'shopbybrand/brand/edit',
                    [
                     'brand_id'    => $item['brand_id'],
                     'store' => $this->context->getRequestParam('store'),
                    ]
                );
                $id=$template->getShopByBrandTemplateId();
                $item[$fieldName.'_orig_src'] = $imageUrl;
            }
        }

        return $dataSource;
    }
}
