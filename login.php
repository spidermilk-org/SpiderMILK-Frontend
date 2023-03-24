<?php
require_once __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
Dotenv\Dotenv::createImmutable(__DIR__)->load();

if(isset($_POST["pass"]) and isset($_POST["mail"])) {
	$password = htmlspecialchars(stripcslashes(trim($_POST["pass"])));
	$email = htmlspecialchars(stripcslashes(trim($_POST["mail"])));
}
else
	$password = $email = "";

if(!empty($email) and !empty($password)) {
	require "database.php";
	$userID = checkLogin($email, $password);
	if($userID) {
		$payload = [
			"iss" => "https://spidermilk.ddnsfree.com",
			"iat" => time(),
			"exp" => time() + 604800,
			"id" => $userID
		];
		$jwt = JWT::encode($payload, $_ENV["JWT_KEY"], "HS512");
		echo ("<script>document.cookie='sm_jwt_login=$jwt'</script></script><meta http-equiv='refresh' content='1; URL=/' />Redirecting...");
	}
	else {
		usleep(1000000);
		$failMessage = "Email address and password do not match. Please try again.";
	}
}
else if(empty($email) xor empty($password))
	$failMessage = "Please enter <b>both</b> pieces of login data!";
?>


<!DOCTYPE html>
<html>
	<head>
		<title>SpiderMILK Login</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="css/light/logreg.css" media="(prefers-color-scheme: light)" />
		<link rel="stylesheet" href="css/dark/logreg.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)" />
		<link rel="stylesheet" href="css/logreg.css" />
	</head>
	<body>
		<form method="POST" action="login.php">
			<?php if(isset($failMessage))
				echo '<span class="errorMsg">'.$failMessage."</span> <br/>";
			?>
			<input type="email" name="mail" value="<?php echo $email ?>" placeholder="E-Mail" required>
			<br/>
			<input type="password" name="pass" placeholder="Password" required>
			<br/>
			<input type="submit" value="Login!">
			<br/>
			<span id="reg">Don't have an account yet? <a href="register.php">Register</a> now!</span>
    	</form>
  	</body>
</html>
