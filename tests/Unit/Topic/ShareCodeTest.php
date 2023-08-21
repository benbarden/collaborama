<?php

namespace Tests\Unit\Topic;

use App\Domain\CollabTopic\ShareCode;

use PHPUnit\Framework\TestCase;

class ShareCodeTest extends TestCase
{
    public function test_basic_generation(): void
    {
        $shareCode = new ShareCode();

        $generatedCode = $shareCode->generateCode();

        $this->assertEquals(ShareCode::CODE_LENGTH, strlen($generatedCode));
    }
}
