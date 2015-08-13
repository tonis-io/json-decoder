<?php

namespace Tonis\JsonDecoder;

use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;
use Zend\Diactoros\ServerRequest;

/**
 * @covers \Tonis\JsonDecoder\JsonDecoder
 */
class JsonDecoderTest extends \PHPUnit_Framework_TestCase
{
    public function createRequest($data, $contentType = 'application/json')
    {
        $json = json_encode($data);
        $request = new ServerRequest();

        $stream = fopen('php://memory','r+');
        fwrite($stream, $json);
        rewind($stream);

        $request = $request->withBody(new Stream($stream));
        $request = $request->withHeader('Content-Type', $contentType);
        return $request;
    }

    public function testInvokeDefaults()
    {
        $data = ['x' => 123, 'y' => ['a','b','c']];
        $request = $this->createRequest($data);

        $response = new Response();
        $decoder = new JsonDecoder();

        $request = $decoder(
            $request,
            $response,
            function ($request, $response) {
                return $request;
            }
        );

        $this->assertInstanceOf(ServerRequest::class, $request);
        $this->assertEquals($data, $request->getParsedBody());
    }

    public function testInvokeSpecialContentType()
    {
        $contentType = 'application/x-special-json';
        $data = ['x' => 345, 'y' => ['a','b','c']];
        $request = $this->createRequest($data, $contentType);

        $response = new Response();
        $decoder = new JsonDecoder(['content-types' => [$contentType]]);

        $request = $decoder(
            $request,
            $response,
            function ($request, $response) {
                return $request;
            }
        );

        $this->assertInstanceOf(ServerRequest::class, $request);
        $this->assertEquals($data, $request->getParsedBody());
    }

    public function testInvokeSeparator()
    {
        $contentType = 'application/x-special-json';
        $data = ['o' => 345, 'p' => ['a','b','c']];
        $request = $this->createRequest($data, 'xyz;def;' . $contentType);

        $response = new Response();
        $decoder = new JsonDecoder(['content-types' => [$contentType]]);

        $request = $decoder(
            $request,
            $response,
            function ($request, $response) {
                return $request;
            }
        );

        $this->assertInstanceOf(ServerRequest::class, $request);
        $this->assertEquals($data, $request->getParsedBody());
    }

    public function testInvokeNoMatchingContentType()
    {
        $data = ['x' => 567, 'y' => 'abc'];
        $request = $this->createRequest($data, 'application/text');

        $response = new Response();
        $decoder = new JsonDecoder();

        $request = $decoder(
            $request,
            $response,
            function ($request, $response) {
                return $request;
            }
        );

        $this->assertInstanceOf(ServerRequest::class, $request);
        $this->assertEquals(null, $request->getParsedBody());
    }

    public function testInvokeSpecialSeparator()
    {
        $data = ['data' => ['test']];
        $request = $this->createRequest($data, 'xyz,abc,application/json');

        $response = new Response();
        $decoder = new JsonDecoder(['separator' => ',']);

        $request = $decoder(
            $request,
            $response,
            function ($request, $response) {
                return $request;
            }
        );

        $this->assertInstanceOf(ServerRequest::class, $request);
        $this->assertEquals($data, $request->getParsedBody());
    }
}
