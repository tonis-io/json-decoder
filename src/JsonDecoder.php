<?php
namespace Tonis\JsonDecoder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class JsonDecoder
{
    /** @var array */
    private $config;

    /**
     * Middleware to json decode the request body
     *
     * - content-types: Array of content-type strings in the header that will cause the body to be json decoded
     * - separator: The separator string used for multiple header values. Defaults to a semi-colon.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $defaults = [
            'content-types' => ['application/json'],
            'separator' => ';'
        ];
        $this->config = array_merge($defaults, $config);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $contentType = $request->getHeader('Content-Type');
        if (!empty($contentType)) {
            $contentType = array_shift($contentType);
            $types = explode($this->config['separator'], $contentType);
            foreach ($types as $type) {
                $type = trim($type);
                if (in_array($type, $this->config['content-types'])) {
                    $jsonDecoded = json_decode($request->getBody()->getContents(), true);
                    $request = $request->withParsedBody($jsonDecoded);
                    break;
                }
            }
        }
        return $next($request, $response);
    }
}
