<?php
require_once __DIR__ . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->load();
$errName = $errMail = $errPass = $errPassRep = $generalError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = $_POST["name"];
	$email = $_POST["mail"];
	$pass = $_POST["pass"];
	$passRep = $_POST["passRep"];
	$isDataValid = true;

	if (empty(trim($name))) {
		$errName = "Please enter a name!";
		$isDataValid = false;
	}

	if (empty(trim($email))) {
		$errMail = "Please enter an email adress!";
		$isDataValid = false;
	}

	if (empty(trim($pass))) {
		$errPass = "Please enter an password!";
		$isDataValid = false;
	}
	else if ($pass != $passRep) {
		$errPassRep = "The passwords do not match!";
		$isDataValid = false;
	}
	if ($isDataValid) {
		require "database.php";
		$isNewData = true;

		if(isRegistered("name", $name)){
			$errName = "Name already exists. Please choose another name!";
			$isNewData = false;
		}
		if(isRegistered("email", $email)){
			$errMail = "Email address is already registered!";
			$isNewData = false;
		}
		if ($isNewData == true)
			if(!register($name, $pass, $email))
				$generalError = "Something went wrong while registering the user.";
			else
				die ("<meta http-equiv='refresh' content='0; URL=login.php' />");
	}
}
else
	$name = $email = $pass = $passRep = "";
 ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/light/logreg.css" media="(prefers-color-scheme: light)" />
		<link rel="stylesheet" href="css/dark/logreg.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)" />
		<link rel="stylesheet" href="css/logreg.css" />
		<title>SpiderMILK register</title>
	</head>
	<body>
		<form method="POST" action="register.php">
        <input type="text" name="name" placeholder="Username" title="Name may only contain letters, numbers and: _.-" pattern="[A-Za-z0-9_.\-]{3,16}" value="<?php echo $name ?>"/>
			<span class="errorMsg"><?php echo $errName ?></span>
			<br/>
			<input type="email" name="mail" placeholder="E-Mail" value="<?php echo $email ?>"/>
			<span class="errorMsg"><?php echo $errMail ?></span>
			<br/>
			<input type="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 characters" name="pass" value="<?php echo $pass ?>"/>
			<span class="errorMsg"><?php echo $errPass ?></span>
			<br/>
			<input type="password" placeholder="Repeat password" name="passRep" value="<?php echo $passRep ?>"/>
			<span class="errorMsg"><?php echo $errPassRep ?></span>
			<br/>
			<input type="submit" value="Register"/>
		</form>
		<div class="errorMsg">
		<?php
				if($generalError!="")
					echo '<div class="errorMsg">'.$generalError.'</div>';
		?>
		</div>
	</body>
 </html>
 