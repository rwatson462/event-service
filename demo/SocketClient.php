<?php

class SocketClient implements HttpClientInterface
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
        $urlParts = $this->processUrl($url);

        $headerString = '';
        foreach($headers as $key => $value) {
            $headerString .= "$key: $value\r\n";
        }

        $packet = "{$method} {$urlParts['url']} HTTP/1.1\r\n"
                . "Host: {$urlParts['host']}\r\n"
                . $headerString
                . "Connection: close\r\n\r\n"
                . $content;

        $errorCode = 0;
        $errorMsg = '';
        $handle = fsockopen($urlParts['host'], $urlParts['port'], $errorCode, $errorMsg, 1.0);

        if($errorCode !== 0) {
            throw new Exception("[$errorCode] $errorMsg");
        }

        fwrite($handle, $packet);

        $response = '';
        while(!feof($handle)) {
            $response .= fgets($handle, 2048);
        }
        fclose($handle);

        return $response;
        return $this->processResponse($response);
    }

    protected function processResponse(string $response): array
    {
        [$headerString, $body] = explode("\r\n\r\n", $response);

        $rawHeaders = explode("\r\n", $headerString);
        $headers = [];
        foreach($rawHeaders as $header) {
            if(strpos($header, ':') !== false) {
                $header = explode(':', strtolower($header));
                $headers[$header[0]] = trim($header[1]);
            }
        }
        unset($header);

        if(array_key_exists('content-type', $headers)) {
            // process different content types
            $body = match($headers['content-type']) {
                'application/json' => json_decode($body, true),
                default => $body
            };
        }

        return [
            'headers' => $headers,
            'body' => $body
        ];
    }

    protected function processUrl(string $url): array
    {
        $transport = 'http://';
        if(strpos($url, '//') !== false) {
            $transport = substr($url, 0, strpos($url, '//')+2);
            $url = substr($url, strlen($transport));
        }

        $host = '';
        if(strpos($url, '/') !== false) {
            $host = substr($url, 0, strpos($url,'/'));
            $url = substr($url,strpos($url, '/'));
        }

        $port = 80;
        if(strrpos($host, ':') !== false) {
            $port = substr($host, strpos($host, ':')+1);
            $host = substr($host, 0, strpos($host, ':'));
        }

        return [
            'transport' => $transport,
            'host' => $host,
            'port' => $port,
            'url' => $url
        ];
    }
}