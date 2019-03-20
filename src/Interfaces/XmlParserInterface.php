<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 19.03.19
 * Time: 11:54
 */

namespace Stefantoczek\SitemapParser\Interfaces;

interface XmlParserInterface
{
    /**
     * @param $xmlData
     *
     * @return mixed
     */
    public function setData($xmlData);

    /**
     * parses & returns parsed data
     *
     * @return array
     */
    public function getParsedData();
}