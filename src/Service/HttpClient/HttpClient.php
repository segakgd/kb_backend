<?php

namespace App\Service\HttpClient;

use App\Service\HttpClient\Request\Request;
use App\Service\HttpClient\Request\RequestInterface;
use App\Service\HttpClient\Response\Response;
use App\Service\HttpClient\Response\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class HttpClient implements HttpClientInterface
{
    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger,
    ) {}

    public static function buildRequest(
        string $method,
        string $scenario,
        string $token,
        ?array $data = null,
        ?string $responseClassName = null,
    ): Request {
        return (new Request())
            ->setMethod($method)
            ->setScenario($scenario)
            ->setToken($token)
            ->setData($data)
            ->setResponseClassName($responseClassName);
    }

    /**
     * @throws Exception
     */
    public function request(RequestInterface $request): ResponseInterface
    {
        $token = $request->getToken();
        $scenario = $request->getScenario();

        $uri = "https://api.telegram.org/bot$token/$scenario";
        $responseArray = $this->curlRequest($uri, $request->getData() ?? [], $request->getMethod());

        $code = 400;

        if (isset($responseArray['ok'])) {
            $code = $responseArray['ok'] === true ? 200 : 400;
        }

        $description = $responseArray['description'] ?? '';

        $result['result'] = $responseArray['result'] ?? [];

        $result['code'] = $code;
        $result['description'] = $description;

        $responseClassName = $request->getResponseClassName();

        if ($code == 400) {
            $exception = new Exception("HTTP error! For uri: $uri \n Result data: " . json_encode($result));

            $this->logger->error($exception);

            throw $exception;
        }

        if ($responseClassName) {
            return $this->serializer->denormalize($result, $responseClassName, 'json');
        }

        return new Response($code, $description);
    }

    private function curlRequest(string $uri, array $requestArray, string $method): array
    {
        if ($method === self::METHOD_GET) {
            $ch = curl_init($uri . http_build_query($requestArray));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);

            $response = curl_exec($ch);

            curl_close($ch);
        } elseif ($method === self::METHOD_POST) {
            $ch = curl_init($uri);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestArray, '', '&'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);

            $response = curl_exec($ch);

            curl_close($ch);
        }

        return json_decode($response ?? '', true) ?? [];
    }
}
