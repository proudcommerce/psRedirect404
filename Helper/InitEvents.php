<?php
/**
 * Created by oxid-module-skeleton.
 * Module: redirect404
 * Autor: Auto generate by oxrun <oxid-module-skeleton@oxid.projects.internal>
 *
 * @see https://github.com/OXIDprojects/oxrun
 */

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
                ->addField('PC_CREATOR', 'char(11) DEFAULT "oxideshop" COMMENT "Module pcRedirect404 or oxidshop created this entry."');

        $desire->execute();
    }

    public static function onModuleDeactivation()
    {
    }
}
