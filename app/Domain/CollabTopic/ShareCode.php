<?php

namespace App\Domain\CollabTopic;

class ShareCode
{
    const SHARE_CODE_CHARS = '23456789abcdefghjkmnpqrstuvwxyz';
    const CODE_LENGTH = 8;
    public function generateCode()
    {
        $maxLength = self::CODE_LENGTH;
        $characters = self::SHARE_CODE_CHARS;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $maxLength; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
