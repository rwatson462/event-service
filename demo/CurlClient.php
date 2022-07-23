<?php

class CurlClient implements HttpClientInterface
{
    public function get(string $url, array $headers = []): string
    {
        return $this->send($url, '', $headers, 'GET');
    }

    public function post(string $url, string $content = '', array $headers = []): string
    {
        $headers['content-length'] = strlen($content);
        return $this->send($url, $content, $headers, 'POST');
    }

    protected function send(string $url, string $content, array $headers, string $method): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}