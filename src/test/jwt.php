<?php

include dirname(__DIR__).'/vendor/autoloader.php';

use RWA\JWT\Token;

const SECRET = 'password';

$token = new Token;
$payload = [
   'name' => 'Rob',
   'email' => 'rob.watson@me.com',
   'role' => 'user'
];
$token->setPayload($payload);
echo "token created with payload:\n".json_encode($payload)."\n";
echo $token->getJWT()."\n";

$token->sign(SECRET);
$signature = $token->getSignature();

echo $token->getJWT()."\n";



$token = new Token;
$token->setPayload([
   'name' => 'Rob',
   'email' => 'rob.watson@example.com',
   'role' => 'admin'
]);
echo $token->getJWT()."\n";

try {
   $token->verify($signature, SECRET);
}
catch(\Exception $e) {
   echo $e->getMessage()."\n";
}

$token->sign(SECRET);
echo $token->getJWT()."\n";