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

use Doctrine\DBAL\Query\QueryBuilder;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Application\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Common\Database\QueryBuilderFactoryInterface;

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
        // module active?
        if(!Registry::getConfig()->getConfigParam("psRedirect404_status"))
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
                    if($sLevRes <= 10 && Registry::getConfig()->getConfigParam("psRedirect404_redirecttype") == "auto")
                    {
                        $iHeaderType = 301;
                    }
                }
            }
            if(!Registry::getConfig()->getConfigParam("psRedirect404_redirecttype") == "301")
            {
                $iHeaderType = 301;
            }
            Registry::getUtils()->redirect( Registry::getConfig()->getShopUrl() . $sClosest, false, $iHeaderType );
        } catch (\Exception $e) {
        }
        Registry::getUtils()->showMessageAndExit( "Found" );
    }

    /**
     * Cleans given seo url
     *
     * @param string $sUrl seo url
     * @return string
     */
    protected function _clearUrl( $sUrl )
    {
        // compare short urls?
        if(Registry::getConfig()->getConfigParam("psRedirect404_comparewholeurl"))
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
     * @return mixed[]
     */
    protected function _getSeoUrls()
    {
        /** @var QueryBuilder $qb */
        $qb = ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)->create();

        $qb->select('oxseourl')
            ->from('oxseo')
            ->where("oxshopid = :oxshopid")
            ->andWhere('oxlang = :oxlang')
            ->andWhere('oxexpired = 0')
            ->setParameter('oxshopid', Registry::getConfig()->getShopId())
            ->setParameter('oxlang', Registry::getLang()->getTplLanguage())
            ->orderBy('oxtimestamp')
        ;

        $all = $qb->execute()->fetchAll(\PDO::FETCH_NUM);
        return $all;
    }
}
