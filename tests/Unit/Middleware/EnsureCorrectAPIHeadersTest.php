<?php


namespace Middleware;


use App\Http\Middleware\EnsureCorrectAPIHeaders;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class EnsureCorrectAPIHeadersTest extends TestCase
{
    /**
     * @test
     */
    public function it_aborts_request_if_accept_header_does_not_adhere_to_json_api_spec()
    {
        $request = Request::create('/test', Request::METHOD_GET);
        $middleware = new EnsureCorrectAPIHeaders();

        /**
         * @var Response $response
         */
        $response = $middleware->handle($request, function ($request) {
            $this->fail('Did not');
        });
        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $response->status());
    }

    /**
     *
     * @test
     */
    public function it_accept_request_if_accept_header_adheres_to_json_api_spec()
    {
        $request = Request::create('/test', Request::METHOD_GET);
        $request->headers->set('accept','application/vnd.api+json');

        $middleware = new EnsureCorrectAPIHeaders();

        $response = $middleware->handle($request, function ($req) {
            return new Response();
        });

        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    /**
     * @test
     */
    public function it_aborts_post_request_if_content_type_header_does_nothere_to_json_api_spec()
    {
        $request = Request::create('/test', Request::METHOD_POST);
        $request->headers->set('accept','application/vnd.api+json');
        $middleware = new EnsureCorrectAPIHeaders();

        /**
         * @var Response $response
         */
        $response = $middleware->handle($request, function ($req) {
            $this->fail('Did not abort request because og invalid Content-Type Header');
        });

        $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->status());
    }

    /**
     * @test
     */
    public function it_aborts_patch_request_if_content_type_header_does_not_adhere_to_json_api_spec()
    {
        $request = Request::create('/test', Request::METHOD_PATCH);
        $request->headers->set('accept','application/vnd.api+json');
        $middleware = new EnsureCorrectAPIHeaders();

        /**
         * @var Response $response
         */
        $response = $middleware->handle($request, function ($req) {
            $this->fail('Did not abort request because og invalid Content-Type Header');
        });

        $this->assertEquals(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $response->status());
    }

    /**
     * @test
     */
    public function it_accepts_post_request_if_content_type_header_adheres_to_json_api_spec()
    {
        $request = Request::create('/test', Request::METHOD_POST);
        $request->headers->set('accept','application/vnd.api+json');
        $request->headers->set('content-type','application/vnd.api+json');
        $middleware = new EnsureCorrectAPIHeaders();

        /**
         * @var Response $response
         */
        $response = $middleware->handle($request, function ($req) {
            return new Response();
        });

        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    /**
     * @test
     */
    public function it_accepts_patch_request_if_content_type_header_adheres_to_json_api_spec()
    {
        $request = Request::create('/test', Request::METHOD_PATCH);
        $request->headers->set('accept','application/vnd.api+json');
        $request->headers->set('content-type','application/vnd.api+json');
        $middleware = new EnsureCorrectAPIHeaders();

        /**
         * @var Response $response
         */
        $response = $middleware->handle($request, function ($req) {
            return new Response();
        });

        $this->assertEquals(Response::HTTP_OK, $response->status());
    }

    /**
     * @test
     */
    public function when_aborting_for_a_missing_accept_header_thw_correct_content_type_header_is()
    {
        $request = Request::create('/test', Request::METHOD_GET);
        $request->headers->set('accept','application/vnd.api+json');
//        $request->headers->set('content-type','application/vnd.api+json');
        $middleware = new EnsureCorrectAPIHeaders();

        /**
         * @var Response $response
         */
        $response = $middleware->handle($request, function ($req) {
            return new Response();
        });

        $this->assertEquals($response->status(), Response::HTTP_NOT_ACCEPTABLE);
        $this->assertEquals('application/vnd.api+json', $response->headers->get('content-type'));
    }
}
