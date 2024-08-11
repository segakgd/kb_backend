<?php

namespace App\Service\HttpClient\Request;

use App\Service\HttpClient\RequestMethodEnum;

interface RequestInterface
{
    public function getData(): ?array;

    public function setData(?array $data): self;

    public function getScenario(): string;

    public function setScenario(string $scenario): self;

    public function getToken(): string;

    public function setToken(string $token): self;

    public function getMethod(): RequestMethodEnum;

    public function setMethod(RequestMethodEnum $method): self;

    public function getResponseClassName(): ?string;

    public function setResponseClassName(?string $responseClassName): self;
}
