<?php

namespace App\Services;

class CurlService
{
    private $url;
    private $queryParams = [];
    private $options = [];
    private $response = [
        'data' => false,
        'statusCode' => 500
    ];

    public function __construct(string $url, array $queryParams = [], array $options = [])
    {
        $this->url = $url;
        $this->queryParams = $queryParams;
        $this->options = $options;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function queryParams(): array
    {
        return $this->queryParams;
    }

    public function options(): array
    {
        return $this->options;
    }

    public function response(): array
    {
        return $this->response;
    }

    public function get(): CurlService
    {
        $url  = $this->url();
        $url .= (false === strpos($url, '?') ? '?' : '');
        $url .= http_build_query($this->queryParams());

        $defaults = [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 4
        ];

        $ch = curl_init();
        curl_setopt_array($ch, ($this->options() + $defaults));
        $data = curl_exec($ch);

        if(empty($data))
        {
            return $this;
        }

        $this->response = [
            'data' => $data,
            'statusCode' => curl_getinfo($ch, CURLINFO_HTTP_CODE)
        ];

        curl_close($ch);

        return $this;
    }

    
}