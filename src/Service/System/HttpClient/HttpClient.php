<?php

namespace App\Service\System\HttpClient;

use App\Service\System\HttpClient\Request\RequestInterface;
use App\Service\System\HttpClient\Response\Response;
use App\Service\System\HttpClient\Response\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;

class HttpClient implements HttpClientInterface
{
    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';

    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

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
            dd('HTTP error!', $result, $request);
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
