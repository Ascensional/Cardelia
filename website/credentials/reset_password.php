<?php
#if (isset($_GET['token'])) {
 #   $token = $_GET['token'];
#} else {
 #   die("Invalid request.");
#}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="../stylesheets/login.css">
</head>
<body>
    <div class="card">
        <form action="reset_password.php" method="POST">
            <h1>Change Password</h1>
            <div>
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <label for="new_password">New password:</label>
                <input type="password" id="new_password" name="new_password" placeholder="new password" required>
            </div>
            <div>
                <label for="con_password">Confirm password:</label>
                <input type="password" id="con_password" name="con_password" placeholder="confirm password" required>

            </div>
            <button type="submit">Reset Password</button>
    </div>
</form>
</body>
</html>

<?php
    require "../db_connect.php";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $token = $_POST['token'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        $stmt=$conn->prepare("SELECT email from password_resets where token=? and expires>=?");
        $date=date("u");
        $stmt->bind_param("si",$token,$date);
        $stmt->execute();
        $stmt->store_result();


        if($stmt->num_rows==1){
            $stmt->bind_result($email);
            $stmt->fetch();
            
            $stmt=$conn->prepare("UPDATE users SET password=? WHERE email=?");
            $stmt->bind_param("ss",$password,$email);
            $stmt->execute();
            $stmt->store_result();

            $sql = "DELETE FROM password_resets WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            echo "Your password has been reset successfully.";
        }
        else{
            echo "Invalid or expired token";
        }
    }


