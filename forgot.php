<?php

$conn = mysqli_connect("localhost","root","","skillspark");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['email']) && isset($_POST['password'])) {

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $newpass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if user exists
        $check = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $check);

        if (!$result) {
            die("Query Failed: " . mysqli_error($conn));
        }

        if(mysqli_num_rows($result) > 0) {

            // Update password in user table
            $update = "UPDATE users SET password='$newpass' WHERE email='$email'";

            if(mysqli_query($conn, $update)) {
                $message = "Password Reset Successful ✅";
            } else {
                $message = "Error Updating Password ❌";
            }

        } else {
            $message = "Email Not Found ❌";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>

    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg,#f06292,#81d4fa);
            font-family: Arial;
        }

        .box {
            background: rgba(255,255,255,0.25);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            width: 320px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: none;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 20px;
            background: #3f51f5;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #303f9f;
        }

        .message {
            margin-top: 12px;
            font-size: 14px;
            font-weight: bold;
        }

        a {
            text-decoration: none;
            font-size: 13px;
            color: #3f51f5;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="box">
    <h2>Reset Password</h2>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="New Password" required>

        <button type="submit">Reset Password</button>
    </form>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <p><a href="login.php">← Back to Login</a></p>
</div>

</body>
</html>
