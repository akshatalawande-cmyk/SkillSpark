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
if (!$userStmt) {
    die("Database error: " . mysqli_error($conn));
}
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

$courseStats = [];
$courseStatsStmt = mysqli_prepare(
    $conn,
    "SELECT c.course_id, c.course_name, c.level, c.duration,
            COUNT(DISTINCT q.quiz_id) AS quiz_total,
            COALESCE(MAX(qa.points), 0) AS best_points,
            MAX(qa.attempt_id) AS latest_attempt_id,
            MAX(qa.completed_at) AS latest_attempt_at
     FROM courses c
     LEFT JOIN course_quizzes q ON q.course_id = c.course_id
     LEFT JOIN quiz_attempts qa ON qa.course_id = c.course_id AND qa.user_id = ?
     GROUP BY c.course_id, c.course_name, c.level, c.duration
     ORDER BY c.course_id ASC"
);
mysqli_stmt_bind_param($courseStatsStmt, "i", $user['id']);
mysqli_stmt_execute($courseStatsStmt);
$courseStatsResult = mysqli_stmt_get_result($courseStatsStmt);
while ($row = mysqli_fetch_assoc($courseStatsResult)) {
    $courseStats[] = $row;
}
mysqli_stmt_close($courseStatsStmt);

$selectedCourseId = isset($_GET['course_id']) ? (int) $_GET['course_id'] : 0;
if ($selectedCourseId === 0 && !empty($courseStats)) {
    $selectedCourseId = (int) $courseStats[0]['course_id'];
}

$selectedCourse = null;
foreach ($courseStats as $courseRow) {
    if ((int) $courseRow['course_id'] === $selectedCourseId) {
        $selectedCourse = $courseRow;
        break;
    }
}

$resultAttempt = null;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {
    $selectedCourseId = (int) ($_POST['course_id'] ?? 0);
    $questionStmt = mysqli_prepare(
        $conn,
        "SELECT quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option
         FROM course_quizzes
         WHERE course_id = ?
         ORDER BY quiz_id ASC
         LIMIT 5"
    );
    mysqli_stmt_bind_param($questionStmt, "i", $selectedCourseId);
    mysqli_stmt_execute($questionStmt);
    $questionResult = mysqli_stmt_get_result($questionStmt);

    $submittedQuestions = [];
    while ($question = mysqli_fetch_assoc($questionResult)) {
        $submittedQuestions[] = $question;
    }
    mysqli_stmt_close($questionStmt);

    if (count($submittedQuestions) === 5) {
        $answers = $_POST['answers'] ?? [];
        $correctAnswers = 0;

        foreach ($submittedQuestions as $question) {
            $questionId = (int) $question['quiz_id'];
            $selectedAnswer = strtoupper(trim((string) ($answers[$questionId] ?? '')));
            if ($selectedAnswer !== '' && $selectedAnswer === strtoupper($question['correct_option'])) {
                $correctAnswers++;
            }
        }

        $points = $correctAnswers * 20;
        $insertAttemptStmt = mysqli_prepare(
            $conn,
            "INSERT INTO quiz_attempts (user_id, course_id, total_questions, correct_answers, points)
             VALUES (?, ?, 5, ?, ?)"
        );
        mysqli_stmt_bind_param($insertAttemptStmt, "iiii", $user['id'], $selectedCourseId, $correctAnswers, $points);
        mysqli_stmt_execute($insertAttemptStmt);
        $attemptId = (int) mysqli_insert_id($conn);
        mysqli_stmt_close($insertAttemptStmt);

        header("Location: Quizzes.php?course_id=" . $selectedCourseId . "&attempt_id=" . $attemptId . "&submitted=1");
        exit();
    }

    $errorMessage = 'Quiz questions could not be loaded for this course.';
}

