<?php

namespace App\Service\System\HttpClient\Request;

class Request implements RequestInterface
{
    private ?array $data = null;

    private string $scenario;

    private string $token;

    private string $method;

    private ?string $responseClassName;

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getScenario(): string
    {
        return $this->scenario;
    }

    public function setScenario(string $scenario): self
    {
        $this->scenario = $scenario;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getResponseClassName(): ?string
    {
        return $this->responseClassName;
    }

    public function setResponseClassName(?string $responseClassName): self
    {
        $this->responseClassName = $responseClassName;

        return $this;
    }
}
