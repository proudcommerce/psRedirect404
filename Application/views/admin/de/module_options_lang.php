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

$sLangName = 'Deutsch';

$aLang = [
    'charset'                                        => 'UTF-8',
    'SHOP_MODULE_GROUP_psRedirect404Main'            => 'Stamm',
    'SHOP_MODULE_psRedirect404_status'               => 'Modul aktiv?',
    'SHOP_MODULE_psRedirect404_comparewholeurl'      => 'Gesamte URL vergleichen?',
    'HELP_SHOP_MODULE_psRedirect404_comparewholeurl' => 'Bei NICHT aktiver Option wird nur der Teil hinter dem letzten / verglichen, .z b. "mein-produkt.html" bei https://meinshop.de/meine-kategorie/mein-produkt.html',
    'SHOP_MODULE_psRedirect404_redirecttype'         => 'Redirect-Type',
    'SHOP_MODULE_psRedirect404_usecache'             => 'Eintrag in oxseohistory?',
    'HELP_SHOP_MODULE_psRedirect404_usecache'        => 'Sobald das erste mal eine URL gefunden wurde, wird ein Eintrag in oxseohistory erzeugt. Hinweis: Der Redirect-Type bei Einträgen aus oxseo/oxseohistory ist immer 301.',
    'SHOP_MODULE_psRedirect404_redirecttype_301'     => '301 (alle URLs)',
    'SHOP_MODULE_psRedirect404_redirecttype_302'     => '302 (alle URLs)',
    'SHOP_MODULE_psRedirect404_redirecttype_auto'    => 'auto (best Matches 301, andere URLs 302)',
];
