<?php

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
    if($result["status"] == "OK" && !empty($result["result"]) && password_verify($password, $result["result"][0]["password"])) {
        db_query("UPDATE users SET lastLogin = time::now(), lastSeen = time::now() WHERE email = $email");
        return explode(":", $result["result"][0]["id"])[1];
    }
    return null;
}

function isRegisteredName($name) {
    $result = db_query("SELECT * FROM users WHERE name = '$name'");
    $result = json_decode($result, true)[0];
    return $result["status"] == "OK" && !empty($result["result"]);
}

function isRegisteredEmail($mail) {
    $result = db_query("SELECT * FROM users WHERE email = '$mail'");
    $result = json_decode($result, true)[0];
    return $result["status"] == "OK" && !empty($result["result"]);
}

function register($name, $pass, $mail) {
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $result = db_query('BEGIN TRANSACTION;
    LET $name = "'.$name.'";
    LET $pass = "'.$pass.'";
    LET $mail = "'.$mail.'";
    LET $user = (CREATE users SET
        name = $name,
        password = $pass,
        email = $mail,
        loggedIn = false,
        signupDate = time::now(),
        lastLogin = time::now(),
        lastSeen = time::now(),
        language = "en"
    );
    RELATE $user ->isInLocation->location:tutorial0;
    COMMIT TRANSACTION');
    echo $result;
    $result = json_decode($result, true)[0];
    return $result["status"] == "OK";
}
?>