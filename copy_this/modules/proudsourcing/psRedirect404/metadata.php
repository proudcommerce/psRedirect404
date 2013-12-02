<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'psRedirect404',
    'title'        => 'psRedirect404',
    'description'  => array(
        'de' => 'Sucht bei nicht gefunden Seiten nach der nächst Besten anhand des levenshtein Alghoritmus und leitet dahin weiter.',
        'en' => 'Redirect on 404 eror pages with levenshtein algorithm. Free Module for OXID eShop.',
    ),
    'thumbnail'    => '',
    'version'      => '1.1.0',
    'author'       => 'Proud Sourcing GmbH',
    'url'          => 'http://www.proudcommerce.com',
    'email'        => 'support@proudcommerce.com',
    'extend'       => array(
        'oxutils'    =>      'proudsourcing/psRedirect404/core/psredirect404_oxutils'
    ),
    'files' => array(
    ),
   'templates' => array(
    ),
   'blocks' => array(
    ),
   'settings' => array(
       array('group' => 'psRedirect404Main', 'name' => 'psRedirect404_status', 'type' => 'bool', 'value' => true, 'position' => 1),
       array('group' => 'psRedirect404Main', 'name' => 'psRedirect404_comparewholeurl', 'type' => 'bool', 'value' => true, 'position' => 2),
       array('group' => 'psRedirect404Main', 'name' => 'psRedirect404_redirecttype' , 'type' => 'select', 'value' => 'auto', 'position' => 3,  'constraints' => '301|302|auto'),
    )
);