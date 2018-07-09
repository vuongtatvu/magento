<?php
/**
 * Created by PhpStorm.
 * User: heomep
 * Date: 21/09/2016
 * Time: 15:01
 */

namespace Magenest\MapList\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magenest\MapList\Model\LocationFactory;

/**
 * Class Logo
 *
 * @package Magenest\MapList\Ui\Component\Listing\Columns
 */
class Icon extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    protected $_locationFactory;

    /**
     * Icon constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        LocationFactory $locationFactory,
        array $components = [],
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->_locationFactory = $locationFactory;
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
        $locationModel = $this->_locationFactory->create();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $mediaUrl = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $i = 0;
            foreach ($dataSource['data']['items'] as & $item) {
                $locationId = $item['location_id'];
                $location = $locationModel->load($locationId);
                $image = $location->getData('small_image');

                $template = new \Magento\Framework\DataObject($item);
                if ($image) {
                    $imageUrl = $mediaUrl .'catalog/category/'. $image;
                } else {
                    $imageUrl = 'http://www.mkshahmcrc.org/images/site_img/user.png';
                }
                $item[$fieldName . '_src'] = $imageUrl;
                $item[$fieldName . '_alt'] = $template->getName();
                $item[$fieldName . '_orig_src'] = $imageUrl;
                $dataSource['data']['items'][$i] = $item;
                $i++;
            }
        }

        return $dataSource;
    }
}
