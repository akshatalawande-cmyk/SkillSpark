<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "skillspark");
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

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

$progressRows = [];
$progressStmt = mysqli_prepare(
    $conn,
    "SELECT c.course_id, c.course_name, c.level, c.duration,
            COALESCE(dp.status, 'Pending') AS progress_status,
            COALESCE(dp.completion_percentage, 0) AS completion_percentage
     FROM courses c
     LEFT JOIN dashboard_progress dp
       ON dp.course_id = c.course_id AND dp.user_id = ?
     ORDER BY c.course_id ASC"
);

if (!$progressStmt) {
    die("Database error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($progressStmt, "i", $user['id']);
mysqli_stmt_execute($progressStmt);
$progressResult = mysqli_stmt_get_result($progressStmt);
while ($row = mysqli_fetch_assoc($progressResult)) {
    $progressRows[] = $row;
}
mysqli_stmt_close($progressStmt);

$enrolledCount = count($progressRows);
$completedCount = 0;
$totalCompletion = 0;
$totalHours = 0;
$recentActivity = [];

foreach ($progressRows as $row) {
    $courseName = trim((string) ($row['course_name'] ?? ''));
    if ($courseName === '') {
        $courseName = 'Untitled Course';
    }

    $completion = max(0, min(100, (int) $row['completion_percentage']));
    $status = trim((string) ($row['progress_status'] ?? 'Pending'));
    if ($status === 'Pending' && $completion === 0) {
        $status = 'Not Started';
    }

    $totalCompletion += $completion;
    $totalHours += round(($completion / 100) * 10, 1);

    if ($status === 'Completed' || $completion >= 100) {
        $completedCount++;
        $recentActivity[] = $courseName . ' - Completed';
    } elseif ($completion > 0) {
        $recentActivity[] = $courseName . ' - ' . $completion . '% completed';
    }
}

$averageScore = $enrolledCount > 0 ? round($totalCompletion / $enrolledCount) : 0;
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
* {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: linear-gradient(180deg, #ec5fc3, #7fa6ff);
  padding: 30px;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.header {
  background: linear-gradient(135deg, #5f8dff, #ff5fa2);
  padding: 25px;
  border-radius: 12px;
  margin-bottom: 25px;
  color: white;
}

.header p {
  margin-top: 8px;
}

.stats {
  display: flex;
  gap: 20px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.stat-box {
  background: #fff;
  padding: 15px 20px;
  border-radius: 10px;
  min-width: 150px;
  text-align: center;
  font-weight: bold;
  color: #22183a;
}

.main {
  display: flex;
  gap: 25px;
  flex: 1;
}

.courses {
  flex: 2;
  background: rgba(255,255,255,0.25);
  padding: 20px;
  border-radius: 15px;
}

.courses > h3,
.side h3 {
  color: white;
  margin-bottom: 16px;
}

.course {
  background: #fff;
  padding: 18px;
  border-radius: 12px;
  margin-bottom: 15px;
}

.course-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}

.course-title {
  color: #1f2937;
  margin-bottom: 0;
}

.course-meta {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 8px;
}

.course-chip {
  background: #f2ebff;
  color: #5b21b6;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 700;
}

.progress-bar {
  height: 10px;
  background: #ddd;
  border-radius: 10px;
  margin-top: 14px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #7c3aed, #2563eb);
}

.progress-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-top: 8px;
}

.status-text {
  font-size: 13px;
  font-weight: bold;
  color: #4b5563;
}

.progress-percent {
  font-size: 13px;
  font-weight: 700;
  color: #4338ca;
}

.continue-btn {
  background: #000;
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  text-decoration: none;
}

.side {
  flex: 1;
}

.box {
  background: #fff;
  padding: 20px;
  border-radius: 15px;
  margin-bottom: 20px;
}

.activity-item {
  margin-bottom: 10px;
  color: #3f3f46;
}

.empty-state {
  color: #4b5563;
}

@media (max-width: 900px) {
  body {
    padding: 18px;
  }

  .main {
    flex-direction: column;
  }

  .course-top {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
</head>
<body>
<?php include 'navigationbar.php'; ?>

<div class="header">
  <h2>Welcome Back <?php echo htmlspecialchars($user['fname']); ?>!</h2>
  <p>Here is your learning progress from the database.</p>
  <div class="stats">
    <div class="stat-box"><?php echo $enrolledCount; ?><br>Enrolled</div>
    <div class="stat-box"><?php echo $completedCount; ?><br>Completed</div>
    <div class="stat-box"><?php echo $totalHours; ?><br>Hours Learned</div>
    <div class="stat-box"><?php echo $averageScore; ?>%<br>Average Score</div>
  </div>
</div>

<div class="main">
  <div class="courses">
    <h3>My Courses</h3>

    <?php if (!empty($progressRows)) { ?>
      <?php foreach ($progressRows as $row) { ?>
        <?php
          $courseName = trim((string) ($row['course_name'] ?? ''));
          if ($courseName === '') {
            $courseName = 'Untitled Course';
          }
          $completion = max(0, min(100, (int) $row['completion_percentage']));
          $status = trim((string) ($row['progress_status'] ?? 'Pending'));
          if ($status === 'Pending' && $completion === 0) {
            $status = 'Not Started';
          }
        ?>
        <div class="course">
          <div class="course-top">
            <div>
              <h3 class="course-title"><?php echo htmlspecialchars($courseName); ?></h3>
              <div class="course-meta">
                <span class="course-chip"><?php echo htmlspecialchars($row['level']); ?></span>
                <span class="course-chip"><?php echo htmlspecialchars($row['duration']); ?></span>
              </div>
            </div>
            <a class="continue-btn" href="Courses.php">Continue</a>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" style="width: <?php echo $completion; ?>%;"></div>
          </div>
          <div class="progress-meta">
            <div class="status-text"><?php echo htmlspecialchars($status); ?></div>
            <div class="progress-percent"><?php echo $completion; ?>%</div>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <div class="course empty-state">
        No courses or progress records found yet.
      </div>
    <?php } ?>
  </div>

  <div class="side">
    <div class="box">
      <h3>Recent Activity</h3>
      <?php if (!empty($recentActivity)) { ?>
        <?php foreach ($recentActivity as $activity) { ?>
          <p class="activity-item"><?php echo htmlspecialchars($activity); ?></p>
        <?php } ?>
      <?php } else { ?>
        <p class="empty-state">No recent activity</p>
      <?php } ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
