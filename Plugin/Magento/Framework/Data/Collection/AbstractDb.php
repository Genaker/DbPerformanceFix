<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Genaker\DbOrderFix\Plugin\Magento\Framework\Data\Collection;

class AbstractDb
{

    public function aroundGetSize(
        \Magento\Framework\Data\Collection\AbstractDb $subject,
        \Closure $proceed
    ) {

        $sql = $subject->getSelectCountSql() . ''; 
        $pattern = "/^SELECT.*COUNT.* FROM.*sales_order_grid/sUi";
        $matches = [];
      
        if (preg_match($pattern, $sql, $matches)) {

            $getFilters = @$_GET['filters'];
            unset($getFilters['placeholder']);

            if(isset($_GET['filters']) && (count($getFilters) === 0 || (count($getFilters) === 1 && isset($getFilters['created_at']['from']) && count($getFilters['created_at']) === 1))){
                //var_dump($_GET); 
                //echo $sql;
                return 8888;
            }
            
        } 
        $result = $proceed();
        return $result;
    }

}