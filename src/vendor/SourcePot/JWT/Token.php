<?php

namespace SourcePot\JWT;

use SourcePot\JWT\Exception\InvalidTokenException;
use SourcePot\JWT\Exception\TamperedTokenException;
use SourcePot\Util\Base64Url;
use SourcePot\Util\JSON;

class Token
{
    /**
        * 'typ' is always JWT for these
        * 'alg' is always HS256 for this implementation
        */
    private array $header = [
        'typ' => 'JWT',
        'alg' => 'HS256',
    ];
    private array $payload = [];
    private string $signature = '';

    // Expiry timestamp of this token
    private int $expiry = 0;

    private function __construct()
    {
    }

    public static function create(?array $header = null, ?array $payload = null): self
    {
        $token = new self;

        if($header !== null) {
            $token->setHeader($header);
        }
        if($payload !== null) {
            $token->setPayload($payload);
        }

        return $token;
    }

    public static function from(string $token): self
    {
        $tokenParts = explode('.', $token);
        if(count($tokenParts) !== 3) {
            throw new InvalidTokenException('Token does not consist of exactly 3 parts');
        }
        
        [$header, $payload, $signature] = $tokenParts;

        $header = JSON::parse(Base64Url::decode($header));
        $payload = JSON::parse(Base64Url::decode($payload));
        $signature = Base64Url::decode($signature);

        $token = self::create($header,$payload);
        $token->setSignature($signature);
        return $token;
    }

    public function setExpiry(int $exp): self
    {
        $this->expiry = $exp;
        return $this;
    }

    public function getExpiry(): int
    {
        return $this->expiry;
    }

    public function setPayload(array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    public function getPayload(): array
    {
        // return a copy of the payload to prevent accidental tampering
        return [...$this->payload];
    }

    public function setHeader(array $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function getHeader(): array
    {
        // return a copy of the header to prevent accidental tampering
        return [...$this->header];
    }

    public function setSignature(string $signature): self
    {
        $this->signature = $signature;
        return $this;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    protected function getEncodedHeader(): string
    {
        $header = $this->header;
        if($this->expiry > 0) {
            $header['exp'] = $this->expiry;
        }

        return Base64Url::encode(JSON::stringify($header));
    }

    protected function getEncodedPayload(): string
    {
        return Base64Url::encode(JSON::stringify($this->payload));
    }

    protected function getEncodedSignature(): string
    {
        return Base64Url::encode($this->signature);
    }

    public function sign(string $secret): self
    {
        $this->signature = $this->generateSignature($secret);
        return $this;
    }

    public function validate(string $secret): bool
    {
        $signature = $this->generateSignature($secret);
        if($this->getSignature() !== $signature) {
            throw new TamperedTokenException(
                'Invalid token! After signing, new signature does not match original'
            );
        }
        return true;
    }
    
    protected function generateSignature(string $secret): string
    {
        return hash_hmac(
            'sha256', 
            $this->getEncodedHeader() . '.' . $this->getEncodedPayload(), 
            $secret
        );
    }

    public function __toString(): string
    {
        // signature is optional, but JWT spec says to always add the dot before it
        return 
            $this->getEncodedHeader()
            .'.'
            .$this->getEncodedPayload()
            .'.'
            .$this->getEncodedSignature();
    }
}
