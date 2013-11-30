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
        'de' => 'Speichert die Google Adwords Click-ID (gclid) beim Kunden (falls dieser eingeloggt ist).',
        'en' => 'Saves google adwords click id (gclid) to user (if logged in).',
    ),
    'thumbnail'    => '',
    'version'      => '1.0.0',
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
    )
);