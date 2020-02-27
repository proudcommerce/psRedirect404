<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @package psRedirect404
 * @copyright ProudCommerce
 * @link www.proudcommerce.com
 **/

namespace ProudCommerce\Redirect404\Helper;

use tm\oxid\SchemaExpander\DesireExpander;

/**
 * Class InitEvents
 */
class InitEvents
{
    public static function onModuleActivation()
    {
        $desire = new DesireExpander();

        $desire->table('oxseohistory')
            ->addField('PC_CREATOR', 'char(11) DEFAULT "oxideshop" COMMENT "Module psRedirect404 or oxidshop created this entry."');

        $desire->execute();
    }

    public static function onModuleDeactivation()
    {
    }
}
