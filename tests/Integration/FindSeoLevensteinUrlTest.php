<?php

namespace ProudCommerce\Redirect404\Tests\Integration;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Utils;
use OxidEsales\EshopCommunity\Internal\Application\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Common\Database\QueryBuilderFactoryInterface;
use PHPUnit\Framework\TestCase;
use ProudCommerce\Redirect404\Application\Model\RedirectSeoCache;
use ProudCommerce\Redirect404\Tests\Helper;


/**
 * Class FindSeoLevensteinUrlTest
 * @package ProudCommerce\Redirect404\Tests\Integration
 */
class FindSeoLevensteinUrlTest extends TestCase
{
    use Helper\SaveUrlToDatanbase;

    protected function setUp()
    {
        parent::setUp();
        Registry::getConfig()->init();

        Registry::getConfig()->setConfigParam('psRedirect404_status', true);
        Registry::getConfig()->setConfigParam('psRedirect404_redirecttype', 'auto');
        Registry::getConfig()->setConfigParam('psRedirect404_comparewholeurl', false);
        Registry::getConfig()->setConfigParam('psRedirect404_usecache', true);

        //Mock Utils
        Registry::set(Utils::class, new Helper\MockUtils());
    }

    public function testPlayTheWholeProcess()
    {
        //Arrange
        $realUrl = 'PHPUnit/this/is/the/real/path/';
        $similarUrl = 'PHPUnit/this/is/the/similar/path/';

        $this->setUrlsIntoOxseo([$realUrl]);

        $expectUrl = Registry::getConfig()->getShopUrl() . $realUrl;

        //Act
        Registry::getUtils()->handlePageNotFoundError($similarUrl);
        $actualUrl = Helper\MockUtils::$funcRedirect['sUrl'];

        //Assert
        $this->assertEquals($expectUrl, $actualUrl);
    }

    public function testPlayTheWholeProcessWithCache()
    {
        //Arrange
        $realUrl    = 'PHPUnit/this/is/a/real/path/cached.html';
        $similarUrl = 'PHPUnit/this/is/a/similar/path/cached.html';
        $unknownUrl = 'PHPUnit/this/is/a/unknown/new/url/';
        $expect[0] = [
            'OXOBJECTID' => $this->getPHPUnitKey($realUrl),
            'OXHITS' => '2'
        ];

        $this->setUrlsIntoOxseo([$realUrl]);
        sleep(1);
        $this->setUrlsIntoOxseo([$unknownUrl]);

        //Pre Act create cache
        Registry::getUtils()->handlePageNotFoundError($similarUrl);

        //Act with cache
        oxNew(\OxidEsales\Eshop\Core\SeoDecoder::class)->processSeoCall($similarUrl);
        oxNew(\OxidEsales\Eshop\Core\SeoDecoder::class)->processSeoCall($similarUrl);

        $actual = ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)
            ->create()
                ->select('OXOBJECTID, OXHITS')
                    ->from('oxseohistory')
                    ->where('OXIDENT = :oxident')
                    ->setParameter('oxident', md5(strtolower($similarUrl)))
            ->execute()->fetchAll(\PDO::FETCH_ASSOC);

        //Assert
        $this->assertEquals($expect, $actual);
    }

    protected function tearDown()
    {
        parent::tearDown();

        Registry::set(Utils::class, null);

        ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)
            ->create()
                ->delete('oxseohistory')
                    ->where('PC_CREATOR = :pc_creator')
                ->setParameter('pc_creator', RedirectSeoCache::AUTHOR)
            ->execute();

        Helper\MockUtils::$funcRedirect = [];

        $this->removePHPUnitURls();
    }
}
