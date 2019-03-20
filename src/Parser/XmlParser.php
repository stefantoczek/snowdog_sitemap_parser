<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 19.03.19
 * Time: 12:27
 */

namespace Stefantoczek\SitemapParser\Parser;

use Stefantoczek\SitemapParser\Interfaces\XmlParserInterface;

class XmlParser implements XmlParserInterface
{
    private $rawData;
    private $parsedXmlData;

    public function __construct()
    {
        libxml_use_internal_errors(true);
    }

    /**
     * @param $xmlData
     *
     * @return $this|mixed
     */
    public function setData($xmlData)
    {
        if ($this->rawData !== $xmlData) {
            $this->rawData = $xmlData;
            $this->parsedXmlData = null;
        }

        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getParsedData()
    {
        if ($this->parsedXmlData === null) {
            $this->parseRawData();
        }

        return $this->parsedXmlData;
    }

    /**
     * @throws \Exception
     */
    private function parseRawData()
    {
        $this->parsedXmlData = simplexml_load_string($this->rawData);
        if ($this->parsedXmlData === false) {
            $this->handleRuntimeError();
        }

    }

    private function handleRuntimeError()
    {
        $errorString = 'Error occured while parsing XML file, please validate it & try again!';

        $errors = libxml_get_errors();
        if (count($errors) > 0) {
            $errorString = implode('|', $errors);
        }

        throw new \RuntimeException($errorString, -1);
    }
}