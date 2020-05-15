<?php
declare(strict_types=1);
namespace Wwwision\GraphQlDemo;

use Neos\Flow\Http\Component\ComponentChain;
use Neos\Flow\Http\Component\ComponentContext;
use Neos\Flow\Http\Component\ComponentInterface;

final class CorsComponent implements ComponentInterface
{

    public function handle(ComponentContext $componentContext): void
    {
        $request = $componentContext->getHttpRequest();
        $response = $componentContext->getHttpResponse();

        $response = $response->withAddedHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withAddedHeader('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
        $response = $response->withAddedHeader('Access-Control-Allow-Headers', 'Content-Type');
        if ($request->getMethod() === 'OPTIONS') {
            // 204 = no content
            $response = $response->withStatus(204);
            $componentContext->setParameter(ComponentChain::class, 'cancel', true);
        }
        $componentContext->replaceHttpResponse($response);
    }
}
