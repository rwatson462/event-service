<?php

interface HttpClientInterface
{
    public function get(string $url, array $headers = []): string;
    public function post(string $url, string $content = '', array $headers = []): string;
}