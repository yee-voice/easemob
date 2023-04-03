<?php
namespace tests;

class AttachmentTest extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testAttachmentUploadDownload()
    {
        $data = $this->attachment->uploadFile(dirname(__FILE__).'/assets/1.png');
        $this->assertArrayHasKey('uuid', $data);

        $this->assertIsInt($this->attachment->downloadFile(dirname(__FILE__).'/assets/11.png', $data['uuid'], $data['share-secret']));
        $this->assertIsInt($this->attachment->downloadThumb(dirname(__FILE__).'/assets/11_thumb.png', $data['uuid'], $data['share-secret']));
    }
}
