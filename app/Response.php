<?php

namespace App;

class Response
{
    protected $body;

    protected $statusCode = 200;

    protected $headers = [];

    public function setBody($body): Response
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withStatus($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withHeader($name, $value) {
        $this->headers[] = [$name, $value];
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function renderView($fileName, $data = []) {

        $filePath = 'Views/' . $fileName . '.php';

        ob_start();

        $output = '';
        include 'Components/Header.php';
        include $filePath;
        include 'Components/Footer.php';
        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }
}