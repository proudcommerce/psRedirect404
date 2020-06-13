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

namespace ProudCommerce\Redirect404\Application\Core;

use Doctrine\DBAL\Query\QueryBuilder;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Application\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Common\Database\QueryBuilderFactoryInterface;

/**
 * Class SeoLevenstein
 *
 * @package ProudCommerce\Redirect404\Application\Core
 */
class SeoLevenstein
{
    /**
     * @var callable
     */
    private $onDeactive = null;

    /**
     * @var callable
     */
    private $onRedirect = null;

    /**
     * @param callable $callable
     * @return $this
     */
    public function isDeactive(callable $callable)
    {
        $this->onDeactive = $callable;

        return $this;
    }

    /**
     * @param callable $callable
     * @return $this
     */
    public function onRedirect(callable $callable)
    {
        $this->onRedirect = $callable;

        return $this;
    }

    /**
     * Main Function
     *
     * @param string $sUrl
     */
    public function searchUrl($sUrl)
    {
        if (Registry::getConfig()->getConfigParam("psRedirect404_status") == false) {
            call_user_func($this->onDeactive);
            return;
        }

        $iShortest = -1;
        $httpStatus = 302;
        $sSearchString = $this->_clearUrl($sUrl);
        $targetSeo = '';

        // psRedirect404
        // checks based on levenshtein algorithm closest url from
        // oxid seo urls (oxseo) and redirect with header 302 to this page

        $seoUrls = $this->_getSeoUrls();
        foreach ($seoUrls as $row) {
            $sUrl = $this->_clearUrl($row['oxseourl']);
            $sLevRes = levenshtein($sSearchString, $sUrl);

            if ($sLevRes <= $iShortest || $iShortest < 0) {
                $sClosest = $row['oxseourl'];
                $targetSeo = $row['oxobjectid'];

                $iShortest = $sLevRes;
                if ($sLevRes <= 10 && Registry::getConfig()->getConfigParam("psRedirect404_redirecttype") == "auto") {
                    $httpStatus = 301;
                }
            }
        }

        if (Registry::getConfig()->getConfigParam("psRedirect404_redirecttype") == "301") {
            $httpStatus = 301;
        }

        $newUrl = Registry::getConfig()->getShopUrl() . $sClosest;

        call_user_func($this->onRedirect, $newUrl, $httpStatus, $targetSeo);
    }

    /**
     * Cleans given seo url
     *
     * @param string $sUrl seo url
     * @return string
     */
    protected function _clearUrl($sUrl)
    {
        // compare short urls?
        if (Registry::getConfig()->getConfigParam('psRedirect404_comparewholeurl')) {
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

        $qb->select('oxseourl, oxobjectid')
            ->from('oxseo')
            ->where("oxshopid = :oxshopid")
            ->andWhere('oxlang = :oxlang')
            ->andWhere('oxexpired = 0')
            ->andWhere('oxobjectid IN (SELECT oxid FROM oxarticles WHERE oxactive = 1)')
            ->setParameter('oxshopid', Registry::getConfig()->getShopId())
            ->setParameter('oxlang', Registry::getLang()->getTplLanguage())
            ->orderBy('oxtimestamp');

        $all = $qb->execute()->fetchAll(\PDO::FETCH_ASSOC);

        return $all;
    }
}
