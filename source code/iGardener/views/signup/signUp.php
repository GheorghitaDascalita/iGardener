<!DOCTYPE html>
<html lang="en">
<head>
	<title>iGardener</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../views/signup/signUp.css">
	<link rel="shortcut icon" type="image/x-icon" href="../views/signup/igardener.ico">
</head>
<body>
	<form method="POST" action="SignupController.php">
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
			<input id="email" type="email" name="email" placeholder="Email">
		</div>
		<div>
			<input id="password" type="password" name="password" placeholder="Password">
		</div>
		<div>
			<input id="submitButton" type="submit" name="op" value="Sign up">
		</div>
		<div>
			<label>Already have an account? 
				<a id="logIn" href="LoginController.php">Log in</a>
			</label>
		</div>
	</form>
</body>
</html>