<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 19.03.19
 * Time: 11:57
 */

namespace Stefantoczek\SitemapParser\Interfaces;

interface XmlDataStreamInterface
{
    /**
     * initializes datastream
     *
     * @param $streamName string
     *
     * @return mixed
     */
    public function initialize($streamName);

    /**
     * returns data read from stream
     *sitemap_import.phtml
     *
     * @return string|null
     */
    public function read(): ?string;
}