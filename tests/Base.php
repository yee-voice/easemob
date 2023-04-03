<?php
namespace tests;

use PHPUnit\Framework\TestCase;
use Easemob\Auth;
use Easemob\User;
use Easemob\Contact;
use Easemob\Block;
use Easemob\Message;
use Easemob\UserMetadata;
use Easemob\Push;
use Easemob\Attachment;
use Easemob\Group;
use Easemob\Room;

class Base extends TestCase
{
    public $auth;
    public $user;
    public $contact;
    public $block;
    public $message;
    public $metadata;
    public $push;
    public $attachment;
    public $group;
    public $room;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new Auth("1121230223208734#demo", "YXA6O7lq4CjzRS6NwmBEXuaXsA", "YXA6Rg6Wy6wLokBfHJ09u8jiCDq4kMc");
        // 设置 Rest Api 域名
        // $this->auth->setApiUri('http://a1-hsb.easemob.com');
        $this->user = new User($this->auth);
        $this->contact = new Contact($this->auth);
        $this->block = new Block($this->auth);
        $this->message = new Message($this->auth);
        $this->metadata = new UserMetadata($this->auth);
        $this->push = new Push($this->auth);
        $this->attachment = new Attachment($this->auth);
        $this->group = new Group($this->auth);
        $this->room = new Room($this->auth);
    }
}