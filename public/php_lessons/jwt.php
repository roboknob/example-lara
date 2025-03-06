<?php
$data = [
    "user_id" => 777,
    "username" => ["Bitrixoid","test"],
    "exp" => time() + 3600 // токен живет 1 час
];

$secret = "BITRIXOID_TOP_SECRET";

$JWT = generateJWT($data, $secret);
setCookieJWT($JWT); // Сохраняем токен в куку
$jwtFromCookie = getCookieJWT();
if ($jwtFromCookie && verifyJWT($jwtFromCookie, $secret)) {
    echo "Токен валиден!";
} else {
    echo "Токен не валиден или истёк.";
}
function generateJWT($data, $secret) {
    // Создать заголовок токена в виде строки JSON
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    // Создать полезную нагрузку токена в виде строки JSON
    $payload = json_encode(normalizePayload($data));
    // Закодировать заголовок в строку Base64Url
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    // Кодировать полезную нагрузку в строку Base64Ur
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    // Создать Хэш подписи
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
    // Кодировать сигнатуру в строку Base64Url
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    //Создание JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    return $jwt;
}
function verifyJWT($jwt, $secret) {
    $token = explode(".", $jwt);

    if (count($token) != 3) {
        return false;
    }
    $payload = json_decode(base64_decode($token[1]), true);

    $signature = hash_hmac('sha256', $token[0] . "." . $token[1], $secret, true);
    $signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    if (!hash_equals($signature, $token[2])) {
        return false; // Подпись невалидна
    }

    if ($payload['exp'] < time()) {
        return false; // Токен истёк
    }

    return true;
}
function setCookieJWT($jwt) {
    setcookie("jwt_token", $jwt, time() + 3600, "/", "", false, true); // HttpOnly = true для безопасности
}
function getCookieJWT() {
    if(isset($_COOKIE["jwt_token"])) {
        return $_COOKIE["jwt_token"];
    }
    return null;
}

function normalizePayload($data)
{
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = normalizePayload($value); // РЕКУРСИЯ ЗАВЕЗЕНА!
        }
    }
    return $data;
}
