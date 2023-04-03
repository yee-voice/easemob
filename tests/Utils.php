<?php
namespace tests;

class Utils
{
    public static function randomUserName()
    {
        return sprintf("it-%d-%d", mt_rand(), microtime(true));
    }

    public static function randomPassword()
    {
        return sprintf("it-password-%d-%d", mt_rand(), microtime(true));
    }
}
