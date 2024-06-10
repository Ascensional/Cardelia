<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Email </title>
	<link rel="stylesheet" type="text/css" href="../stylesheets/login.css">
</head>
<body>
	<div class="card">
		<h1>Forgot Password</h1>
		<form action="request_reset.php" method="POST">
			<div>
			  <label for="email">Registered Email</label>
			  <input type="email" id="email" name="email" placeholder="ex: example@gmail.com" required>
		  	</div>
		  	<p>Password recovery instructions will be sent to the registered email address</p>
		  	<button type="submit">Send</button>

		</form>
	</div>
</body>
</html>

<?php

	if ($_SERVER['REQUEST_METHOD']=="POST"){
		require "../db_connect.php";

		$email=filter_input(INPUT_POST,"email",FILTER_SANITIZE_SPECIAL_CHARS);

		$stmt=$conn->prepare("SELECT email FROM users WHERE email=?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();

		if($stmt->num_rows()==1){
			$token=bin2hex(random_bytes(50));
			$expires=date("u")+1800;

			$sql = "INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)";
	        $stmt = $conn->prepare($sql);
	        $stmt->bind_param("sss", $email, $token, $expires);
	        $stmt->execute();

			$to=$email;
			$subject="Password reset request";
			$message="You requested a password reset. Click the link below to reset your password:\n";
			$message.="http://yourwebsite.com/reset_password.php?token=" . $token;
		    $headers = "From: no-reply@cardelia.com";

		    mail($to, $subject, $message,$headers);

		    echo "Password reset link has been sent to your email.";
	    } else {
	        echo "No account found with that email address.";
	   	}

	   	$stmt->close();
	   	$conn->close();
	   }
	   
?>