$attemptId = isset($_GET['attempt_id']) ? (int) $_GET['attempt_id'] : 0;
if ($attemptId > 0) {
    $attemptStmt = mysqli_prepare(
        $conn,
        "SELECT qa.attempt_id, qa.correct_answers, qa.total_questions, qa.points, qa.completed_at,
                c.course_name, c.course_id
         FROM quiz_attempts qa
         INNER JOIN courses c ON c.course_id = qa.course_id
         WHERE qa.attempt_id = ? AND qa.user_id = ?
         LIMIT 1"
    );
    mysqli_stmt_bind_param($attemptStmt, "ii", $attemptId, $user['id']);
    mysqli_stmt_execute($attemptStmt);
    $attemptResult = mysqli_stmt_get_result($attemptStmt);
    $resultAttempt = mysqli_fetch_assoc($attemptResult);
    mysqli_stmt_close($attemptStmt);
}

$questions = [];
if ($selectedCourseId > 0) {
    $selectedQuestionStmt = mysqli_prepare(
        $conn,
        "SELECT quiz_id, question_text, option_a, option_b, option_c, option_d
         FROM course_quizzes
         WHERE course_id = ?
         ORDER BY quiz_id ASC
         LIMIT 5"
    );
    mysqli_stmt_bind_param($selectedQuestionStmt, "i", $selectedCourseId);
    mysqli_stmt_execute($selectedQuestionStmt);
    $selectedQuestionResult = mysqli_stmt_get_result($selectedQuestionStmt);
    while ($question = mysqli_fetch_assoc($selectedQuestionResult)) {
        $questions[] = $question;
    }
    mysqli_stmt_close($selectedQuestionStmt);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Course Quizzes</title>
<style>
* { box-sizing: border-box; }
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(180deg, #ffe6f0 0%, #eef4ff 55%, #ffffff 100%);
    color: #1f2937;
}
.main-wrap {
    max-width: 1220px;
    margin: 0 auto;
    padding: 28px 20px 40px;
}
.hero {
    background: linear-gradient(135deg, #0f172a, #4338ca 58%, #ec4899);
    color: #fff;
    border-radius: 24px;
    padding: 28px;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
}
.hero h1 {
    margin: 0 0 10px;
    font-size: 34px;
}
.hero p {
    margin: 0;
    max-width: 720px;
    line-height: 1.6;
}
.layout {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 24px;
    margin-top: 24px;
}
.sidebar-card,
.quiz-panel,
.result-card {
    background: rgba(255, 255, 255, 0.92);
    border-radius: 22px;
    box-shadow: 0 18px 40px rgba(148, 163, 184, 0.18);
}
.sidebar-card {
    padding: 18px;
    align-self: start;
}
.course-list {
    display: grid;
    gap: 14px;
}
.course-item {
    display: block;
    text-decoration: none;
    padding: 16px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: inherit;
}
.course-item.active {
    background: linear-gradient(135deg, #312e81, #7c3aed);
    color: #fff;
    border-color: transparent;
}
.course-item h3 {
    margin: 0 0 8px;
    font-size: 18px;
}
.course-meta,
.course-points {
    font-size: 13px;
    opacity: 0.92;
}
.content-column {
    display: grid;
    gap: 22px;
}
.result-card,
.quiz-panel {
    padding: 24px;
}
.result-card {
    background: linear-gradient(135deg, #dcfce7, #eff6ff);
}
.result-card h2,
.quiz-panel h2,
.sidebar-card h2 {
    margin-top: 0;
}
.result-stats {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin: 16px 0;
}
.stat-pill {
    background: rgba(255,255,255,0.8);
    border-radius: 999px;
    padding: 10px 16px;
    font-weight: 700;
}
.certificate-btn,
.submit-btn {
    display: inline-block;
    background: #111827;
    color: #fff;
    text-decoration: none;
    border: none;
    border-radius: 999px;
    padding: 12px 20px;
    font-weight: 700;
    cursor: pointer;
}
.certificate-btn {
    background: linear-gradient(135deg, #111827, #4338ca);
}
.form-note,
.error-note {
    margin-bottom: 16px;
    padding: 14px 16px;
    border-radius: 16px;
}
.form-note {
    background: #eef2ff;
    color: #312e81;
}
.error-note {
    background: #fee2e2;
    color: #b91c1c;
}
.question-card {
    padding: 18px;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    margin-bottom: 16px;
    background: #fff;
}
.question-card h3 {
    margin-top: 0;
    font-size: 18px;
}
.option-list {
    display: grid;
    gap: 10px;
    margin-top: 14px;
}
.option-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    border-radius: 14px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
}
.option-item input {
    margin: 0;
}
.empty-state {
    color: #64748b;
}
@media (max-width: 900px) {
    .layout {
        grid-template-columns: 1fr;
    }

    .hero h1 {
        font-size: 28px;
    }
}
</style>
</head>
<body>
<?php include 'navigationbar.php'; ?>
<div class="main-wrap">
    <section class="hero">
        <h1>Quizzes & Certificates</h1>
        <p>Select any course, answer 5 quiz questions, earn points, and generate a certificate with your name, course name, and score.</p>
    </section>

    <div class="layout">
        <aside class="sidebar-card">
            <h2>All Course Quizzes</h2>
            <div class="course-list">
                <?php foreach ($courseStats as $courseRow): ?>
                    <?php $isActive = (int) $courseRow['course_id'] === $selectedCourseId; ?>
                    <a class="course-item<?php echo $isActive ? ' active' : ''; ?>" href="Quizzes.php?course_id=<?php echo (int) $courseRow['course_id']; ?>">
                        <h3><?php echo htmlspecialchars($courseRow['course_name']); ?></h3>
                        <div class="course-meta"><?php echo htmlspecialchars($courseRow['level']); ?> � <?php echo htmlspecialchars($courseRow['duration']); ?></div>
                        <div class="course-points"><?php echo (int) $courseRow['quiz_total']; ?> questions � Best points: <?php echo (int) $courseRow['best_points']; ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </aside>

        <div class="content-column">
            <?php if ($resultAttempt): ?>
                <section class="result-card">
                    <h2>Latest Quiz Result</h2>
                    <p><strong><?php echo htmlspecialchars($resultAttempt['course_name']); ?></strong> completed successfully.</p>
                    <div class="result-stats">
                        <div class="stat-pill"><?php echo (int) $resultAttempt['correct_answers']; ?>/<?php echo (int) $resultAttempt['total_questions']; ?> correct</div>
                        <div class="stat-pill"><?php echo (int) $resultAttempt['points']; ?> points</div>
                        <div class="stat-pill"><?php echo htmlspecialchars(date('d M Y h:i A', strtotime($resultAttempt['completed_at']))); ?></div>
                    </div>
                    <a class="certificate-btn" href="Certificate.php?attempt_id=<?php echo (int) $resultAttempt['attempt_id']; ?>">Generate Certificate</a>
                </section>
            <?php endif; ?>

            <section class="quiz-panel">
                <h2><?php echo $selectedCourse ? htmlspecialchars($selectedCourse['course_name']) : 'Course Quiz'; ?></h2>

                <?php if ($errorMessage !== ''): ?>
                    <div class="error-note"><?php echo htmlspecialchars($errorMessage); ?></div>
                <?php endif; ?>

                <?php if ($selectedCourse && !empty($questions)): ?>
                    <div class="form-note">This quiz contains 5 questions. Each correct answer gives 20 points.</div>
                    <form method="post">
                        <input type="hidden" name="course_id" value="<?php echo (int) $selectedCourseId; ?>">
                        <?php foreach ($questions as $index => $question): ?>
                            <div class="question-card">
                                <h3>Question <?php echo $index + 1; ?></h3>
                                <p><?php echo htmlspecialchars($question['question_text']); ?></p>
                                <div class="option-list">
                                    <?php foreach (['A', 'B', 'C', 'D'] as $optionKey): ?>
                                        <?php $optionField = 'option_' . strtolower($optionKey); ?>
                                        <label class="option-item">
                                            <input type="radio" name="answers[<?php echo (int) $question['quiz_id']; ?>]" value="<?php echo $optionKey; ?>" required>
                                            <span><strong><?php echo $optionKey; ?>.</strong> <?php echo htmlspecialchars($question[$optionField]); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <button class="submit-btn" type="submit" name="submit_quiz">Submit Quiz</button>
                    </form>
                <?php else: ?>
                    <p class="empty-state">No quiz is available for this course yet.</p>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
