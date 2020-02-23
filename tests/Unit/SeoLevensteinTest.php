<?php

namespace ProudCommerce\Redirect404\Tests\Unit;

use OxidEsales\Eshop\Core\Registry;
use ProudCommerce\Redirect404\Application\Core\SeoLevenstein;
use PHPUnit\Framework\TestCase;
use ProudCommerce\Redirect404\Tests\Helper;

class SeoLevensteinTest extends TestCase
{
    use Helper\SaveUrlToDatanbase;

    protected function setUp()
    {
        parent::setUp();
        Registry::getConfig()->init();
    }

    public function testEventIsDeactiveCanBeSet()
    {
        //Arange
        $seoLevenstein = new SeoLevenstein();

        //Act
        $actual = $seoLevenstein->isDeactive(function () {});

        //Assert
        $this->assertInstanceOf(SeoLevenstein::class, $actual);
    }

    public function testEventOnRedirectCanBeSet()
    {
        //Arange
        $seoLevenstein = new SeoLevenstein();

        //Act
        $actual = $seoLevenstein->onRedirect(function () {});

        //Assert
        $this->assertInstanceOf(SeoLevenstein::class, $actual);
    }

    public function testIsTheModuleDeactivatedViaConfig()
    {
        //Arange
        Registry::getConfig()->setConfigParam('psRedirect404_status', false);
        $seoLevenstein = new SeoLevenstein();

        //Act
        $actual = null;
        $seoLevenstein
            ->isDeactive(function () use (&$actual){
                $actual = true;
            })
            ->searchUrl('');

        //Assert
        $this->assertTrue($actual);
    }

    /**
     * @param $expect
     * @param $oxseotable
     * @param $url
     * @dataProvider urlsForHttpStatus
     */
    public function testConfigHTTPStatus($config, $expect, $oxseoUrls, $url)
    {
        //Arange
        Registry::getConfig()->setConfigParam('psRedirect404_redirecttype', $config);
        $this->setUrlsIntoOxseo($oxseoUrls);
        $seoLevenstein = new SeoLevenstein();

        //Act
        $actual = [];
        $seoLevenstein
            ->onRedirect(function ($url, $httpStatus) use (&$actual){
                $actual['url'] = $url;
                $actual['httpStatus'] = $httpStatus;
            })
            ->searchUrl($url);

        //Assert
        $this->assertEquals($expect, $actual['httpStatus']);
    }

    public function urlsForHttpStatus()
    {
        return [
            'Auto httpStatus 301'        => ['auto', 301, ['PHPUnit/difference/less/than/10/level/points/'], 'PHPUnit/difference/less/than/10/leve'],
            'Auto httpStatus 302'        => ['auto', 302, ['PHPUnit/difference/over/than/10/level/points/'], 'PHPUnit/difference/over/than/'],

            'Fix httpStatus 302 Level 9' => [302, 302, ['PHPUnit/difference/less/than/10/level/points/'], 'PHPUnit/difference/less/than/10/leve'],
            'Fix httpStatus 302 Level 16' => [302, 302, ['PHPUnit/difference/over/than/10/level/points/'], 'PHPUnit/difference/over/than/'],

            'Fix httpStatus 301 Level 9' => [301, 301, ['PHPUnit/difference/less/than/10/level/points/'], 'PHPUnit/difference/less/than/10/leve'],
            'Fix httpStatus 301 Level 16' => [301, 301, ['PHPUnit/difference/over/than/10/level/points/'], 'PHPUnit/difference/over/than/'],
        ];
    }

    /**
     * @param $expect
     * @param $oxseotable
     * @param $url
     * @dataProvider urlsBasename
     */
    public function testLevenshteinOnlyBasenameFromTheURL($config, $expect, $url, $oxseoUrls)
    {
        //Arange
        Registry::getConfig()->setConfigParam('psRedirect404_comparewholeurl', $config);
        $this->setUrlsIntoOxseo($oxseoUrls);
        $seoLevenstein = new SeoLevenstein();

        //Act
        $actual = [];
        $seoLevenstein
            ->onRedirect(function ($url, $httpStatus) use (&$actual){
                $actual['url'] = $url;
                $actual['httpStatus'] = $httpStatus;
            })
            ->searchUrl($url);

        //Assert
        $this->assertEquals($expect, $actual['url']);
    }


    public function urlsBasename()
    {
        return [
            'compare whole url'        => [
                false,
                Registry::getConfig()->getShopUrl() . 'PHPUnit/XXXX/ABFF',
                'PHPUnit/name/ABCD',
                [
                    'PHPUnit/Xame/AFFF', //Shortest 3
                    'PHPUnit/XXXX/ABFF', //Shortest 2
                ],
            ],
            'compare not whole url'        => [
                true,
                Registry::getConfig()->getShopUrl() . 'PHPUnit/Xame/AFFF',
                'PHPUnit/name/ABCD',
                [
                    'PHPUnit/Xame/AFFF', //Shortest 4
                    'PHPUnit/XXXX/ABFF', //Shortest 6
                ],
            ],
        ];
    }

    protected function tearDown()
    {
        parent::tearDown();
        Registry::getConfig()->setConfigParam('psRedirect404_status', true);
        Registry::getConfig()->setConfigParam('psRedirect404_redirecttype', 'auto');
        Registry::getConfig()->setConfigParam('psRedirect404_comparewholeurl', true);

        $this->removePHPUnitURls();
    }
}
