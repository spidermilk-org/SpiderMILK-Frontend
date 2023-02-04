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
			<input type="email" name="mail" placeholder="E-Mail" required>
			<br/>
			<input type="password" name="pass" placeholder="Password" required>
			<br/>
			<input type="submit" value="Login!">
			<br/>
			<span id="reg">Don't have an account yet? <a href="register.php">Register</a> now!</span>
    	</form>
  	</body>
</html>