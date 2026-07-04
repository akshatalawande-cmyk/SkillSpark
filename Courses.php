<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "skillspark";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email']) && isset($_SESSION['user_email'])) {
    $_SESSION['email'] = $_SESSION['user_email'];
}

if (!isset($_SESSION['email'])) {
    $_SESSION['email'] = "student@example.com";
}

function course_is_free(array $courseRow, int $index): bool
{
    if (array_key_exists('access_type', $courseRow)) {
        return strtolower(trim((string) $courseRow['access_type'])) === 'free';
    }

    if (array_key_exists('is_free', $courseRow)) {
        $value = strtolower(trim((string) $courseRow['is_free']));
        return in_array($value, ['1', 'true', 'yes', 'free'], true);
    }

    return $index < 3;
}

$courseColumns = [
    'course_id',
    'course_name',
    'description',
    'duration',
    'level'
];

$columnResult = $conn->query("SHOW COLUMNS FROM courses");
if ($columnResult) {
    while ($column = $columnResult->fetch_assoc()) {
        if ($column['Field'] === 'access_type') {
            $courseColumns[] = 'access_type';
        }
        if ($column['Field'] === 'is_free') {
            $courseColumns[] = 'is_free';
        }
    }
}

$courses = [];
$courseQuery = "SELECT " . implode(", ", $courseColumns) . " FROM courses ORDER BY course_id ASC";
$courseResult = $conn->query($courseQuery);
if ($courseResult) {
    while ($row = $courseResult->fetch_assoc()) {
        $courses[] = $row;
    }
}

