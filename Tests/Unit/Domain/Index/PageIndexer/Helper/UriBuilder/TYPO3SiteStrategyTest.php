<?php

namespace ApacheSolrForTypo3\Solr\Tests\Unit\Domain\Index\PageIndexer\Helper\UriBuilder;

use ApacheSolrForTypo3\Solr\Domain\Index\PageIndexer\Helper\UriBuilder\TYPO3SiteStrategy;
use ApacheSolrForTypo3\Solr\IndexQueue\Item;
use ApacheSolrForTypo3\Solr\System\Logging\SolrLogManager;
use ApacheSolrForTypo3\Solr\Tests\Unit\UnitTest;
use Psr\Http\Message\UriInterface;
use TYPO3\CMS\Core\Routing\RouterInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SolrSiteStrategyTest
 */
class TYPO3SiteStrategyTest extends UnitTest
{

    /**
     * @test
     */
    public function testPageIndexingUriFromPageItemAndLanguageId()
    {
        $pageRecord = ['uid' => 55];
        $itemMock = $this->getDumbMock(Item::class);
        $itemMock->expects(self::any())->method('getRecord')->willReturn($pageRecord);
        $itemMock->expects(self::any())->method('getRecordUid')->willReturn(55);
        $siteFinderMock = $this->getSiteFinderMock($pageRecord);

        /** @var $loggerMock SolrLogManager */
        $loggerMock = $this->getDumbMock(SolrLogManager::class);

        /** @var TYPO3SiteStrategy $typo3SiteStrategy */
        $typo3SiteStrategy = GeneralUtility::makeInstance(TYPO3SiteStrategy::class, $loggerMock, $siteFinderMock);
        $typo3SiteStrategy->getPageIndexingUriFromPageItemAndLanguageId($itemMock, 2, 'foo');
    }

    /**
     * @test
     */
    public function canOverrideHost()
    {
        $pageRecord = ['uid' => 55];
        $itemMock = $this->getDumbMock(Item::class);
        $itemMock->expects(self::any())->method('getRecord')->willReturn($pageRecord);
        $itemMock->expects(self::any())->method('getRecordUid')->willReturn(55);
        $siteFinderMock = $this->getSiteFinderMock($pageRecord);

        /** @var $loggerMock SolrLogManager */
        $loggerMock = $this->getDumbMock(SolrLogManager::class);

        /** @var TYPO3SiteStrategy $strategy */
        $typo3SiteStrategy = GeneralUtility::makeInstance(TYPO3SiteStrategy::class, $loggerMock, $siteFinderMock);
        $uri = $typo3SiteStrategy->getPageIndexingUriFromPageItemAndLanguageId($itemMock, 2, 'foo', ['frontendDataHelper.' => ['host' => 'www.secondsite.de']]);
        self::assertSame('http://www.secondsite.de/en/test', $uri, 'Solr site strategy generated unexpected uri');
    }

    /**
     * @test
     */
    public function canOverrideScheme()
    {
        $pageRecord = ['uid' => 55];
        $itemMock = $this->getDumbMock(Item::class);
        $itemMock->expects(self::any())->method('getRecord')->willReturn($pageRecord);
        $itemMock->expects(self::any())->method('getRecordUid')->willReturn(55);
        $siteFinderMock = $this->getSiteFinderMock($pageRecord);

        /** @var $loggerMock SolrLogManager */
        $loggerMock = $this->getDumbMock(SolrLogManager::class);

        /** @var TYPO3SiteStrategy $strategy */
        $typo3SiteStrategy = GeneralUtility::makeInstance(TYPO3SiteStrategy::class, $loggerMock, $siteFinderMock);
        $uri = $typo3SiteStrategy->getPageIndexingUriFromPageItemAndLanguageId($itemMock, 2, 'foo', ['frontendDataHelper.' => ['scheme' => 'https']]);
        self::assertSame('https://www.site.de/en/test', $uri, 'Solr site strategy generated unexpected uri');
    }

    /**
     * @return array
     */
    protected function getSiteFinderMock($pageRecord = []): SiteFinder
    {
        $uriMock = $this->getDumbMock(UriInterface::class);
        $uriMock->expects(self::any())->method('__toString')->willReturn('http://www.site.de/en/test');

        $routerMock = $this->getDumbMock(RouterInterface::class);
        $routerMock->expects(self::once())->method('generateUri')->with($pageRecord, ['_language' => 2, 'MP' => 'foo'])
            ->willReturn($uriMock);

        $siteMock = $this->getDumbMock(Site::class);
        $siteMock->expects(self::once())->method('getRouter')->willReturn($routerMock);

        /** @var SiteFinder $siteFinderMock */
        $siteFinderMock = $this->getDumbMock(SiteFinder::class);
        $siteFinderMock->expects(self::once())->method('getSiteByPageId')->willReturn($siteMock);
        return $siteFinderMock;
    }
}
