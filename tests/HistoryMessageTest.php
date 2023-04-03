<?php
namespace tests;

class HistoryMessageTest extends Base
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('PRC');
    }

    /* 
    // 发送消息一小时后再测试
    public function testHistoryMsgGetAsUri()
    {
        $this->assertIsNotArray($this->message->getHistoryAsUri(date('YmdH', time() - 60*60)));
    }

    public function testHistoryMsgGetAsLocalFile()
    {
        $this->assertIsNotArray($this->assertIsString($this->message->getHistoryAsUri(date('YmdH', time() - 60*60))));
    }
     */
}
