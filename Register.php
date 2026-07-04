<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "skillspark");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";
$show_success_popup = false;

// Preserve form values
$fname_value = isset($_POST['fname']) ? trim($_POST['fname']) : '';
$lname_value = isset($_POST['lname']) ? trim($_POST['lname']) : '';
$birthdate_value = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
$contact_value = isset($_POST['contact']) ? trim($_POST['contact']) : '';
$email_value = isset($_POST['email']) ? trim($_POST['email']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $birthdate = $_POST['birthdate'];
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!preg_match("/^[A-Za-z]+$/", $fname)) {
        $message = "First name must contain only letters!";
    } elseif (!preg_match("/^[A-Za-z]+$/", $lname)) {
        $message = "Last name must contain only letters!";
    } elseif (!preg_match("/^[0-9]+$/", $contact)) {
        $message = "Contact number must contain only digits!";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $message = "Contact must be 10 digits!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address!";
    } else {
        $check = $conn->prepare("SELECT email FROM users WHERE email = ?");

        if (!$check) {
            $message = "Database error: " . $conn->error;
        } else {
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $message = "Email already registered!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare(
                    "INSERT INTO users (fname, lname, birthdate, contact, email, password) VALUES (?,?,?,?,?,?)"
                );

                if (!$stmt) {
                    $message = "Database error: " . $conn->error;
                } else {
                    $stmt->bind_param("ssssss", $fname, $lname, $birthdate, $contact, $email, $hashed_password);

                    if ($stmt->execute()) {
                        $show_success_popup = true;
                        $message = "Registered Successfully";
                        $fname_value = "";
                        $lname_value = "";
                        $birthdate_value = "";
                        $contact_value = "";
                        $email_value = "";
                    } else {
                        $message = "Error: " . $stmt->error;
                    }

                    $stmt->close();
                }
            }

            $check->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SkillSpark Register</title>
<style>
body {
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#f06292,#81d4fa);
    font-family:Arial, sans-serif;
    margin:0;
    position:relative;
}
.box {
    background: rgba(255,255,255,0.25);
    backdrop-filter:blur(10px);
    padding:30px;
    border-radius:15px;
    width:350px;
    text-align:center;
}
input { width:100%; padding:10px; margin:6px 0; border:none; border-radius:8px; }
button { width:100%; padding:10px; border:none; border-radius:20px; background:#3f51f5; color:white; cursor:pointer; }
button:hover { background:#303f9f; }
.message { margin-top:15px; color:red; font-weight:bold; }
a { text-decoration:none; color:#3f51f5; font-weight:bold; }
.success-overlay {
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.25);
    display:flex;
    justify-content:center;
    align-items:center;
}
.success-card {
    width:320px;
    background:#ffffff;
    border-radius:18px;
    padding:28px 24px;
    text-align:center;
    box-shadow:0 16px 40px rgba(0,0,0,0.22);
    animation:popup 0.25s ease-out;
}
.success-tick {
    width:74px;
    height:74px;
    margin:0 auto 16px;
    border-radius:50%;
    background:#22c55e;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:40px;
    font-weight:bold;
}
.success-card h3 {
    margin:0 0 8px;
    color:#15803d;
}
.success-card p {
    margin:0;
    color:#374151;
}
@keyframes popup {
    from {
        transform:scale(0.92);
        opacity:0;
    }
    to {
        transform:scale(1);
        opacity:1;
    }
}
</style>
</head>
<body>

<div class="box">
    <h2>Create Account</h2>
    <form method="POST">
        <input type="text" name="fname" placeholder="First Name" required pattern="[A-Za-z]+" title="First name should contain only letters" value="<?php echo htmlspecialchars($fname_value); ?>">
        <input type="text" name="lname" placeholder="Last Name" required pattern="[A-Za-z]+" title="Last name should contain only letters" value="<?php echo htmlspecialchars($lname_value); ?>">
        <input type="date" name="birthdate" required value="<?php echo htmlspecialchars($birthdate_value); ?>">
        <input type="text" name="contact" placeholder="Contact No" required inputmode="numeric" pattern="[0-9]{10}" maxlength="10" title="Contact number should contain exactly 10 digits" value="<?php echo htmlspecialchars($contact_value); ?>">
        <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email_value); ?>">
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>

    <?php if ($message && !$show_success_popup) { ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php } ?>

    <p><a href="login.php">Back to Login</a></p>
</div>

<?php if ($show_success_popup) { ?>
    <div class="success-overlay">
        <div class="success-card">
            <div class="success-tick">&#10003;</div>
            <h3>Registered Successfully</h3>
            
        </div>
    </div>
    <script>
        setTimeout(function () {
            window.location.href = 'login.php';
        }, 2000);
    </script>
<?php } ?>

</body>
</html>
