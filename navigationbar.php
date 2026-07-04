<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nav_logout'])) {
    $_SESSION = [];

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }

    if (!headers_sent()) {
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        header('Location: userview.php');
        exit();
    }

    echo "<script>window.location.href='userview.php';</script>";
    echo '<noscript><meta http-equiv="refresh" content="0;url=userview.php"></noscript>';
    exit();
}

$nav_user = null;
$nav_fullname = "";
$nav_email = "";
$nav_contact = "";
$nav_initials = "SS";

$nav_conn = mysqli_connect("localhost", "root", "", "skillspark");

$nav_session_email = $_SESSION['user_email'] ?? $_SESSION['email'] ?? null;

if ($nav_conn && $nav_session_email) {
    $nav_email_session = $nav_session_email;
    $nav_stmt = mysqli_prepare($nav_conn, "SELECT fname, lname, email, contact FROM users WHERE email = ? LIMIT 1");

    if ($nav_stmt) {
        mysqli_stmt_bind_param($nav_stmt, "s", $nav_email_session);
        mysqli_stmt_execute($nav_stmt);
        $nav_result = mysqli_stmt_get_result($nav_stmt);

        if ($nav_result && mysqli_num_rows($nav_result) > 0) {
            $nav_user = mysqli_fetch_assoc($nav_result);
            $nav_fullname = trim($nav_user['fname'] . " " . $nav_user['lname']);
            $nav_email = $nav_user['email'];
            $nav_contact = $nav_user['contact'];
            $nav_initials = strtoupper(substr($nav_user['fname'], 0, 1) . substr($nav_user['lname'], 0, 1));
        }

        mysqli_stmt_close($nav_stmt);
    }
}

if ($nav_conn) {
    mysqli_close($nav_conn);
}
?>
<style>
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 40px;
    background: linear-gradient(90deg, #2c1ccf, #ec5fc3);
    min-height: 70px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-left, .nav-links, .nav-right {
    display: flex;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 20px;
    font-weight: 700;
    color: white;
    cursor: pointer;
}

.pro-badge {
    background: gold;
    color: black;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: bold;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 25px;
    margin: 0;
    padding: 0;
}

.nav-links a {
    text-decoration: none;
    color: white;
    font-weight: 500;
    font-size: 15px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.nav-links a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

.nav-right {
    gap: 15px;
}

.help-icon,
.profile-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: white;
    color: #2c1ccf;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.help-icon:hover,
.profile-circle:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(10px);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1200;
    padding: 24px;
}

.overlay.active {
    display: flex;
    animation: overlayFade 0.28s ease-out;
}

.popup {
    width: 340px;
    max-width: 100%;
    background: linear-gradient(180deg, #ffffff 0%, #f7f8ff 100%);
    border-radius: 24px;
    padding: 28px 24px 24px;
    text-align: center;
    position: relative;
    transform: translateY(24px) scale(0.9);
    opacity: 0;
    box-shadow: 0 28px 65px rgba(31, 41, 55, 0.28);
    border: 1px solid rgba(255,255,255,0.7);
}

.overlay.active .popup {
    animation: popupRise 0.38s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
}

.close-btn {
    position: absolute;
    right: 16px;
    top: 14px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    cursor: pointer;
    color: #64748b;
    background: #eef2ff;
    transition: transform 0.2s ease, background 0.2s ease;
}

.close-btn:hover {
    transform: rotate(90deg);
    background: #e2e8f0;
}

.profile-pic-wrap {
    width: 96px;
    height: 96px;
    margin: 0 auto 14px;
    padding: 4px;
    border-radius: 50%;
    background: linear-gradient(135deg, #22c55e, #2c1ccf, #ec5fc3);
    animation: pulseRing 2.2s infinite;
}

.profile-pic {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #fff;
    background: linear-gradient(135deg, #2c1ccf, #7c3aed, #ec5fc3);
    color: #fff;
    font-size: 32px;
    font-weight: 700;
    letter-spacing: 2px;
}

.popup h3 {
    margin: 0;
    font-size: 22px;
    color: #1e293b;
}

.popup-name {
    margin: 8px 0 18px;
    font-size: 15px;
    color: #475569;
    font-weight: 600;
}

.profile-info {
    display: grid;
    gap: 10px;
    text-align: left;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(99, 102, 241, 0.08);
    border-radius: 14px;
    padding: 12px 14px;
    color: #0f172a;
    font-size: 14px;
}

.info-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: linear-gradient(135deg, #2c1ccf, #ec5fc3);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}

.logout-form {
    margin-top: 18px;
}

.logout-btn {
    width: 100%;
    border: none;
    border-radius: 14px;
    padding: 12px 18px;
    background: linear-gradient(135deg, #ef4444, #db2777);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 14px 26px rgba(219, 39, 119, 0.24);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.logout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 30px rgba(219, 39, 119, 0.3);
}

@keyframes overlayFade {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes popupRise {
    from {
        transform: translateY(24px) scale(0.9);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

@keyframes pulseRing {
    0%, 100% { box-shadow: 0 0 0 0 rgba(44, 28, 207, 0.12); }
    50% { box-shadow: 0 0 0 12px rgba(44, 28, 207, 0); }
}

@media (max-width: 768px) {
    .navbar {
        padding: 12px 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .nav-links {
        gap: 12px;
        flex-wrap: wrap;
    }

    .nav-links a {
        font-size: 14px;
        padding: 6px 10px;
    }

    .popup {
        width: 100%;
        padding: 26px 20px 20px;
    }
}
</style>

<header class="navbar">
    <div class="nav-left">
        <div class="logo">
            <span>📖 SkillSpark</span>
            <span class="pro-badge">PRO</span>
        </div>
    </div>

    <ul class="nav-links">
        <li><a href="homepage.php">🏠Home</a></li>
        <li><a href="Courses.php">📚Courses</a></li>
        <li><a href="Dashboard.php">📊Dashboard</a></li>
        <li><a href="Subscription.php">💎Subscription</a></li>
        <li><a href="About.php">ℹ️About</a></li>
    </ul>

    <div class="nav-right">
        <a href="helpcenter.php" class="help-icon">?</a>

        <?php if ($nav_user): ?>
            <div class="profile-circle" onclick="openPopup()">
                <?php echo htmlspecialchars($nav_initials); ?>
            </div>
        <?php else: ?>
            <a href="login.php" class="profile-circle">L</a>
        <?php endif; ?>
    </div>
</header>

<?php if ($nav_user): ?>
<div class="overlay" id="overlay">
    <div class="popup">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <div class="profile-pic-wrap">
            <div class="profile-pic"><?php echo htmlspecialchars($nav_initials); ?></div>
        </div>
        <h3>Student Profile</h3>
        <p class="popup-name"><?php echo htmlspecialchars($nav_fullname); ?></p>
        <div class="profile-info">
            <div class="info-row">
                <span class="info-icon">@</span>
                <span><?php echo htmlspecialchars($nav_email); ?></span>
            </div>
            <div class="info-row">
                <span class="info-icon">#</span>
                <span><?php echo htmlspecialchars($nav_contact); ?></span>
            </div>
        </div>
        <form method="post" class="logout-form">
            <button type="submit" name="nav_logout" class="logout-btn">Logout</button>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
function openPopup() {
    var overlay = document.getElementById('overlay');
    if (overlay) {
        overlay.classList.add('active');
    }
}

function closePopup() {
    var overlay = document.getElementById('overlay');
    if (overlay) {
        overlay.classList.remove('active');
    }
}
</script>
