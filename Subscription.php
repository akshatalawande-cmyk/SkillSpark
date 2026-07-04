<?php
session_start();

$course = $_GET['course'] ?? '';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "skillspark";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userEmail = $_SESSION['user_email'] ?? $_SESSION['email'] ?? '';
$currentUser = null;

if ($userEmail !== '') {
    $userStmt = $conn->prepare("SELECT id, fname, lname, email FROM users WHERE email = ? LIMIT 1");
    if ($userStmt) {
        $userStmt->bind_param("s", $userEmail);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        if ($userResult && $userResult->num_rows > 0) {
            $currentUser = $userResult->fetch_assoc();
        }
        $userStmt->close();
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'subscribe') {
    header('Content-Type: application/json');

    if (!$currentUser) {
        echo json_encode(["success" => false, "message" => "Please log in first."]);
        exit;
    }

    $planName = trim($_POST['plan'] ?? '');
    $planPrices = [
        'Free' => 0.00,
        'Monthly Pro' => 29.00,
        'Custom Yearly' => 299.00,
    ];

    if (!array_key_exists($planName, $planPrices)) {
        echo json_encode(["success" => false, "message" => "Invalid plan selected."]);
        exit;
    }

    $price = $planPrices[$planName];
    $startDate = date('Y-m-d');
    $endDate = $planName === 'Custom Yearly'
        ? date('Y-m-d', strtotime('+1 year'))
        : date('Y-m-d', strtotime('+30 days'));
    $status = 'Active';

    $stmt = $conn->prepare(
        "INSERT INTO subscriptions (user_id, plan_name, price, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param(
        "isdsss",
        $currentUser['id'],
        $planName,
        $price,
        $startDate,
        $endDate,
        $status
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Subscription saved successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Unable to save subscription."]);
    }

    $stmt->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Choose Your Plan</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
body {
    background:
        radial-gradient(circle at top left, rgba(156, 99, 255, 0.18), transparent 30%),
        radial-gradient(circle at top right, rgba(255, 190, 92, 0.16), transparent 28%),
        linear-gradient(180deg, #f9f5ff 0%, #f4efff 52%, #efe8ff 100%);
    color: #241b3a;
}
.pricing-shell { max-width: 1180px; margin: 0 auto; padding: 56px 24px 72px; }
.hero-copy { text-align: center; margin-bottom: 30px; }
.hero-copy h1 { font-size: 40px; font-weight: 800; letter-spacing: -0.03em; margin-bottom: 12px; color: #22183a; }
.hero-copy p { max-width: 650px; margin: 0 auto; color: #6b6287; font-size: 15px; line-height: 1.7; }
.plan-switch { width: 360px; max-width: 100%; margin: 0 auto 34px; padding: 6px; border-radius: 999px; background: rgba(255, 255, 255, 0.85); box-shadow: 0 14px 32px rgba(92, 71, 159, 0.12); display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.switch-pill { text-align: center; padding: 12px 16px; border-radius: 999px; font-size: 14px; font-weight: 600; color: #7b7199; }
.switch-pill.active { background: linear-gradient(90deg, #7c4dff, #9758ff); color: #fff; box-shadow: 0 8px 20px rgba(124, 77, 255, 0.35); }
.pricing-grid { display: grid; grid-template-columns: repeat(3, minmax(260px, 1fr)); gap: 24px; }
.plan-card { position: relative; border-radius: 28px; padding: 26px 24px 24px; background: rgba(255, 255, 255, 0.82); border: 1px solid rgba(130, 102, 206, 0.12); box-shadow: 0 24px 48px rgba(76, 53, 132, 0.12); backdrop-filter: blur(12px); display: flex; flex-direction: column; min-height: 520px; }
.plan-card.featured { background: linear-gradient(180deg, rgba(117, 82, 255, 0.96), rgba(83, 50, 194, 0.96)); color: #fff; transform: translateY(-10px); box-shadow: 0 28px 58px rgba(86, 52, 193, 0.28); }
.plan-badge { align-self: flex-start; padding: 7px 14px; border-radius: 999px; font-size: 12px; font-weight: 700; letter-spacing: 0.02em; margin-bottom: 18px; }
.plan-card:not(.featured) .plan-badge { background: #efe7ff; color: #6b3df0; }
.plan-card.featured .plan-badge { background: rgba(255, 255, 255, 0.18); color: #fff; }
.plan-card h3 { font-size: 28px; font-weight: 800; margin-bottom: 10px; }
.plan-card .description { color: #6b6287; font-size: 14px; line-height: 1.6; min-height: 64px; }
.plan-card.featured .description { color: rgba(255, 255, 255, 0.82); }
.price-row { margin: 22px 0 18px; display: flex; align-items: baseline; gap: 6px; }
.price-row strong { font-size: 44px; line-height: 1; }
.price-row span { font-size: 14px; color: #857ca1; }
.plan-card.featured .price-row span { color: rgba(255, 255, 255, 0.78); }
.feature-list { list-style: none; display: grid; gap: 14px; margin-bottom: 28px; flex: 1; }
.feature-list li { position: relative; padding-left: 26px; font-size: 14px; line-height: 1.55; color: #41375d; }
.plan-card.featured .feature-list li { color: rgba(255, 255, 255, 0.92); }
.feature-list li::before { content: "\2713"; position: absolute; left: 0; top: 0; color: #6d47ff; font-weight: 700; }
.plan-card.featured .feature-list li::before { color: #fff6bf; }
.plan-btn { width: 100%; border: none; border-radius: 14px; padding: 14px 18px; font-size: 15px; font-weight: 700; cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; }
.plan-btn:hover { transform: translateY(-2px); }
.btn-free { background: #ebe7f8; color: #34285b; }
.btn-pro { background: #ffffff; color: #5a36dc; box-shadow: 0 12px 26px rgba(38, 20, 102, 0.18); }
.btn-custom { background: linear-gradient(90deg, #1f1637, #302051); color: #fff; }
.sub-overlay { position: fixed; inset: 0; background: rgba(15, 12, 31, 0.52); display: none; justify-content: center; align-items: center; z-index: 2500; padding: 24px; }
.sub-popup { width: 390px; max-width: 100%; background: #ffffff; padding: 30px 28px; border-radius: 24px; text-align: center; box-shadow: 0 24px 60px rgba(31, 22, 61, 0.28); position: relative; animation: fadeIn 0.28s ease-in-out; }
.sub-popup h2 { margin-top: 0; color: #231842; }
.sub-popup p { margin-top: 10px; color: #60567d; line-height: 1.6; }
.sub-close-btn { position: absolute; top: 12px; right: 16px; font-size: 22px; cursor: pointer; color: #6f658b; }
.sub-popup button { padding: 12px 20px; background: linear-gradient(90deg, #7b4dff, #9659ff); color: white; border: none; border-radius: 12px; cursor: pointer; margin-top: 18px; width: 100%; font-weight: 700; }
.sub-popup-form { margin-top: 18px; display: grid; gap: 12px; }
.sub-popup-form input { width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid #d8d2eb; outline: none; }
.sub-popup-form .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.status-msg { margin-top: 14px; font-weight: 600; }
.success-msg { color: #15803d; }
.error-msg { color: #dc2626; }
@keyframes fadeIn { from { opacity: 0; transform: scale(0.88); } to { opacity: 1; transform: scale(1); } }
@media (max-width: 960px) { .pricing-grid { grid-template-columns: 1fr; } .plan-card.featured { transform: none; } .hero-copy h1 { font-size: 32px; } }
</style>
</head>
<body>
<div class="sub-overlay" id="paymentPopup">
    <div class="sub-popup">
        <span class="sub-close-btn" onclick="closePopup('paymentPopup')">&times;</span>
        <h2>Demo Payment</h2>
        <p id="paymentPlanText"></p>
        <p>Fill the demo card details below to continue.</p>
        <div class="sub-popup-form">
            <input type="text" id="cardName" placeholder="Card Holder Name">
            <input type="text" id="cardNumber" placeholder="Card Number" maxlength="16">
            <div class="row">
                <input type="text" id="expiryDate" placeholder="MM/YY" maxlength="5">
                <input type="text" id="cvv" placeholder="CVV" maxlength="3">
            </div>
        </div>
        <button type="button" onclick="processDemoPayment()">Pay Now</button>
        <div id="paymentMessage" class="status-msg"></div>
    </div>
</div>

<?php include 'navigationbar.php'; ?>

<section class="pricing-shell">
    <div class="hero-copy">
        <h1>Choose your right plan!</h1>
        <p>Select from flexible plans crafted for every learner. Start free, upgrade monthly for all courses, or go custom yearly for premium benefits, certificates, notes access, and an ad-free experience.</p>
    </div>

    <div class="plan-switch">
        <div class="switch-pill active">Monthly</div>
        <div class="switch-pill">Quarterly / Yearly</div>
    </div>

    <div class="pricing-grid">
        <article class="plan-card">
            <span class="plan-badge">Free</span>
            <h3>Free</h3>
            <p class="description">Ideal for new learners who want to explore the platform and start with core topics.</p>
            <div class="price-row">
                <strong>$0</strong>
                <span>/month</span>
            </div>
            <ul class="feature-list">
                <li>Access to first 3 free courses</li>
                <li>Basic study material support</li>
                <li>Community learning access</li>
                <li>Limited quizzes and practice</li>
                <li>Beginner-friendly learning path</li>
            </ul>
            <button type="button" class="plan-btn btn-free" onclick="subscribePlan('Free')">Get Started</button>
        </article>

        <article class="plan-card featured">
            <span class="plan-badge">Monthly Pro</span>
            <h3>Pro</h3>
            <p class="description">Best for committed learners who want monthly access to every course and premium support.</p>
            <div class="price-row">
                <strong>$29</strong>
                <span>/month</span>
            </div>
            <ul class="feature-list">
                <li>Access to all courses</li>
                <li>Premium downloadable resources</li>
                <li>Priority doubt support</li>
                <li>Certificates on completion</li>
                <li>Full quizzes and assessments</li>
            </ul>
            <button type="button" class="plan-btn btn-pro" onclick="subscribePlan('Monthly Pro')">Start Monthly Plan</button>
        </article>

        <article class="plan-card">
            <span class="plan-badge">Custom Yearly</span>
            <h3>Custom</h3>
            <p class="description">A yearly premium package with full access, ad-free learning, notes, certifications, and strong student outcomes.</p>
            <div class="price-row">
                <strong>$299</strong>
                <span>/year</span>
            </div>
            <ul class="feature-list">
                <li>Yearly access to all courses</li>
                <li>Ad-free premium learning experience</li>
                <li>Certification support included</li>
                <li>Good rating student batch guidance</li>
                <li>Notes access for every module</li>
            </ul>
            <button type="button" class="plan-btn btn-custom" onclick="subscribePlan('Custom Yearly')">Book This Plan</button>
        </article>
    </div>
</section>

<script>
let selectedPlan = '';

function subscribePlan(plan) {
    selectedPlan = plan;

    if (plan === 'Free') {
        window.location.href = 'Courses.php';
        return;
    }

    document.getElementById('paymentPlanText').textContent = 'You selected the ' + plan + ' plan.';
    document.getElementById('paymentMessage').textContent = '';
    document.getElementById('paymentMessage').className = 'status-msg';
    document.getElementById('paymentPopup').style.display = 'flex';
}

function closePopup(id) {
    document.getElementById(id).style.display = 'none';
}

function saveSubscription() {
    fetch('', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=subscribe&plan=' + encodeURIComponent(selectedPlan)
    })
    .then(res => res.json())
    .then(data => {
        const message = document.getElementById('paymentMessage');
        message.textContent = data.message || 'Something went wrong.';
        message.className = data.success ? 'status-msg success-msg' : 'status-msg error-msg';

        if (data.success) {
            message.textContent = 'Payment is done successfully. Opening your video...';
            setTimeout(() => {
                window.location.href = '<?php echo $course !== '' ? 'Courses.php?course=' . rawurlencode($course) : 'Courses.php'; ?>';
            }, 1500);
        }
    });
}

function processDemoPayment() {
    const cardName = document.getElementById('cardName').value.trim();
    const cardNumber = document.getElementById('cardNumber').value.trim();
    const expiryDate = document.getElementById('expiryDate').value.trim();
    const cvv = document.getElementById('cvv').value.trim();
    const message = document.getElementById('paymentMessage');

    if (!cardName || cardNumber.length !== 16 || expiryDate.length !== 5 || cvv.length !== 3) {
        message.textContent = 'Enter valid demo payment details.';
        message.className = 'status-msg error-msg';
        return;
    }

    message.textContent = 'Payment is done successfully. Saving your subscription...';
    message.className = 'status-msg success-msg';
    saveSubscription();
}
</script>
<?php include 'footer.php'; ?>
</body>
</html>
