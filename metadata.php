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

$sMetadataVersion = '2.1';

$aModule = [
    'id'          => 'psRedirect404',
    'title'       => "psRedirect404",
    'description' => [
        'de' => 'Sucht bei nicht gefunden Seiten nach der nächst Besten anhand des levenshtein Alghoritmus und leitet dahin weiter.',
        'en' => 'Redirect on 404 error pages with levenshtein algorithm. Free Module for OXID eShop.',
    ],
    'thumbnail'   => 'logo_pc-os.jpg',
    'version'     => '2.0.1',
    'author'      => 'Proud Sourcing GmbH',
    'url'         => 'http://www.proudcommerce.com',
    'email'       => 'support@proudcommerce.com',

    'extend'                  => [
        \OxidEsales\Eshop\Core\Utils::class => \ProudCommerce\Redirect404\Application\Core\Utils::class
    ],
    'controllers'             => [],
    'templates'               => [],
    'smartyPluginDirectories' => [],
    'blocks'                  => [],
    'events'                  => [
        'onActivate'   => 'ProudCommerce\Redirect404\Helper\InitEvents::onModuleActivation',
        'onDeactivate' => 'ProudCommerce\Redirect404\Helper\InitEvents::onModuleDeactivation',
    ],
    'settings'                => [
        ['group' => 'psRedirect404Main', 'name' => 'psRedirect404_status', 'type' => 'bool', 'value' => true, 'position' => 1],
        ['group' => 'psRedirect404Main', 'name' => 'psRedirect404_comparewholeurl', 'type' => 'bool', 'value' => false, 'position' => 2],
        ['group' => 'psRedirect404Main', 'name' => 'psRedirect404_redirecttype', 'type' => 'select', 'value' => 'auto', 'position' => 3, 'constraints' => '301|302|auto'],
        ['group' => 'psRedirect404Main', 'name' => 'psRedirect404_usecache', 'type' => 'bool', 'value' => true],
    ],
];
