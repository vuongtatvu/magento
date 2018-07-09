<?php

namespace Magenest\Sales\Plugin;

class SalesAgentFirstName
{
    public function afterGetFirstName(\Magento\Customer\Model\Customer $subject, $result)
    {
        $a = $subject->getCustomAttribute('is_sales_agent')->getValue();
        if ($subject->getCustomAttribute('is_sales_agent')->getValue())
            return "Sales Agent: " . $result;
    }
}