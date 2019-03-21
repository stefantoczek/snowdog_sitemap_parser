<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 19.03.19
 * Time: 12:27
 */

namespace Stefantoczek\SitemapParser\Parser;

use Stefantoczek\SitemapParser\Interfaces\XmlDataStreamInterface;
use Stefantoczek\SitemapParser\Interfaces\XmlParserInterface;

class SitemapXmlParser
{
    /** @var XmlParserInterface $xmlParser */
    private $xmlParser;

    /** @var string $rawData */
    private $rawData;

    /** @var array $parsedXmlData */
    private $parsedXmlData;
    /** @var \Stefantoczek\SitemapParser\Interfaces\XmlDataStreamInterface */
    private $dataStream;

    /** @var array $websiteData */
    private $websiteData;

    public function __construct()
    {
        $this->websiteData =
            [
                'hostname' => null,
                'pages' => [],
            ];
    }

    /**
     * returns raw data from stream
     *
     * @return string
     */
    public function getRawData(): string
    {
        if ($this->rawData === null) {
            $this->rawData = $this->dataStream->read();
        }

        return $this->rawData;
    }

    /**
     * @return mixed
     */
    public function getParsedXmlData()
    {
        if ($this->parsedXmlData === null) {
            $this->parsedXmlData = $this->xmlParser
                ->setData($this->getRawData())
                ->getParsedData();
        }

        return $this->parsedXmlData;
    }

    public function getWebsiteData(): array
    {
        $this->getParsedXmlData();

        foreach ($this->parsedXmlData as $item) {
            $this->parseSingleItem($item);
        }

        return $this->websiteData;
    }

    private function parseSingleItem($item): void
    {
        if ($this->websiteData['hostname'] === null) {
            $this->websiteData['hostname'] = parse_url((string)$item->loc, PHP_URL_HOST);
        }
        $queryString = parse_url((string)$item->loc, PHP_URL_PATH);
        if ($queryString === null) {
            $queryString = '';
        }

        $this->websiteData['pages'] [] = ltrim($queryString, '/');
    }

    /**
     * @param \Stefantoczek\SitemapParser\Interfaces\XmlDataStreamInterface $dataStream
     *
     * @return \Stefantoczek\SitemapParser\Parser\SitemapXmlParser
     */
    public function setStream(XmlDataStreamInterface $dataStream): SitemapXmlParser
    {
        $this->dataStream = $dataStream;

        return $this;
    }

    /**
     * @param \Stefantoczek\SitemapParser\Interfaces\XmlParserInterface $xmlParser
     *
     * @return \Stefantoczek\SitemapParser\Parser\SitemapXmlParser
     */
    public function setXmlParser(XmlParserInterface $xmlParser): SitemapXmlParser
    {
        $this->xmlParser = $xmlParser;

        return $this;
    }
}