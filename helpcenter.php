<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "skillspark");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$helpTopics = [
    'enroll_course' => ['title' => 'How to Enroll in a Course', 'content' => 'Open the Courses page, choose your course, and click Start Learning or subscribe for premium access.'],
    'dashboard_help' => ['title' => 'Understanding the Dashboard', 'content' => 'Your dashboard shows learning progress, completion percentage, and recent activity for your enrolled courses.'],
    'setup_profile' => ['title' => 'Setting Up Your Profile', 'content' => 'Register or log in, then use the profile section in the navbar to review your account details.'],
    'quiz_work' => ['title' => 'How Quizzes Work', 'content' => 'Quizzes are attached to course modules and help you check your understanding after each lesson.'],
    'retake_tests' => ['title' => 'Retaking Tests', 'content' => 'You can retry practice tests whenever the course allows repeated attempts.'],
    'understand_scores' => ['title' => 'Understanding Scores', 'content' => 'Scores reflect your answers in quizzes and tests and help measure progress in each course.'],
    'subscription_plans' => ['title' => 'Subscription Plans', 'content' => 'SkillSpark offers free access plus premium plans with full-course access and added benefits.'],
    'payment_methods' => ['title' => 'Payment Methods', 'content' => 'You can continue with the demo payment flow on the subscription page for project use.'],
    'refund_policy' => ['title' => 'Refund Policy', 'content' => 'Please contact support for billing concerns so the team can review your request.'],
    'change_password' => ['title' => 'Change Password', 'content' => 'Use the forgot password flow if you need to reset your account password.'],
    'email_notifications' => ['title' => 'Email Notifications', 'content' => 'Important account and subscription updates will be shared through your registered email address.'],
    'privacy_settings' => ['title' => 'Privacy Settings', 'content' => 'Your registered account details are stored securely and used only for account-related features.'],
    'browser_compatibility' => ['title' => 'Browser Compatibility', 'content' => 'For the best experience, use a current version of Chrome, Edge, or Firefox.'],
    'connection_issues' => ['title' => 'Connection Issues', 'content' => 'Refresh the page, check your internet connection, and try again if content is not loading properly.'],
    'contact_support' => ['title' => 'Contact Support', 'content' => 'Use the question form below or open the contact page to reach the support team.'],
];

if (isset($_GET['key'])) {
    $key = $_GET['key'];
    header('Content-Type: application/json');

    if (isset($helpTopics[$key])) {
        echo json_encode($helpTopics[$key]);
    } else {
        echo json_encode(['error' => 'Topic not found']);
    }
    exit();
}

