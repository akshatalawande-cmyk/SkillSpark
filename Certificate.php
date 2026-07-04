<?php
session_start();

require_once 'quiz_helpers.php';

$conn = mysqli_connect("localhost", "root", "", "skillspark");
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

quiz_initialize($conn);

$userEmail = $_SESSION['user_email'] ?? $_SESSION['email'] ?? '';
if ($userEmail === '') {
    header("Location: login.php");
    exit();
}

$userStmt = mysqli_prepare($conn, "SELECT id, fname, lname, email FROM users WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($userStmt, "s", $userEmail);
mysqli_stmt_execute($userStmt);
$userResult = mysqli_stmt_get_result($userStmt);
$user = mysqli_fetch_assoc($userResult);
mysqli_stmt_close($userStmt);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$attemptId = isset($_GET['attempt_id']) ? (int) $_GET['attempt_id'] : 0;
if ($attemptId <= 0) {
    header("Location: Quizzes.php");
    exit();
}

$certificateStmt = mysqli_prepare(
    $conn,
    "SELECT qa.attempt_id, qa.correct_answers, qa.total_questions, qa.points, qa.completed_at,
            c.course_name, c.level, u.fname, u.lname
     FROM quiz_attempts qa
     INNER JOIN courses c ON c.course_id = qa.course_id
     INNER JOIN users u ON u.id = qa.user_id
     WHERE qa.attempt_id = ? AND qa.user_id = ?
     LIMIT 1"
);
mysqli_stmt_bind_param($certificateStmt, "ii", $attemptId, $user['id']);
mysqli_stmt_execute($certificateStmt);
$certificateResult = mysqli_stmt_get_result($certificateStmt);
$certificate = mysqli_fetch_assoc($certificateResult);
mysqli_stmt_close($certificateStmt);
mysqli_close($conn);

if (!$certificate) {
    header("Location: Quizzes.php");
    exit();
}

$fullName = trim($certificate['fname'] . ' ' . $certificate['lname']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quiz Certificate</title>
<style>
body {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    background: linear-gradient(180deg, #fff7ed, #f8fafc);
    color: #1f2937;
    padding: 30px 18px;
}
.certificate-shell {
    max-width: 960px;
    margin: 0 auto;
}
.top-actions {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}
.top-actions a,
.top-actions button {
    border: none;
    border-radius: 999px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}
.top-actions a {
    background: #111827;
    color: #fff;
}
.top-actions button {
    background: linear-gradient(135deg, #1d4ed8, #9333ea);
    color: #fff;
}
.certificate {
    background: #fff;
    border: 14px solid #f59e0b;
    border-radius: 26px;
    padding: 44px 36px;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14);
    text-align: center;
}
.certificate h1 {
    margin: 0;
    font-size: 48px;
    color: #92400e;
    letter-spacing: 2px;
}
.certificate h2 {
    margin: 16px 0 10px;
    font-size: 26px;
}
.subline {
    font-size: 18px;
    color: #6b7280;
}
.student-name {
    margin: 22px 0 10px;
    font-size: 44px;
    color: #111827;
    font-weight: 700;
}
.course-name {
    margin: 16px 0;
    font-size: 30px;
    color: #4338ca;
    font-weight: 700;
}
.score-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin: 28px 0;
}
.score-box {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 18px;
}
.score-box strong {
    display: block;
    font-size: 30px;
    color: #111827;
}
.footer-line {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    text-align: left;
}
.sign-box {
    min-width: 220px;
}
.sign-line {
    border-top: 2px solid #1f2937;
    margin-top: 46px;
    padding-top: 8px;
    font-size: 14px;
}
@media (max-width: 760px) {
    .certificate {
        padding: 32px 20px;
    }

    .certificate h1 {
        font-size: 34px;
    }

    .student-name {
        font-size: 32px;
    }

    .course-name {
        font-size: 24px;
    }

    .score-grid {
        grid-template-columns: 1fr;
    }
}
@media print {
    body {
        background: #fff;
        padding: 0;
    }

    .top-actions {
        display: none;
    }

    .certificate {
        box-shadow: none;
        margin: 0;
    }
}
</style>
</head>
<body>
<div class="certificate-shell">
    <div class="top-actions">
        <a href="Quizzes.php?course_id=<?php echo isset($_GET['course_id']) ? (int) $_GET['course_id'] : 0; ?>">Back to Quizzes</a>
        <a href="download_certificate.php?attempt_id=<?php echo $attemptId; ?>" class="print-btn">
    Download Certificate
</a>
    </div>

    <section class="certificate">
        <h1>CERTIFICATE</h1>
        <h2>Certificate of Achievement</h2>
        <p class="subline">This certificate is proudly presented to</p>
        <div class="student-name"><?php echo htmlspecialchars($fullName !== '' ? $fullName : $certificate['fname']); ?></div>
        <p class="subline">for successfully completing the quiz for</p>
        <div class="course-name"><?php echo htmlspecialchars($certificate['course_name']); ?></div>

        <div class="score-grid">
            <div class="score-box">
                <span>Points</span>
                <strong><?php echo (int) $certificate['points']; ?></strong>
            </div>
            <div class="score-box">
                <span>Correct Answers</span>
                <strong><?php echo (int) $certificate['correct_answers']; ?>/<?php echo (int) $certificate['total_questions']; ?></strong>
            </div>
            <div class="score-box">
                <span>Issued On</span>
                <strong style="font-size: 20px;"><?php echo htmlspecialchars(date('d M Y', strtotime($certificate['completed_at']))); ?></strong>
            </div>
        </div>

        <div class="footer-line">
            <div class="sign-box">
                <div class="sign-line">Student Name: <?php echo htmlspecialchars($fullName !== '' ? $fullName : $certificate['fname']); ?></div>
            </div>
            <div class="sign-box">
                <div class="sign-line">SkillSpark Quiz Certificate</div>
            </div>
        </div>
    </section>
</div>
</body>
</html>
