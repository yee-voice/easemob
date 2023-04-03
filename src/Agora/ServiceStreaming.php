<?php
namespace Easemob\Agora;

/**
 * @ignore
 */
class ServiceStreaming extends Service
{
    const SERVICE_TYPE = 3;
    const PRIVILEGE_PUBLISH_MIX_STREAM = 1;
    const PRIVILEGE_PUBLISH_RAW_STREAM = 2;
    public $channelName;
    public $uid;

    public function __construct($channelName = "", $uid = "")
    {
        parent::__construct(self::SERVICE_TYPE);
        $this->channelName = $channelName;
        $this->uid = $uid;
    }

    public function pack()
    {
        return parent::pack() . Util::packString($this->channelName) . Util::packString($this->uid);
    }

    public function unpack(&$data)
    {
        parent::unpack($data);
        $this->channelName = Util::unpackString($data);
        $this->uid = Util::unpackString($data);
    }
}
