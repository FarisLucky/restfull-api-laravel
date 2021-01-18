<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class EnsureCorrectAPIHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->headers->get("Accept") !== 'application/vnd.api+json') {
            $resp = new Response('', Response::HTTP_NOT_ACCEPTABLE);
            return $this->addCorrectContentType($resp);
        }

        if ($request->isMethod(Request::METHOD_PATCH) ||
            $request->isMethod(Request::METHOD_POST)) {
            if ($request->header('content-Type') !== 'application/vnd.api+json') {
                return new Response('', Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
            }
        }

        return $this->addCorrectContentType($next($request));
    }

    private function addCorrectContentType(BaseResponse $response)
    {
        $response->headers->set('content-type','application/vnd.api+json');
        return $response;
    }
}
