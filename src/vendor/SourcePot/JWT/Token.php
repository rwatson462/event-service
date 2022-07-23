<?php

namespace SourcePot\JWT;

use SourcePot\Util\Base64Url;
use SourcePot\JWT\Exception\TamperedTokenException;

/**
 * A JWT Token
 */
class Token
{
   /**
    * 'typ' is always JWT for these
    * 'alg' is always HS256 for this class
    */
   private array $header = [
      'alg' => 'HS256'
   ];
   private array $payload = [];
   private string $signature = '';

   // Expiry timestamp of this token (seconds since epoch)
   private int $expiry = 0;

   public function __construct(?array $header = null, ?array $payload = null, ?string $signature = null)
   {
      if($header !== null) $this->header = $header;
      if($payload !== null) $this->payload = $payload;
      if($signature !== null) $this->signature = $signature;
   }

   public static function from(string $token): self
   {
      [$header, $payload, $signature] = explode('.', $token);
      $header = json_decode(Base64Url::decode($header));
      $payload = json_decode(Base64Url::decode($payload));
      $signature = Base64Url::decode($signature);
      return new self($header,$payload,$signature);
   }

   public function setExpiry(int $exp): self
   {
      $this->expiry = $exp;
      return $this;
   }

   public function setPayload(array $payload): self
   {
      $this->payload = $payload;
      return $this;
   }

   public function setHeader(array $header): self
   {
      $this->header = $header;
      return $this;
   }

   public function getEncodedHeader(): string
   {
      return Base64Url::encode(json_encode($this->header));
   }

   public function getEncodedPayload(): string
   {
      $payload = $this->payload;
      if($this->expiry > 0) $payload['exp'] = $this->expiry;

      return Base64Url::encode(json_encode($this->payload));
   }

   public function getSignature(): string
   {
      return $this->signature;
   }

   public function generateSignature(string $secret): string
   {
      return Base64Url::encode(
         hash_hmac(
            'sha256', 
            $this->getEncodedHeader() . '.' . $this->getEncodedPayload(), 
            $secret
         )
      );
   }

   public function sign(string $secret): self
   {
      $this->signature = $this->generateSignature($secret);
      return $this;
   }

   /**
    * Generates a signature for this token, checking against the user-given signature
    * to confirm validity
    */
   public function verify(string $user_signature, string $secret): bool
   {
      $signature = $this->generateSignature($secret);
      if($user_signature !== $signature) {
         throw new TamperedTokenException('Invalid token! After signing, new signature does not match original');
      }
      return true;
   }

   public function getJWT(): string
   {
      // signature is optional, but JWT spec says to always add the dot before it
      return 
         $this->getEncodedHeader()
         .'.'
         .$this->getEncodedPayload()
         .'.'
         .$this->getSignature();
   }
}
