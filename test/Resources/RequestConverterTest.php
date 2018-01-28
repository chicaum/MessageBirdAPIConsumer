<?php

declare(strict_types=1);

namespace App\Resources;

use MessageBird\Objects\Message;
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

        static::assertInstanceOf(Message::class, $messageRequest);
    }

    /**
     * @expectedException App\Exception\BadRequestException
     * @expectedExceptionMessage Invalid json object
     */
    public function testConvertFails()
    {
        $jsonObject = 'invalid json object';
        $request = new Request([], [], [], [], [], [], $jsonObject);

        $this->requestConverter->convert($request);
    }

    /**
     * @expectedException App\Exception\BadRequestException
     * @expectedExceptionMessage 'recipient' field is required
     */
    public function testConvertFailsWithoutRecipient()
    {
        $jsonObject = '{"originator":"MessageBird","message":"This is a test message."}';
        $request = new Request([], [], [], [], [], [], $jsonObject);

        $this->requestConverter->convert($request);
    }

    /**
     * @expectedException App\Exception\BadRequestException
     * @expectedExceptionMessage 'originator' field is required
     */
    public function testConvertFailsWithoutOriginator()
    {
        $jsonObject = '{"recipient":"31612345678","message":"This is a test message."}';
        $request = new Request([], [], [], [], [], [], $jsonObject);

        $this->requestConverter->convert($request);
    }

    /**
     * @expectedException App\Exception\BadRequestException
     * @expectedExceptionMessage 'message' field is required
     */
    public function testConvertFailsWithoutMessage()
    {
        $jsonObject = '{"recipient":31612345678,"originator":"MessageBird"}';
        $request = new Request([], [], [], [], [], [], $jsonObject);

        $this->requestConverter->convert($request);
    }
}
