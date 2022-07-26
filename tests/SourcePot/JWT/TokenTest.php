<?php

namespace SourcePot\JWT;

use PHPUnit\Framework\TestCase;
use SourcePot\JWT\Exception\InvalidTokenException;
use SourcePot\JWT\Exception\TamperedTokenException;

class TokenTest extends TestCase
{
    public function testCannotInstantiate(): void
    {
        $this->expectException(\Throwable::class);
        new Token();
    }

    protected function dataProviderCanCreateToken(): iterable
    {
        $header = ['expiry' => 1];
        $payload = ['event' => 'todo.created'];

        yield 'empty' => [[]];
        yield 'headers only' => [['header' => $header]];
        yield 'payload only' => [['payload' => $payload]];
        yield 'header and payload' => [['header' => $header, 'payload' => $payload]];
    }

    /**
     * @dataProvider dataProviderCanCreateToken
     */
    public function testCanCreateToken(array $args): void
    {
        $token = Token::create(...$args);
        $this->assertInstanceOf(Token::class, $token);
    }

    public function testCanGetHeader(): void
    {
        $header = ['expiry' => 1];

        $token = Token::create(header: $header);

        $this->assertEquals($header, $token->getHeader());
    }

    public function testCanGetPayload(): void
    {
        $payload = ['event' => 'todo.created'];

        $token = Token::create(payload: $payload);

        $this->assertEquals($payload, $token->getPayload());
    }

    public function testCanGetSignature(): void
    {
        $signature = 'signature';
        $token = Token::create();

        $token->setSignature($signature);
        $this->assertEquals($signature, $token->getSignature());
    }

    public function testCanUseExpiry(): void
    {
        $expiry = 1000;
        $token = Token::create();

        $token->setExpiry($expiry);

        $this->assertEquals($expiry, $token->getExpiry());
    }

    public function testCanSignToken(): void
    {
        // an empty token signed with this secret produces the signature below
        // if any token defaults change, this signature will need updating
        $secret = 'secret';
        $signature = 'c3da5dd7f13a5d1597aa567bed8b962aaa9a531e0a883eddb20339e9c66b0eb7';

        $token = Token::create();

        $token->sign('secret');

        $this->assertEquals($signature, $token->getSignature());
    }

    public function testToStringMethod(): void
    {
        // this is what the token should look like when stringified
        // if any token defaults change, this string will need updating
        $tokenStr = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10=.YzNkYTVkZDdmMTNhNWQxNTk3YWE1NjdiZWQ4Yjk2MmFhYTlhNTMxZTBhODgzZWRkYjIwMzM5ZTljNjZiMGViNw==';

        $token = Token::create();
        $token->sign('secret');

        $this->assertEquals($tokenStr, (string) $token);
    }

    public function testCanValidateTokenWithInvalidSecret(): void
    {
        $token = Token::create();
        $token->sign('invalid-secret');

        $this->expectException(TamperedTokenException::class);

        $token->validate('secret');
    }

    public function testCanValidateTokenWithValidSecret(): void
    {
        $token = Token::create();
        $token->sign('secret');

        $this->assertTrue($token->validate('secret'));
    }

    public function testCanCreateTokenFromString(): void
    {
        $tokenStr = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTY1ODk0MjQyMH0=.eyJjbGllbnQiOiJ0b2RvIiwiZXZlbnRzIjpbInRvZG8uY3JlYXRlZCIsInRvZG8uZGVzY3JpcHRpb24uY2hhbmdlZCIsInRvZG8ubWFya2VkQ29tcGxldGUiLCJ0b2RvLmRlbGV0ZWQiXX0=.ZjNhYjc3YjU1Y2YzODM0ZWI1NDNlZDUxNjRlZmUyYjY1MzQ0N2M4MWNmNjI2ZTY5MGUzZThlZGEyNTYwZjU5Mg==';

        $token = Token::from($tokenStr);

        $this->assertEquals($token->getPayload()['client'], 'todo');
        $this->assertEquals($token->getHeader()['exp'], 1658942420);
    }

    public function testCannotCreateTokenFromInvalidString(): void
    {
        $tokenStr = 'invalid-token-string';
        
        $this->expectException(InvalidTokenException::class);

        Token::from($tokenStr);
    }
}
