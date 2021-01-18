<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\Handler;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use Mockery\Container;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @test
     * @return void
     */
    public function it_converts_an_exception_into_json_api_spec_error_response()
    {
        /**
         * @var Handler $handler
         */
        $handler = app(Handler::class);
        $request = Request::create('/test/', Request::METHOD_GET);
        $request->headers->set('accept','application/vnd.api+json');

        $exception = new \Exception('Test Exception');

        $response = $handler->render($request, $exception);
        TestResponse::fromBaseResponse($response)
            ->assertJson([
                'errors' => [
                    [
                        'title' => 'Exception',
                        'details' => 'Test Exception'
                    ]
                ]
            ]);
    }
}
