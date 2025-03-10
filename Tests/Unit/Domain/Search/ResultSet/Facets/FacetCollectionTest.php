<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace ApacheSolrForTypo3\Solr\Tests\Unit\Domain\Search\ResultSet\Facets;

use ApacheSolrForTypo3\Solr\Domain\Search\ResultSet\Facets\FacetCollection;
use ApacheSolrForTypo3\Solr\Domain\Search\ResultSet\Facets\OptionBased\Options\OptionsFacet;
use ApacheSolrForTypo3\Solr\Domain\Search\ResultSet\SearchResultSet;
use ApacheSolrForTypo3\Solr\Tests\Unit\UnitTest;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class FacetCollectionTest
 *
 * @author Frans Saris <frans@beech.it>
 */
class FacetCollectionTest extends UnitTest
{

    /**
     * @test
     */
    public function canAddAndRetrieveFacetByKey()
    {
        $facetCollection = new FacetCollection();
        $resultSetMock = $this->getDumbMock(SearchResultSet::class);
        $objectManagerMock = $this->createMock(ObjectManager::class);

        $colorFacet = new OptionsFacet($resultSetMock, 'color', 'color_s', '', ['groupName' => 'left'], $objectManagerMock);
        $brandFacet = new OptionsFacet($resultSetMock, 'brand', 'brand_s', '', ['groupName' => 'left'], $objectManagerMock);
        $facetCollection->addFacet($colorFacet);
        $facetCollection->addFacet($brandFacet);

        self::assertEquals($colorFacet, $facetCollection['color']);
        self::assertEquals($brandFacet, $facetCollection['brand']);
    }

    /**
     * @test
     */
    public function canAddAndRetrieveFacetByPosition()
    {
        $facetCollection = new FacetCollection();
        $resultSetMock = $this->getDumbMock(SearchResultSet::class);
        $objectManagerMock = $this->createMock(ObjectManager::class);

        $colorFacet = new OptionsFacet($resultSetMock, 'color', 'color_s', '', ['groupName' => 'left'], $objectManagerMock);
        $brandFacet = new OptionsFacet($resultSetMock, 'brand', 'brand_s', '', ['groupName' => 'left'], $objectManagerMock);
        $facetCollection->addFacet($colorFacet);
        $facetCollection->addFacet($brandFacet);

        self::assertEquals($colorFacet, $facetCollection->getByPosition(0));
        self::assertEquals($brandFacet, $facetCollection->getByPosition(1));
    }

    /**
     * @test
     */
    public function canRetrieveFacetOfCollectionCopyByKey()
    {
        $facetCollection = new FacetCollection();
        $resultSetMock = $this->getDumbMock(SearchResultSet::class);
        $objectManagerMock = $this->createMock(ObjectManager::class);

        $colorFacet = new OptionsFacet($resultSetMock, 'color', 'color_s', '', ['groupName' => 'top'], $objectManagerMock);
        $brandFacet = new OptionsFacet($resultSetMock, 'brand', 'brand_s', '', ['groupName' => 'left'], $objectManagerMock);
        $facetCollection->addFacet($colorFacet);
        $facetCollection->addFacet($brandFacet);

        $leftFacetCollection = $facetCollection->getByGroupName('left');
        self::assertEquals(1, $leftFacetCollection->count());
        self::assertEquals($brandFacet, $leftFacetCollection['brand']);
    }

    /**
     * @test
     */
    public function canRetrieveFacetOfCollectionCopyByPosition()
    {
        $facetCollection = new FacetCollection();
        $resultSetMock = $this->getDumbMock(SearchResultSet::class);
        $objectManagerMock = $this->createMock(ObjectManager::class);

        $colorFacet = new OptionsFacet($resultSetMock, 'color', 'color_s', '', ['groupName' => 'top'], $objectManagerMock);
        $brandFacet = new OptionsFacet($resultSetMock, 'brand', 'brand_s', '', ['groupName' => 'left'], $objectManagerMock);
        $facetCollection->addFacet($colorFacet);
        $facetCollection->addFacet($brandFacet);

        $leftFacetCollection = $facetCollection->getByGroupName('left');
        self::assertEquals(1, $leftFacetCollection->count());
        self::assertEquals($brandFacet, $leftFacetCollection->getByPosition(0));
    }
}
