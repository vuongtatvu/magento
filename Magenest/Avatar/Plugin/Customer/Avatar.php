<?php

namespace Magenest\Avatar\Plugin\Customer;
class Avatar
{
    public function aroundSaveTemporaryFile($subject, callable $proceed, $fileId)
    {
        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $result = $proceed($fileId);
        if (!isset($result['path'])) {
            $result['path'] = '/srv/http/magento/magento2/pub/media/customer/tmp';

        }
        return $result;
    }
}