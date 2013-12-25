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
 * @version 1.1.0
**/
class psRedirect404_oxutils extends psRedirect404_oxutils_parent
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
        // module active?
        if(!oxRegistry::getConfig()->getConfigParam("psRedirect404_status"))
        {
            return parent::handlePageNotFoundError($sUrl = '');
        }

        $iShortest = -1;
        $iHeaderType = 302;
        $sSearchString = $this->_clearUrl($sUrl);

        // psRedirect404
        // checks based on levenshtein algorithm closest url from
        // oxid seo urls (oxseo) and redirect with header 302 to this page

        try {
            foreach($this->_getSeoUrls() as $value)
            {
                $sUrl = $this->_clearUrl($value[0]);
                $sLevRes = levenshtein($sSearchString, $sUrl);
                #echo $sLevRes." - ".$sUrl." (".$value[0].")<br>";
                if ($sLevRes <= $iShortest || $iShortest < 0) {
                    $sClosest = $value[0];
                    $iShortest = $sLevRes;
                    if($sLevRes <= 10 && oxRegistry::getConfig()->getConfigParam("psRedirect404_redirecttype") == "auto")
                    {
                        $iHeaderType = 301;
                    }
                }
            }
            if(!oxRegistry::getConfig()->getConfigParam("psRedirect404_redirecttype") == "301")
            {
                $iHeaderType = 301;
            }
            oxRegistry::getUtils()->redirect( oxRegistry::getConfig()->getShopUrl() . $sClosest, false, $iHeaderType );
        } catch (Exception $e) {
        }
        $this->showMessageAndExit( "Found" );
    }

    /**
     * Cleans given seo url
     *
     * @param   arr     seo url
     *
     * @return  string
     */
    protected function _clearUrl( $sUrl )
    {
        // compare short urls?
        if(oxRegistry::getConfig()->getConfigParam("psRedirect404_comparewholeurl"))
        {
            return $sUrl;
        }
        $aUrl = explode("/", $sUrl);
        $aUrl = array_filter($aUrl);
        return end($aUrl);
    }

    /**
     * Gets all seo urls from database
     *
     * @return  arr
     */
    protected function _getSeoUrls()
    {
        $sSql = "SELECT oxseourl FROM oxseo WHERE oxshopid = '".oxRegistry::getConfig()->getShopId()."' AND oxlang = ".oxRegistry::getLang()->getTplLanguage()." AND oxexpired = 0 ORDER BY oxtimestamp";
        return oxDb::getDb()->getAll($sSql);
    }
}
