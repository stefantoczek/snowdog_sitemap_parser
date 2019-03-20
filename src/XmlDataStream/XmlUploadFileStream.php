<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.03.19
 * Time: 08:59
 */

namespace Stefantoczek\SitemapParser\XmlDataStream;

use Stefantoczek\SitemapParser\Interfaces\XmlDataStreamInterface;

class XmlUploadFileStream implements XmlDataStreamInterface
{
    /** @var array $uploadedFile */
    private $uploadedFile;
    /** @var bool $isValidFile */
    private $isValidFile;
    /** @var string $dataBuffer */
    private $dataBuffer;

    public function __construct($uploadedFile)
    {
        $this->initialize($uploadedFile);

        if ($this->uploadedFile['error'] > 0) {
            throw new \RuntimeException('FILE UPLOAD ERROR: ' . $this->uploadedFile['error']);
        }
        if ($this->uploadedFile['type'] !== 'text/xml') {
            throw new \RuntimeException('FILE IS NOT XML FILE');
        }
        $this->isValidFile = true;
    }

    public function read()
    {
        if ($this->dataBuffer === null && $this->isValidFile) {
            $this->loadUploadedFileData();
        }

        return $this->dataBuffer;
    }

    public function initialize($fileUploadEntry)
    {
        $this->uploadedFile = $fileUploadEntry;
        $this->dataBuffer = null;
    }

    private function loadUploadedFileData()
    {
        $this->dataBuffer = file_get_contents($this->uploadedFile['tmp_name']);
    }
}
