<?php
/**
 * Created by oxid-module-skeleton.
 * Module: redirect404
 * Autor: Auto generate by oxrun <oxid-module-skeleton@oxid.projects.internal>
 *
 * @see https://github.com/OXIDprojects/oxrun
 */

$sMetadataVersion = '2.1';

$aModule = [
    'id'            => 'pcRedirect404',
    'title'         => "Redirect404",
    'description'   => [
        'de' => 'Sucht bei nicht gefunden Seiten nach der nächst Besten anhand des levenshtein Alghoritmus und leitet dahin weiter.',
        'en' => 'Redirect on 404 error pages with levenshtein algorithm. Free Module for OXID eShop.',
    ],
    'thumbnail'     => 'logo_pc-os.jpg',
    'version'       => '2.0.0',
    'author'        => 'Proud Sourcing GmbH',
    'url'           => 'http://www.proudcommerce.com',
    'email'         => 'support@proudcommerce.com',

    'extend'                  => [
        \OxidEsales\Eshop\Core\Utils::class => \ProudCommerce\Redirect404\Application\Core\Utils::class
    ],
    'controllers'             => [],
    'templates'               => [],
    'smartyPluginDirectories' => [],
    'blocks'                  => [],
    'events'                  => [
        'onActivate'      => 'ProudCommerce\Redirect404\Helper\InitEvents::onModuleActivation',
        'onDeactivate'    => 'ProudCommerce\Redirect404\Helper\InitEvents::onModuleDeactivation',
    ],
    'settings' => [
       ['group' => 'psRedirect404Main', 'name' => 'psRedirect404_status', 'type' => 'bool', 'value' => true, 'position' => 1],
       ['group' => 'psRedirect404Main', 'name' => 'psRedirect404_comparewholeurl', 'type' => 'bool', 'value' => true, 'position' => 2],
       ['group' => 'psRedirect404Main', 'name' => 'psRedirect404_redirecttype' , 'type' => 'select', 'value' => 'auto', 'position' => 3,  'constraints' => '301|302|auto'],
    ],
];
