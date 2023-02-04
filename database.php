<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//TODO #1 use official SurrealDB library instead of curl when it comes out

function db_query($query) {
    $ch = curl_init($_ENV["DB_ADDR"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json", "NS: $_ENV[DB_NS]", "DB: $_ENV[DB_NAME]"));
    curl_setopt($ch, CURLOPT_USERPWD, "$_ENV[DB_USER]:$_ENV[DB_PASS]");  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function checkLogin($email, $password) {
    $result = db_query("select * from users where email = '$email'");
    $result = json_decode($result, true)[0];
    if($result["status"] == "OK" && !empty($result["result"]) && password_verify($password, $result["result"][0]["password"]))
        return explode(":", $result["result"][0]["id"])[1];
    return null;
}
?>