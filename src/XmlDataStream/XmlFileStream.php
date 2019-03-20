<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 19.03.19
 * Time: 12:11
 */

namespace Stefantoczek\SitemapParser\XmlDataStream;

use Stefantoczek\SitemapParser\Interfaces\XmlDataStreamInterface;

class XmlFileStream implements XmlDataStreamInterface
{
    /** @var string $filename */
    private $fileName;

    private $dataBuffer;

    /**
     * XmlFileStream constructor.
     *
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->initialize($fileName);
    }

    /**
     * @param $streamName
     *
     * @return mixed|void
     */
    public function initialize($streamName)
    {
        $this->fileName = $streamName;
        $this->dataBuffer = null;
    }

    /**
     * @return mixed|null
     */
    public function read()
    {
        if ($this->dataBuffer === null) {
            $this->loadFileData();
        }

        return $this->dataBuffer;
    }

    private function loadFileData()
    {
        $this->dataBuffer = file_get_contents($this->fileName);
    }

}