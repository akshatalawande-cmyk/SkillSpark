<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = mysqli_connect("localhost", "root", "", "skillspark");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$message = "";
$email_value = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Preserve email value
    $email_value = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepared Statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");

    if (!$stmt) {
        die("Database Error: " . $conn->error);
    }

    $stmt->bind_param("s", $email_value);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {

            // Store session data
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_name'] = $row['fname'] . " " . $row['lname'];

            header("Location: homepage.php");
            exit();

        } else {
            $message = "Invalid Password ❌";
        }

    } else {
        $message = "User Not Found ❌";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SkillSpark Login</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:Arial,sans-serif; }
body { height:100vh; display:flex; justify-content:center; align-items:center; background:linear-gradient(135deg,#f06292,#81d4fa); }
.login-box { background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); padding:30px; width:320px; border-radius:15px; text-align:center; box-shadow:0 8px 20px rgba(0,0,0,0.2); }
.logo { font-size:32px; margin-bottom:5px; }
h2 { color:#222; }
.tagline { font-size:14px; color:#333; margin-bottom:20px; }
input { width:100%; padding:10px; margin:8px 0; border:none; border-radius:8px; outline:none; }
.forgot { text-align:right; font-size:12px; margin-top:-4px; margin-bottom:10px; }
.forgot a { text-decoration:none; color:#333; }
button { width:100%; padding:10px; margin-top:10px; border:none; border-radius:20px; background:#3f51f5; color:#fff; font-size:16px; cursor:pointer; }
button:hover { background:#303f9f; }
.message { margin-top:12px; font-size:14px; color:#d32f2f; }
.register { margin-top:15px; font-size:13px; }
.register a { text-decoration:none; color:#3f51f5; font-weight:bold; }
</style>
</head>
<body>

<div class="login-box">
    <div class="logo">✨</div>
    <h2>SkillSpark</h2>
    <p class="tagline">Ignite Your Potential</p>

    <form method="POST">
        <input type="email" name="email" placeholder="Email address" required
               value="<?php echo htmlspecialchars($email_value); ?>">
        <input type="password" name="password" placeholder="Password" required autofocus>

        <div class="forgot">
            <a href="forgot.php">Forgot Password?</a>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <div class="register">
        Don’t have an account? <a href="register.php">Register</a>
    </div>
</div>

</body>
</html>