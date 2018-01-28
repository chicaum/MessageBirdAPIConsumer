<?php

declare(strict_types=1);

namespace App\Resources;

use App\Entity\MessageRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RequestConverterTest extends TestCase
{
    private $requestConverter;

    public function setUp()
    {
        $this->requestConverter = new RequestConverter();
    }

    public function testConvert()
    {
        $jsonObject = '{"recipient":31612345678,"originator":"MessageBird","message":"This is a test message."}';
        $request = new Request([], [], [], [], [], [], $jsonObject);

        $messageRequest = $this->requestConverter->convert($request);

        static::assertInstanceOf(MessageRequest::class, $messageRequest);
    }

    /**
     * @expectedException App\Exception\BadRequestException
     */
    public function testConvertFails()
    {
        $jsonObject = 'invalid json object';
        $request = new Request([], [], [], [], [], [], $jsonObject);

        $this->requestConverter->convert($request);
    }
}
