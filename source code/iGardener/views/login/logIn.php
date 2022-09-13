<!DOCTYPE html>
<html lang="en">
<head>
	<title>iGardener</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../views/login/logIn.css">
	<link rel="shortcut icon" type="image/x-icon" href="../views/login/igardener.ico">
</head>
<body>
	<form method="POST" action="LoginController.php">
		<div>
			<label for="seller">
				<input id="seller" type="radio" name="userType" value="seller">
				<img src="../views/login/sellerText.png" alt="seller">
			</label>
			<label for="buyer">
				<input id="buyer" type="radio" name="userType" value="buyer">
				<img src="../views/login/buyerText.png" alt="buyer">
			</label>
		</div>
		<div>
			<input id="userName" type="text" name="username" placeholder="Username">
		</div>
		<div>
			<input id="password" type="password" name="password" placeholder="Password">
		</div>
		<div>
			<input id="submitButton" type="submit" name="op" value="Log in">
		</div>
		<div>
			<label>Don't have an account? 
				<a id="signUp" href="SignupController.php">Sign up</a>
			</label>
		</div>
	</form>
</body>
</html>