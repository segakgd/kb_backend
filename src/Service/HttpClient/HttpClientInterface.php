<?php

namespace App\Service\HttpClient;

use App\Service\HttpClient\Request\RequestInterface;
use App\Service\HttpClient\Response\ResponseInterface;

interface HttpClientInterface
{
    public function request(RequestInterface $request): ResponseInterface;
}