$formMessage = '';
$formMessageType = '';
$formSubject = '';
$formQuestion = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_help_query'])) {
    $formSubject = trim($_POST['subject'] ?? '');
    $formQuestion = trim($_POST['message'] ?? '');

    if (!isset($_SESSION['user_email'])) {
        $formMessage = 'Please login first to submit your question.';
        $formMessageType = 'error';
    } elseif ($formSubject === '' || $formQuestion === '') {
        $formMessage = 'Please fill in both subject and message.';
        $formMessageType = 'error';
    } else {
        $sessionEmail = $_SESSION['user_email'];
        $userStmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");

        if ($userStmt) {
            $userStmt->bind_param('s', $sessionEmail);
            $userStmt->execute();
            $userResult = $userStmt->get_result();
            $userRow = $userResult ? $userResult->fetch_assoc() : null;
            $userStmt->close();

            if ($userRow) {
                $userId = (int) $userRow['id'];
                $insertStmt = $conn->prepare("INSERT INTO help_center (user_id, subject, message, query_status) VALUES (?, ?, ?, 'Open')");

                if ($insertStmt) {
                    $insertStmt->bind_param('iss', $userId, $formSubject, $formQuestion);

                    if ($insertStmt->execute()) {
                        $formMessage = 'Your question has been submitted successfully.';
                        $formMessageType = 'success';
                        $formSubject = '';
                        $formQuestion = '';
                    } else {
                        $formMessage = 'Unable to submit your question right now.';
                        $formMessageType = 'error';
                    }

                    $insertStmt->close();
                } else {
                    $formMessage = 'Unable to prepare your request right now.';
                    $formMessageType = 'error';
                }
            } else {
                $formMessage = 'User account not found. Please login again.';
                $formMessageType = 'error';
            }
        } else {
            $formMessage = 'Unable to validate your account right now.';
            $formMessageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Help Center</title>
<style>
body { font-family: Arial, sans-serif; margin: 0; background: #f4f6fb; }
.navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 40px; background: linear-gradient(90deg, #2c1ccf, #ec5fc3); }
.logo { display: flex; align-items: center; gap: 8px; font-size: 22px; font-weight: bold; }
.pro-badge { background: gold; color: white; font-size: 12px; padding: 3px 8px; border-radius: 12px; font-weight: bold; }
.nav-links { list-style: none; display: flex; gap: 30px; }
.nav-links a { text-decoration: none; color: white; font-weight: 500; font-size: 16px; transition: 0.3s; }
.nav-links a:hover, .nav-links a.active { transform: translateY(-3px) scale(1.05); text-shadow: 0 0 8px #00bfff; }
.nav-right { display: flex; align-items: center; gap: 15px; }
.help-icon, .profile-circle { width: 38px; height: 38px; border-radius: 50%; background: white; color: #2c1ccf; display: flex; align-items: center; justify-content: center; font-weight: bold; cursor: pointer; font-size: 18px; }
.profile-circle { width: 40px; }
.container { max-width: 1100px; margin: 40px auto; padding: 20px; }
h2 { margin-bottom: 20px; }
.search-box { margin-bottom: 20px; }
.search-box input { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ccc; }
.grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }
.card { background: white; border: 1px solid #e0e0e0; border-radius: 12px; padding: 18px; }
.card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
.card a { display: block; color: #4b6bfb; margin: 6px 0; text-decoration: none; cursor:pointer; }
.card a:hover { text-decoration: underline; }
.contact-box { margin-top: 30px; padding: 15px; background: #e9f0ff; border-radius: 8px; text-align: center; }
.faq { background: white; padding: 50px 20px; }
.faq h2 { font-size: 32px; text-align: center; margin-bottom: 30px; }
.faq-item { background: #93c1d8; border-radius: 10px; padding: 18px 20px; margin: 15px auto; max-width: 800px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); transition: all 0.3s ease; }
.faq-item:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.faq-item h4 { display: flex; justify-content: space-between; cursor: pointer; font-size: 18px; }
.faq-item p { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; margin-top: 0; color: #555; }
.faq-item.active p { max-height: 200px; margin-top: 10px; }
.faq-item span { font-size: 20px; transition: transform 0.3s; }
.faq-item.active span { transform: rotate(45deg); }
.ask-question { background: #ffffff; padding: 60px 20px; text-align: center; }
.ask-question h2{ font-size:32px; margin-bottom:30px; }
.question-card{ background: #93c1d8; width:380px; margin:auto; padding:30px; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,0.15); display:flex; flex-direction:column; gap:15px; }
.question-card input, .question-card textarea{ width:100%; padding:12px; border-radius:6px; border:1px solid #ddd; font-size:14px; }
.question-card textarea{ height:90px; resize:none; }
.question-card button{ padding:12px; border:none; border-radius:6px; background:#4b6bfb; color:white; font-weight:bold; cursor:pointer; transition:0.3s; }
.question-card button:hover{ background:#2c4de0; }
.form-alert { padding: 12px 14px; border-radius: 8px; font-size: 14px; text-align: left; }
.form-alert.success { background: #dcfce7; color: #166534; }
.form-alert.error { background: #fee2e2; color: #991b1b; }
.form-note { font-size: 13px; color: #1f2937; text-align: left; }
.cta { background: white; padding: 50px 20px; text-align: center; }
.cta h2 { font-size: 30px; }
.cta p { margin: 10px 0 20px; font-size: 18px; }
.cta button { padding: 10px 18px; border-radius: 6px; border: none; background: #4b6bfb; color: white; cursor: pointer; }
.cta button:hover { background: #2c4de0; }
.site-footer { background: #009688; color: white; text-align: center; padding: 20px; }
.footer-links a { color: white; text-decoration: none; margin: 0 10px; font-weight: bold; }
.footer-links span { margin: 0 5px; }
</style>
</head>
<body>
<?php include 'navigationbar.php'; ?>

<div class="container">
    <h2>Help Center</h2>
    <div class="search-box"><input type="text" id="searchInput" placeholder="Search for help..." onkeyup="searchCards()"></div>
    <div class="grid">
        <div class="card">
            <h3>Getting Started</h3>
            <a href="#" onclick="openModal('enroll_course'); return false;">How to enroll in a course</a>
            <a href="#" onclick="openModal('dashboard_help'); return false;">Understanding the dashboard</a>
            <a href="#" onclick="openModal('setup_profile'); return false;">Setting up your profile</a>
        </div>
        <div class="card">
            <h3>Quizzes & Tests</h3>
            <a href="#" onclick="openModal('quiz_work'); return false;">How quizzes work</a>
            <a href="#" onclick="openModal('retake_tests'); return false;">Retaking tests</a>
            <a href="#" onclick="openModal('understand_scores'); return false;">Understanding scores</a>
        </div>
        <div class="card">
            <h3>Subscription & Billing</h3>
            <a href="#" onclick="openModal('subscription_plans'); return false;">Subscription plans</a>
            <a href="#" onclick="openModal('payment_methods'); return false;">Payment methods</a>
            <a href="#" onclick="openModal('refund_policy'); return false;">Refund policy</a>
        </div>
        <div class="card">
            <h3>Account Settings</h3>
            <a href="#" onclick="openModal('change_password'); return false;">Change password</a>
            <a href="#" onclick="openModal('email_notifications'); return false;">Email notifications</a>
            <a href="#" onclick="openModal('privacy_settings'); return false;">Privacy settings</a>
        </div>
        <div class="card">
            <h3>Technical Support</h3>
            <a href="#" onclick="openModal('browser_compatibility'); return false;">Browser compatibility</a>
            <a href="#" onclick="openModal('connection_issues'); return false;">Connection issues</a>
            <a href="#" onclick="openModal('contact_support'); return false;">Contact support</a>
        </div>
    </div>
</div>

<div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center;">
    <div style="background:white; width:500px; padding:20px; border-radius:10px;">
        <h3 id="modalTitle"></h3>
        <p id="modalContent"></p>
        <button onclick="closeModal()">Close</button>
    </div>
</div>

<div id="adminPanel" style="display:none; text-align:center; margin-bottom:20px;">
  <input type="text" id="newQuestion" placeholder="Enter question" />
  <input type="text" id="newAnswer" placeholder="Enter answer" />
  <button onclick="addFAQ()">Add FAQ</button>
</div>
<section class="faq">
  <h2>Frequently Asked Questions</h2>

  <div class="faq-item">
    <h4>Can I switch plans anytime? <span>+</span></h4>
    <p>Yes, you can upgrade or downgrade your plan at any time.</p>
  </div>

  <div class="faq-item">
    <h4>Do you offer refunds? <span>+</span></h4>
    <p>We offer a 30-day money-back guarantee.</p>
  </div>

  <div class="faq-item">
    <h4>What payment methods do you accept? <span>+</span></h4>
    <p>We accept major credit cards, PayPal, and bank transfers.</p>
  </div>

  <div class="faq-item">
    <h4>Is there a free trial? <span>+</span></h4>
    <p>Yes! Pro plan includes a 7-day free trial.</p>
  </div>
</section>

<section class="ask-question">
  <h2>Have a question?</h2>

  <form class="question-card" method="post">
      <?php if ($formMessage !== ''): ?>
          <div class="form-alert <?php echo htmlspecialchars($formMessageType); ?>"><?php echo htmlspecialchars($formMessage); ?></div>
      <?php endif; ?>

      <input type="text" name="subject" placeholder="Subject" value="<?php echo htmlspecialchars($formSubject); ?>">
      <textarea name="message" placeholder="Type your question here..."><?php echo htmlspecialchars($formQuestion); ?></textarea>
      <div class="form-note">Questions are saved in the <code>users.help_center</code> table with status <code>Open</code>.</div>
      <button type="submit" name="submit_help_query">Submit Question</button>
  </form>
</section>

<section class="cta">
  <h2>Still have questions?</h2>
  <p>Our team is here to help you choose the right plan</p>
  <button class="btn light">Contact Us</button>
</section>
<div class="contact-box">
    Can't find what you're looking for?<br>
    <a href="Customer.php">Contact our support team -></a>
</div>
<script>
function openModal(type) {
    fetch("helpcenter.php?key=" + type)
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            document.getElementById("modalTitle").innerText = data.title;
            document.getElementById("modalContent").innerText = data.content;
            document.getElementById("modalOverlay").style.display = "flex";
        }
    }).catch(err => console.error(err));
}

function closeModal() {
    document.getElementById("modalOverlay").style.display = "none";
}

function searchCards() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll(".card").forEach(card => {
        card.style.display = card.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}

document.querySelectorAll(".faq-item h4").forEach(question => {
    question.addEventListener("click", () => {
        const item = question.parentElement;
        item.classList.toggle("active");
    });
});
</script>
<?php include 'footer.php'; ?>
</body>
</html>
