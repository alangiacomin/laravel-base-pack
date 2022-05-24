<?php

namespace Alangiacomin\LaravelBasePack\Middleware;

use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonResponse
{
    /**
     * @var Response The request response
     */
    private Response $response;

    /**
     * JsonMiddleware constructor.
     *
     * @param  ResponseFactory  $factory
     */
    public function __construct(
        protected ResponseFactory $factory
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return HttpJsonResponse
     */
    public function handle(Request $request, Closure $next): HttpJsonResponse
    {
        $this->response = $next($request);

        $data = null;
        $errors = null;
        $success = $this->response->isSuccessful();

        if ($success)
        {
            $data = $this->getResponseContent();
        }
        else
        {
            if ($this->response->isServerError())
            {
                $errors = $this->response->exception->getMessage();
            }
            else
            {
                $errors = $this->getResponseContent();
            }
        }

        return $this->factory->json(
            [
                'success' => $success,
                'data' => $data,
                'errors' => $errors,
            ]
        );
    }

    /**
     * Gets the response content as decoded json or original value
     *
     * @return false|mixed|string
     */
    private function getResponseContent(): mixed
    {
        $responseContent = $this->response->getContent();

        $content = json_decode($responseContent);
        if (!isset($content))
        {
            $content = $responseContent;
        }

        return $content;
    }
}
