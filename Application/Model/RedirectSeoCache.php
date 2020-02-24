<?php

namespace ProudCommerce\Redirect404\Application\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Application\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Common\Database\QueryBuilderFactoryInterface;

/**
 * Class LevensteinCache
 *
 * @package OxidEsales\EshopCommunity\modules\pc\redirect404\Application
 */
class RedirectSeoCache
{

    const AUTHOR = 'redirect404';

    /**
     * @param string $url Levenstein match Url
     * @param string $seoObjectid Target oxobjectid from oxseo entity
     */
    public function createCache($url, $seoObjectid)
    {
        $key_ident = md5(strtolower($url));

        /** @var QueryBuilder $qb */
        $qb = ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)->create();
        $qb
            ->insert('oxseohistory')
            ->values([
                'OXOBJECTID' => ':oxobjectid',
                'OXIDENT' => ':oxident',
                'OXSHOPID' => ':oxshopid',
                'OXLANG' => ':oxlang',
                'OXINSERT' => 'now()',
                'PC_CREATOR' => ':pc_creator',
            ])
            ->setParameters([
                'oxobjectid' => $seoObjectid,
                'oxident' => $key_ident,
                'oxshopid' => Registry::getConfig()->getShopId(),
                'oxlang' => Registry::getLang()->getBaseLanguage(),
                'pc_creator' => self::AUTHOR,
            ]);

        try {
            $qb->execute();
        } catch (\Exception $e) {
            getLogger()->debug('Exception save match url to oxseohistory: '. $e->getMessage(), ['module' => 'redirect404']);
        }
    }
}
