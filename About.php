<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "skillspark");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$userCount = 0;
$courseCount = 0;

$userCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
if ($userCountResult) {
    $userCount = (int) mysqli_fetch_assoc($userCountResult)['total_users'];
}

$courseCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total_courses FROM courses");
if ($courseCountResult) {
    $courseCount = (int) mysqli_fetch_assoc($courseCountResult)['total_courses'];
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About SkillSpark</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family: Arial, Helvetica, sans-serif;
}

body{
  background:#f5f5f5;
  color:#333;
}

h1,h2{
  margin-bottom:15px;
}

p{
  line-height:1.6;
  margin-bottom:10px;
}

.hero {
    background: linear-gradient(135deg, #ff9a9e,#7b6cf6 );
    padding: 60px 0;
    text-align: center;
}

.stats {
    display: flex;
    justify-content: space-between;
    margin: 40px auto;
    flex-wrap: wrap;
}

.card {
    background: white;
    padding: 20px;
    width: 22%;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.stats.container .card:nth-child(1) { background-color: lightgreen; }
.stats.container .card:nth-child(2) { background-color: lightblue; }
.stats.container .card:nth-child(3) { background-color: lightpink; }
.stats.container .card:nth-child(4) { background-color: lightyellow; }

.stat-icon {
    font-size: 28px;
    color: #6a11cb;
    margin-bottom: 10px;
}

.values {
    background: linear-gradient(135deg, #ff9a9e,#7b6cf6 );
    padding: 50px 0;
    text-align: center;
}

.grid {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 30px;
}

.box {
    background: white;
    padding: 20px;
    width: 22%;
    border-radius: 10px;
}

.value-icon {
    font-size: 28px;
    color: #2575fc;
    margin-bottom: 10px;
}

.team {
    text-align: center;
    padding: 50px 0;
    background:linear-gradient(135deg,pink, lightblue);
}

.team-card {
    background: linear-gradient(135deg, #ff9a9e,#7b6cf6 );
    padding: 20px;
    width: 30%;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.avatar {
    background: #6a11cb;
    color: white;
    width: 60px;
    height: 60px;
    line-height: 60px;
    border-radius: 50%;
    margin: auto;
    font-weight: bold;
}

.social i {
    margin: 10px;
    cursor: pointer;
}

.story{
  background: linear-gradient(135deg, #ff9a9e,#7b6cf6 );
  padding:60px 8%;
  display:flex;
  flex-wrap:wrap;
  gap:40px;
  align-items:center;
  color:white;
  text-align:left;
}

.story img{
  width:100%;
  max-width:400px;
  border-radius:15px;
}

.choose{
  padding:60px 8%;
  background:#d7ccff;
  text-align:center;
}

.card-container {
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
  margin-top: 30px;
}

.card h3 {
  margin-bottom: 15px;
  color: #333;
}

.card p {
  color: #666;
  font-size: 14px;
}

.cta{
  background:#3c4bd9;
  color:white;
  padding:60px 8%;
  text-align:center;
}

.btn{
  padding:12px 25px;
  border:none;
  border-radius:8px;
  margin:10px;
  font-size:16px;
  cursor:pointer;
  transition:0.3s;
}

.btn-primary{
  background:white;
  color:#3c4bd9;
}

.btn-secondary{
  background:#6b74f7;
  color:white;
}

.btn:hover{
  opacity:0.85;
}

@media(max-width:768px){
  .story{
    flex-direction:column;
    text-align:center;
  }

  .card,
  .box,
  .team-card {
    width: 100%;
    max-width: 340px;
  }
}
</style>
</head>
<body>
<?php include 'navigationbar.php'; ?>

<section class="hero">
    <div class="container">
        <h1><i class="fas fa-lightbulb"></i> About SkillSpark</h1>
        <p>We are on a mission to democratize programming education and make it accessible to everyone.</p>
    </div>
</section>

<section class="stats container">
    <div class="card">
        <i class="fas fa-calendar-alt stat-icon"></i>
        <h3>2020</h3>
        <span>Founded</span>
    </div>

    <div class="card">
        <i class="fas fa-user-graduate stat-icon"></i>
        <h3><?php echo $userCount; ?>+</h3>
        <span>Students</span>
    </div>

    <div class="card">
        <i class="fas fa-laptop-code stat-icon"></i>
        <h3><?php echo $courseCount; ?>+</h3>
        <span>Courses</span>
    </div>

    <div class="card">
        <i class="fas fa-chart-line stat-icon"></i>
        <h3>95%</h3>
        <span>Success Rate</span>
    </div>
</section>

<section class="values">
    <div class="container">
        <h2>Our Core Values</h2>
        <p style="text-align:center;">The principles that guide everthing we do</p>
        <div class="grid">
            <div class="box">
                <i class="fas fa-bullseye value-icon"></i>
                <h3>Mission Driven</h3>
                <p>Empowering learners through quality programming education.</p>
            </div>

            <div class="box">
                <i class="fas fa-heart value-icon"></i>
                <h3>Student First</h3>
                <p>We prioritize student success and learning experience.</p>
            </div>

            <div class="box">
                <i class="fas fa-bolt value-icon"></i>
                <h3>Innovation</h3>
                <p>Modern platform with updated technologies.</p>
            </div>

            <div class="box">
                <i class="fas fa-globe value-icon"></i>
                <h3>Global Community</h3>
                <p>Worldwide network of learners and educators.</p>
            </div>
        </div>
    </div>
</section>

<section class="team container">
    <h2>Meet Our Team</h2>
    <p>Passinate educators and technologists dedicated to your success</p>
    <div class="grid">
        <div class="team-card">
            <div class="avatar">SJ</div>
            <h4>Sarah Johnson</h4>
            <p>CEO & Founder</p>
            <div class="social">
                <i class="fab fa-linkedin"></i>
                <i class="fab fa-twitter"></i>
            </div>
        </div>

        <div class="team-card">
            <div class="avatar">MC</div>
            <h4>Michael Chen</h4>
            <p>CTO</p>
            <div class="social">
                <i class="fab fa-linkedin"></i>
                <i class="fab fa-twitter"></i>
            </div>
        </div>

        <div class="team-card">
            <div class="avatar">ER</div>
            <h4>Emma Rodriguez</h4>
            <p>Head of Learning</p>
            <div class="social">
                <i class="fab fa-linkedin"></i>
                <i class="fab fa-twitter"></i>
            </div>
        </div>
    </div>
</section>

<section class="story">
  <div style="display: flex; align-items: flex-start; gap: 20px;">
    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c" alt="Team" style="max-width:50%; height:auto;">
  </div>
  <div>
    <h2>Our Story</h2>
    <p>SkillSpark was born out of a simple observation: traditional programming education wasn't working for everyone. Many aspiring developers were held back by expensive bootcamps, rigid schedules, and outdated teaching methods.</p>
    <p>We envisioned a platform that would break down these barriers-making quality programming education flexible, affordable, and engaging. A place where anyone, regardless of their background, could learn to code and build a tech career.</p>
    <p>Today, SkillSpark serves students in over 120 countries, offering interactive courses, real-time quizzes, and comprehensive tests. But our mission remains the same: to spark your skills and ignite your potential.</p>
  </div>
</section>

<section class="choose">
  <h2 style="text-align:center;">Why Choose SkillSpark?</h2>

  <div class="card-container">
    <div class="card">
      <h3>Trusted Learning</h3>
      <p>Industry-vetted curriculum with real-world experience.</p>
    </div>

    <div class="card">
      <h3>Interactive Platform</h3>
      <p>Hands-on exercises and projects to reinforce learning.</p>
    </div>

    <div class="card">
      <h3>Career Support</h3>
      <p>Certificates and career guidance to help you succeed.</p>
    </div>
  </div>
</section>

<section class="cta">
  <h2>Ready to Start Your Journey?</h2>
  <p>Join thousands of students transforming their careers</p>

  <button class="btn btn-primary" onclick="scrollTopPage()">Get Started Free</button>
  <button class="btn btn-secondary">Contact Us</button>
</section>

<script>
function scrollTopPage(){
  window.scrollTo({
    top:0,
    behavior:"smooth"
  });
}

</script>
<?php include 'footer.php'; ?>
</body>
</html>


