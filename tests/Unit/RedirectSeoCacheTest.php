<?php

namespace ProudCommerce\Redirect404\Tests\Unit;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Application\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Common\Database\QueryBuilderFactoryInterface;
use ProudCommerce\Redirect404\Application\Model\RedirectSeoCache;
use PHPUnit\Framework\TestCase;

class RedirectSeoCacheTest extends TestCase
{
    public function testTestToSaveLevensteinUrl()
    {
        //Arande
        $redirectSeoCache = new RedirectSeoCache();

        $url = 'PHPUnit/Url/For/Seo/History/Cache/';
        $seoObjectid = 'PHPUnit_d35fcaad3aaba2a7f3d03b9e';

        //Act
        $redirectSeoCache->createCache($url, $seoObjectid);

        $actual = ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)
                    ->create()
                        ->select('OXIDENT, OXOBJECTID, OXSHOPID, OXLANG, PC_CREATOR')
                            ->from('oxseohistory')
                            ->where('OXIDENT = :oxident')
                        ->setParameter('oxident', md5(strtolower($url)))
                        ->setMaxResults(1)
                    ->execute()->fetchAll(\PDO::FETCH_ASSOC);

        $expect[0]['OXIDENT'] = md5(strtolower($url));
        $expect[0]['OXOBJECTID'] = $seoObjectid;
        $expect[0]['OXSHOPID'] = Registry::getConfig()->getShopId();
        $expect[0]['OXLANG'] = Registry::getLang()->getBaseLanguage();
        $expect[0]['PC_CREATOR'] = 'redirect404';

        //Assert
        $this->assertEquals($expect, $actual);
    }

    public function testCachtErrors()
    {
        //Arande
        $redirectSeoCache = new RedirectSeoCache();

        $url = 'PHPUnit/Url/For/Seo/History/Cache/';
        $seoObjectid = 'PHPUnit_d35fcaad3aaba2a7f3d03b9e';

        //Act
        $redirectSeoCache->createCache($url, $seoObjectid);
        $redirectSeoCache->createCache($url, $seoObjectid);

        //Assert
        $this->assertEmpty($this->getExpectedException());
    }

    protected function tearDown()
    {
        parent::tearDown();

        ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)
            ->create()
                ->delete('oxseohistory')->where('OXOBJECTID LIKE "PHPUnit_%"')
            ->execute();
    }
}
