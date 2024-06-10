<?php
$conn = mysqli_connect("localhost", "root", "", "store");
    if (mysqli_connect_errno()) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
        die("Connection failed: " . $conn->connect_error);
    }
?>