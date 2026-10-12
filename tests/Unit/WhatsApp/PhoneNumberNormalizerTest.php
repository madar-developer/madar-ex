<?php

namespace Tests\Unit\WhatsApp;

use App\Exceptions\WhatsAppException;
use App\Services\WhatsApp\PhoneNumberNormalizer;
use PHPUnit\Framework\TestCase;

class PhoneNumberNormalizerTest extends TestCase
{
    protected PhoneNumberNormalizer $normalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->normalizer = new PhoneNumberNormalizer();
    }

    public function testNormalizesE164WithPlus(): void
    {
        $this->assertSame('966538825803', $this->normalizer->normalize('+966 538 825 803'));
    }

    public function testNormalizesInternationalPrefix00(): void
    {
        $this->assertSame('201012345678', $this->normalizer->normalize('00201012345678'));
    }

    public function testRejectsLocalNumberWithoutCountryCode(): void
    {
        $this->expectException(WhatsAppException::class);
        $this->normalizer->normalize('0538825803');
    }

    public function testRejectsEmptyPhone(): void
    {
        $this->expectException(WhatsAppException::class);
        $this->normalizer->normalize('   ');
    }
}
