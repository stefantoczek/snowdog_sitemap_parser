<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 19.03.19
 * Time: 11:46
 */

namespace Stefantoczek\SitemapParser;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
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
    private $pageManager;

    private $user;

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
    }

    /**
     * @return array|null
     */
    public
    function getParsedSitemap()
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
    function insertDataToDatabase()
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
    ) {
        foreach ($this->parsedSitemap['pages'] as $page) {
            if ($page !== '') {
                $this->websiteManager->insertPage($parentWebsite, $page);
            }
        }
    }

    public
    function loadFromFile(
        $filename
    ) {
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
    ) {
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