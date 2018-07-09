<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 11/22/16
 * Time: 09:49
 */

namespace Magenest\MapList\Helper;

use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\App\Helper\Context;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_encryptor;

    public function __construct(
        Context $context,
        EncryptorInterface $encryptor
    ) {
        $this->_encryptor = $encryptor;
        parent::__construct($context);
    }

    public function isShowMapListItem()
    {
        return $this->scopeConfig->getValue(
            'maplist/general/show_item_section',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
