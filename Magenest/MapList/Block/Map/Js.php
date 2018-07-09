<?php
/**
 * Created by PhpStorm.
 * User: hiennq
 * Date: 9/23/16
 * Time: 13:50
 */

namespace Magenest\MapList\Block\Map;

use Magenest\MapList\Block\Block;

class Js extends Block
{
    public function getDataView()
    {
        $dataView = $this->getConfig();

        return $dataView;
    }
}
