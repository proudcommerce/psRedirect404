<?php

namespace ProudCommerce\Redirect404\Tests\Helper;


use OxidEsales\Eshop\Core\Utils;

/**
 * Class MockUtils
 *
 * @package ProudCommerce\Redirect404\Tests\Helper
 * @mixin Utils
 */
class MockUtils
{

    /**
     * @var Utils
     */
    private $realClass = null;

    /**
     * @var array
     */
    public static $funcRedirect = [];

    public function __construct()
    {
        $this->realClass = oxNew(Utils::class);
    }

    public function __call($name, $arguments)
    {
        return call_user_func([$this->realClass, $name], ...$arguments);
    }

    /**
     * Hard Mock function redirect
     *
     * @param $sUrl
     * @param bool $blAddRedirectParam
     * @param int $iHeaderCode
     */
    public function redirect($sUrl, $blAddRedirectParam = true, $iHeaderCode = 302)
    {
        static::$funcRedirect['sUrl'] = $sUrl;
        static::$funcRedirect['blAddRedirectParam'] =  $blAddRedirectParam;
        static::$funcRedirect['iHeaderCode'] =  $iHeaderCode;
    }

    /**
     * @param $sMsg
     */
    public function showMessageAndExit($sMsg)
    {
        throw new \LogicException($sMsg);
    }
}
