<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * @copyright (c) Proud Sourcing GmbH | 2013
 * @link www.proudcommerce.com
 * @package psRedirect404
**/

namespace ProudCommerce\Redirect404\Application\Core;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class psRedirect404_oxutils
 *
 * @package ProudCommerce\Redirect404\Application\Core
 */
class Utils extends Utils_parent
{
    /**
     * handler for 404 (page not found) error
     *
     * @param string $sUrl url which was given, can be not specified in some cases
     *
     * @return void
     */
    public function handlePageNotFoundError($sUrl = '')
    {
        $seoLevenstein = new SeoLevenstein();

        try {

            $seoLevenstein
                ->isDeactive(function () use ($sUrl) {
                    parent::handlePageNotFoundError($sUrl);
                })
                ->onRedirect(function ($url, $httpStatus) {
                    Registry::getUtils()->redirect($url, false, $httpStatus);
                })
                ->searchUrl($sUrl);

        } catch (\Exception $e) {
            getLogger()->warning('Exception redirect404: '. $e->getMessage(), ['module' => 'redirect404']);
            parent::handlePageNotFoundError($sUrl);
        }
    }
}