if (isset($_POST['enroll']) && isset($_POST['course'])) {
    $course = trim($_POST['course']);
    $email = $_SESSION['email'];

    $selectedCourse = null;
    foreach ($courses as $index => $courseRow) {
        if ($courseRow['course_name'] === $course) {
            $selectedCourse = $courseRow;
            $selectedIndex = $index;
            break;
        }
    }

    $plan = "Free";

$planStmt = $conn->prepare("
SELECT s.plan_name
FROM subscriptions s
INNER JOIN users u ON u.id = s.user_id
WHERE u.email = ?
ORDER BY s.subscription_id DESC
LIMIT 1
");
    if ($planStmt) {
        $planStmt->bind_param("s", $email);
        $planStmt->execute();
        $planResult = $planStmt->get_result();
        if ($planResult && $planResult->num_rows > 0) {
            $plan = $planResult->fetch_assoc()['plan_name'];
        }
        $planStmt->close();
    }

    $isFreeCourse = $selectedCourse ? course_is_free($selectedCourse, $selectedIndex ?? 999) : false;
    $allowed = $isFreeCourse || in_array($plan, ['Monthly Pro', 'Custom Yearly', 'Pro', 'Enterprise'], true);

    if (!$allowed) {
        header("Location: Subscription.php?course=" . urlencode($course));
        exit;
    }

    // Get logged-in user's ID
$userStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$userStmt->bind_param("s", $email);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userRow = $userResult->fetch_assoc();
$userStmt->close();

$userId = $userRow['id'];
$courseId = $selectedCourse['course_id'];

// Check if already enrolled
$checkStmt = $conn->prepare("
SELECT 1
FROM enrollments
WHERE user_id = ?
AND course_id = ?
");

$checkStmt->bind_param("ii", $userId, $courseId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows == 0) {

    $enrollStmt = $conn->prepare("
    INSERT INTO enrollments(user_id, course_id)
    VALUES(?, ?)
    ");

    $enrollStmt->bind_param("ii", $userId, $courseId);
    $enrollStmt->execute();
    $enrollStmt->close();

    // Add dashboard entry
    $progressStmt = $conn->prepare("
    INSERT INTO dashboard_progress(user_id, course_id, status, completion_percentage)
    VALUES (?, ?, 'Pending', 0)
    ");

    $progressStmt->bind_param("ii", $userId, $courseId);
    $progressStmt->execute();
    $progressStmt->close();
}

$checkStmt->close();
    $_SESSION['current_course'] = $course;
    header("Location: Courses.php?course=" . urlencode($course));
    exit;
}

$requestedCourse = $_GET['course'] ?? '';
$playlists = [

    "Full Stack Development" => "PLWKjhJtqVAbnSe1qUNMG7AbPmjIG54u88",

    "Python Programming" => "PLsyeobzWxl7poL9JTVyndKe62ieoN-MZ3",

    "Java Programming" => "PLsyeobzWxl7pe8uY4Q4rbsb88W7Z8aNDT",

    "Data Analytics" => "PL9ooVrP1hQOG6DQnOD6ujdCEchaqADfCU",

    "C Programming" => "PLsyeobzWxl7oBxHp43xQTFrw9f1CDPR6C",

    "C++ Programming" => "PLS1QulWo1RIYSyC6w2-rDssprPrEsgtVK",

    "JavaScript Advanced" => "PLillGF-RfqbbnEGy3ROiLWk7JMCuSyQtX",

    "Data Structures" => "PLVlQHNRLflP_OxF1QJoGBwH_TnZszHR_j",

    "Computer Organization" => "PLV8vIYTIdSnar4uzz-4TIlgyFJ2m18NE3",

    "Web Technology" => "PLL7liBDYa4Ya7cZyU0IYj3DSjinZ2Se8c"

];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SkillSpark Courses</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:Arial, sans-serif; }
    body { background: linear-gradient(180deg, #ec5fc3, #7fa6ff); color:white; min-height:100vh; }
    h1 { margin:20px 0; }
    .courses-section { padding:40px 20px; text-align:center; }
    .courses-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px; max-width:1200px; margin:auto; }
    .course-card { background: rgba(255,255,255,0.25); backdrop-filter: blur(6px); border-radius:16px; padding:18px; text-align:left; box-shadow:0 10px 20px rgba(0,0,0,0.1); transition:0.3s; }
    .course-card:hover { transform:translateY(-6px); }
    .course-card h3 { margin:10px 0 6px; }
    .details { background:#fff; padding:6px 10px; border-radius:8px; font-size:13px; margin-bottom:6px; color:black; }
    .rating { background:lightyellow; padding:6px 10px; border-radius:8px; font-size:13px; margin:10px 0; color:black; }
    .badge { color:white; font-size:12px; padding:4px 10px; border-radius:20px; display:inline-block; }
    .beginner { background:#2ecc71; }
    .intermediate { background:#f39c12; }
    .advanced { background:#e74c3c; }
    .default-level { background:#64748b; }
    .free-tag { background:#16a34a; margin-top:8px; }
    .pro-tag { background:#eab308; color:#1f2937; margin-top:8px; }
    button { width:100%; padding:10px; border:none; border-radius:8px; background:#3f51f5; color:white; cursor:pointer; }
    button:hover { background:#2c3ef5; }
    .video-section { display:none; text-align:center; padding:40px 20px; }
    iframe { width:100%; height:550px; border-radius:15px; margin-top:20px; }
    .back-btn { margin-top:20px; padding:10px 20px; border:none; border-radius:8px; background:#3f51f5; color:white; cursor:pointer; }
    .back-btn:hover { background:#2c3ef5; }
    .quiz-btn{
    display:inline-block;
    background:#4CAF50;
    color:white;
    padding:12px 30px;
    text-decoration:none;
    border-radius:8px;
    font-size:18px;
    font-weight:bold;
    transition:.3s;
}

.quiz-btn:hover{
    background:#2e7d32;
}
  </style>
</head>
<body>
<?php include 'navigationbar.php'; ?>

<section class="courses-section" id="coursesSection">
  <h1>Start Learning Today</h1>
  <div class="courses-grid">
    <?php if (!empty($courses)) { ?>
      <?php foreach ($courses as $index => $course) { ?>
        <?php
          $courseName = $course['course_name'];
          $courseId = $course['course_id'];
          $courseDescription = $course['description'];
          $courseDuration = $course['duration'];
          $courseLevel = strtolower(trim($course['level']));
          $levelClass = in_array($courseLevel, ['beginner', 'intermediate', 'advanced'], true) ? $courseLevel : 'default-level';
          $isFreeCourse = course_is_free($course, $index);
          $accessLabel = $isFreeCourse ? 'Free' : 'Pro';
          $accessClass = $isFreeCourse ? 'free-tag' : 'pro-tag';
        ?>
        <div class="course-card">
          <span class="badge <?php echo htmlspecialchars($levelClass); ?>"><?php echo htmlspecialchars(ucfirst($courseLevel)); ?></span>
          <h3><?php echo htmlspecialchars($courseName); ?></h3>
          <p><?php echo htmlspecialchars($courseDescription); ?></p>
          <div class="details">Course ID: <?php echo htmlspecialchars($courseId); ?></div>
          <div class="details">Duration: <?php echo htmlspecialchars($courseDuration); ?></div>
          <div class="rating">Level: <?php echo htmlspecialchars(ucfirst($courseLevel)); ?></div>
          <span class="badge <?php echo htmlspecialchars($accessClass); ?>"><?php echo htmlspecialchars($accessLabel); ?></span>
          <?php if ($isFreeCourse) { ?>
            <button type="button" onclick="startLearning('<?php echo htmlspecialchars($courseName, ENT_QUOTES); ?>')">Start Learning</button>
          <?php } else { ?>
            <form method="POST" style="margin:0; margin-top:10px;">
              <input type="hidden" name="course" value="<?php echo htmlspecialchars($courseName); ?>">
              <button type="submit" name="enroll">Start Learning</button>
            </form>
          <?php } ?>
        </div>
      <?php } ?>
    <?php } else { ?>
      <div class="course-card">
        <h3>No courses found</h3>
        <p>Add records to the courses table in the users database to show them here.</p>
      </div>
    <?php } ?>
  </div>
</section>

<section class="video-section" id="videoSection">
  <h1 id="courseTitle"></h1>
  <iframe id="playlistFrame" width="100%" height="550" frameborder="0" allowfullscreen></iframe>
  <div style="text-align:center; margin-top:25px;">
    <a id="quizBtn" href="Quizzes.php" class="quiz-btn">
        📝 Take Quiz
    </a>
</div>
  <br>
  <button class="back-btn" onclick="goBack()">Back to Courses</button>
</section>

<script>
const playlists = {
  "Python Programming":"PLsyeobzWxl7poL9JTVyndKe62ieoN-MZ3",
  "Java Programming":"PLsyeobzWxl7rrvgG7MLNIMSTzVCDZZcT4",
  "Web Technology":"PLL7liBDYa4Ya7cZyU0IYj3DSjinZ2Se8c",
  "C Programming":"PLsyeobzWxl7oBxHp43xQTFrw9f1CDPR6C",
  "C++ Programming":"PLS1QulWo1RIYSyC6w2-rDssprPrEsgtVK",
  "JavaScript Advanced":"PLillGF-RfqbbnEGy3ROiLWk7JMCuSyQtX",
  "Data Structures":"PLVlQHNRLflP_OxF1QJoGBwH_TnZszHR_j",
  "Object Oriented System":"PLfVsf4Bjg79DLA5K3GLbIwf3baNVFO2Lq",
  "Computer Organisation":"PLV8vIYTIdSnar4uzz-4TIlgyFJ2m18NE3",
  "Web App Development":"PLfqMhTWNBTe3H6c9OGXb5_6wcc1Mca52n",
  "Full Stack Development":"PLWKjhJtqVAbnSe1qUNMG7AbPmjIG54u88",
  "Data Analytics":"PL9ooVrP1hQOG6DQnOD6ujdCEchaqADfCU"
};

function startLearning(course){
  document.getElementById("coursesSection").style.display = "none";
  document.getElementById("videoSection").style.display = "block";
  document.getElementById("courseTitle").innerText = course;
  console.log(course);
  course = course.trim();

if (playlists[course]) {
    document.getElementById("playlistFrame").src =
        "https://www.youtube.com/embed/videoseries?list=" + playlists[course];
} else {
    document.getElementById("playlistFrame").src = "";
    document.getElementById("courseTitle").innerText = course + " playlist not available yet";
  }
}

function goBack(){
  document.getElementById("videoSection").style.display = "none";
  document.getElementById("coursesSection").style.display = "block";
  document.getElementById("playlistFrame").src = "";
}

const requestedCourse = <?php echo json_encode($requestedCourse); ?>;
if (requestedCourse) {
  startLearning(requestedCourse);
}
</script>
<?php include 'footer.php'; ?>
</body>
</html>
