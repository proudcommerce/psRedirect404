<?php

namespace ProudCommerce\Redirect404\Tests\Helper;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Application\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Common\Database\QueryBuilderFactoryInterface;

trait SaveUrlToDatanbase
{
    protected function setUrlsIntoOxseo(array $urls)
    {
        array_walk($urls, function ($url) {
            ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)
                ->create()
                ->insert('oxseo')
                ->values([
                    'OXOBJECTID' => ':oxobjectid',
                    'OXIDENT' => ':oxident',
                    'OXSHOPID' => ':oxshopid',
                    'OXSEOURL' => ':oxseourl',
                    'OXTYPE' => ':oxtype',
                    'OXEXPIRED' => ':oxexpired',
                ])
                ->setParameters([
                    'oxobjectid' => substr('PHPUnit_'.md5('OXOBJECTID'. $url), 0,32),
                    'oxident' => md5('OXIDENT'. $url),
                    'oxshopid' => Registry::getConfig()->getShopId(),
                    'oxseourl' => $url,
                    'oxtype' => 'static',
                    'oxexpired' => 0,
                ])
                ->execute();
        });
    }

    protected function removePHPUnitURls()
    {
        ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)
            ->create()
            ->delete('oxseo')
            ->where("OXOBJECTID LIKE 'PHPUnit_%'")
        ->execute();
    }
}
