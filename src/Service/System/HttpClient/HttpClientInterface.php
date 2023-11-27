<?php

namespace App\Service\System\HttpClient;

use App\Service\System\HttpClient\Request\RequestInterface;
use App\Service\System\HttpClient\Response\ResponseInterface;

interface HttpClientInterface
{
    public function request(RequestInterface $request): ResponseInterface;
}