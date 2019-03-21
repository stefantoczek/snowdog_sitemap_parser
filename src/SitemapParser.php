<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 19.03.19
 * Time: 11:46
 */

namespace Stefantoczek\SitemapParser;

use Stefantoczek\SitemapParser\Interfaces\WebsiteDatabaseInterface;
use Stefantoczek\SitemapParser\Parser\SitemapXmlParser;
use Stefantoczek\SitemapParser\Parser\XmlParser;
use Stefantoczek\SitemapParser\XmlDataStream\XmlFileStream;

class SitemapParser
{
    private $sitemapXmlParser;
    private $xmlParser;
    private $stream;
    /** @var array */
    private $parsedSitemap;
    private $websiteManager;

    public function __construct(
        SitemapXmlParser $sitemapXmlParser,
        XmlParser $xmlParser
    ) {
        $this->sitemapXmlParser = $sitemapXmlParser;
        $this->xmlParser = $xmlParser;
    }

    public function setWebsiteDatabaseSetter(WebsiteDatabaseInterface $interface)
    {
        $this->websiteManager = $interface;

        return $this;
    }

    /**
     * @return array|null
     */
    public
    function getParsedSitemap(): ?array
    {
        if ($this->parsedSitemap === null) {
            $this->parseSitemap();
        }

        return $this->parsedSitemap;
    }

    /**
     *
     */
    public
    function insertDataToDatabase(): void
    {
        $website = $this->insertWebsiteToDatabase();
        if ($website) {
            $this->insertPagesToDatabase($website);
        }
    }

    private
    function insertWebsiteToDatabase()
    {
        return $this->websiteManager->insertWebpage($this->parsedSitemap['hostname']);

    }

    private
    function insertPagesToDatabase(
        $parentWebsite
    ): void {
        foreach ($this->parsedSitemap['pages'] as $page) {
            if ($page !== '') {
                $this->websiteManager->insertPage($parentWebsite, $page);
            }
        }
    }

    public
    function loadFromFile(
        $filename
    ): SitemapParser {
        $stream = new XmlFileStream($filename);

        return $this->loadFromStream($stream);
    }

    /**
     * @param $stream
     *
     * @return $this
     */
    public
    function loadFromStream(
        $stream
    ): self {
        $this->stream = $stream;
        $this->parseSitemap();

        return $this;
    }

    private
    function parseSitemap(): void
    {
        $this->parsedSitemap = $this->sitemapXmlParser
            ->setStream($this->stream)
            ->setXmlParser($this->xmlParser)
            ->getWebsiteData();
    }
}