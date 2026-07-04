<?php
session_start();
$conn = mysqli_connect("localhost","root","","skillspark");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

if(!isset($_SESSION['user_email'])){
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];
$query = "SELECT fname, lname, email, contact FROM users WHERE email=?";
$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if($result && mysqli_num_rows($result) > 0){
    $user = mysqli_fetch_assoc($result);
    $fullname = $user['fname'] . " " . $user['lname'];
    $email = $user['email'];
    $contact = $user['contact'];
}else{
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkillSpark</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* RESET - CLEAN */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* NAVBAR - FIXED */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 40px;
            background: linear-gradient(90deg, #2c1ccf, #ec5fc3);
            height: 70px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            top: 0;
            z-index: 100;
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

        .nav-right { gap: 15px; }

        .help-icon, .profile-circle {
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
        }

        .help-icon:hover, .profile-circle:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* POPUP */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup {
            background: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            max-width: 300px;
            width: 90%;
            transform: scale(0.8);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .overlay.active { display: flex; }
        .overlay.active .popup { transform: scale(1); opacity: 1; }

        .close-btn {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        /* HERO SECTION */
        .hero {
            background: linear-gradient(135deg, #5f8dff, #ff5fa2);
            padding: 80px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            margin: 0;
        }

        .hero-left { max-width: 50%; }
        .hero-right { position: relative; }

        .tag {
            background: rgba(255,255,255,0.25);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
            margin-bottom: 25px;
        }

        .hero h1 {
            font-size: 42px;
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .hero h1 span { color: #ffd84d; }

        .hero p {
            font-size: 16px;
            opacity: 0.95;
            margin-bottom: 30px;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-fill {
            background: #ffd84d;
            color: #000;
            border: none;
            padding: 12px 26px;
            border-radius: 25px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .stats-strip {
            display: flex;
            gap: 25px;
            font-size: 14px;
            opacity: 0.9;
        }

        .hero-right img {
            width: 400px;
            border-radius: 20px;
        }

        /* STATS */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            padding: 60px 80px;
            text-align: center;
        }

        .stat-card {
            background: white;
            color: #000;
            border-radius: 18px;
            padding: 30px 20px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h2 {
            font-size: 32px;
            color: #6a1bff;
            margin: 10px 0;
            font-weight: 700;
        }

        /* COURSES */
        .courses-section {
            padding: 80px 40px;
            background: linear-gradient(135deg, #60c6d8, #1c5983);
            text-align: center;
        }

        .courses-label {
            display: inline-block;
            padding: 8px 20px;
            font-size: 12px;
            font-weight: 600;
            color: #0f0f10;
            background: rgba(255,255,255,0.9);
            border-radius: 20px;
            margin-bottom: 15px;
        }

        .courses-section h2 {
            font-size: 36px;
            color: white;
            margin-bottom: 15px;
        }

        .courses-section p {
            color: rgba(255,255,255,0.9);
            font-size: 16px;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .courses-card {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .courses-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .courses-list li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 15px;
            font-size: 16px;
            color: #0f172a;
            font-weight: 500;
        }

        .courses-list li::before {
            content: "";
            width: 8px;
            height: 8px;
            background: #4f46e5;
            border-radius: 50%;
            position: absolute;
            left: 0;
            top: 8px;
        }

        .browse-btn {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .browse-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(79,70,229,0.4);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .navbar { padding: 12px 20px; }
            .nav-links { gap: 15px; }
            .nav-links a { font-size: 14px; padding: 6px 10px; }
            
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 60px 30px;
            }
            
            .hero-left {
                 max-width: 100%; 
                 margin-bottom: 30px; 
                }

            .stats {
                 grid-template-columns: 1fr; 
                 padding: 40px 20px;
                }

            .courses-list {
                 grid-template-columns: 1fr;
                }
        }
         .courses-card {
                padding: 30px 25px;
            }

            .courses-section h2 {
                font-size: 26px;
            }

.testimonials {
    padding: 60px 40px;
    background: #0d1b59; /* dark blue background */
    color: #000;
    text-align: center;
}

.testimonials .badge {
    display: inline-block;
    background: #facc15;
    color: #000;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 18px;
    border-radius: 20px;
    margin-bottom: 15px;
}

.testimonials h2 {
    font-size: 32px;
    margin-bottom: 40px;
    color: white;
    font-weight: 700;
}

.testimonial-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    justify-content: center;
}

.testimonial-card {
    background: white;
    border-radius: 20px;
    padding: 25px 20px;
    text-align: left;
    box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
}

.testimonial-card:hover {
    box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
     transition: all 0.35s ease;
}

.testimonial-card .avatar {
    width: 50px;
    height: 50px;
    background: #6c34f7; /* purple */
    color: white;
    border-radius: 50%;
    font-weight: bold;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
}

.testimonial-card h4 {
    font-weight: 700;
    font-size: 18px;
    margin-bottom: 10px;
    color: #000;
}

.testimonial-card p {
    font-size: 15px;
    line-height: 1.5;
    margin-bottom: 12px;
    color: #121212;
}

.testimonial-card .rating {
    color: #facc15;
    font-size: 18px;
    margin-bottom: 15px;
    display: block;
}

.testimonial-card .course-tag {
    display: inline-block;
    background: #e7e7ff;
    color: #6c34f7;
    font-weight: 600;
    font-size: 13px;
    padding: 6px 14px;
    border-radius: 20px;
    text-transform: capitalize;
}

/* Responsive */
@media (max-width: 768px) {
    .testimonial-grid {
        grid-template-columns: 1fr;
    }
}

        /* HERO SECTION */
        .cta-section {
            position: relative;
            height: 340px;
            background: url('student.jpg') center/cover no-repeat;
            display: block;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .cta-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-content h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .cta-content p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 25px;
        }

        .cta-button {
            background: #facc15;
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 18px;
            transition: 0.3s ease;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(250, 204, 21, 0.35);
             transition: all 0.35s ease;
        }

        .cta-features {
            font-size: 13px;
            opacity: 0.9;
        }

        .cta-features span {
            margin: 0 10px;
        }

       footer {
  background: linear-gradient(135deg, #346b84, #06b6d4);
  color: white;
  padding: 50px 60px 25px;
}

.footer-container {
  display: flex;
  justify-content: space-between; /* spread columns */
  align-items: flex-start;
  gap: 40px;
  padding: 40px 60px;
  color: white;
  max-width: 1100px;
  margin: 0 auto; /* center footer content */
}

.footer-column {
  flex: 1;
  max-width: 300px;
  text-align: center;
  padding: 0 20px;
  position: relative;
}

/* Add vertical lines between columns except last */
.footer-column:not(:last-child)::after {
  content: "";
  position: absolute;
  top: 10px;
  right: 0;
  height: 80%;
  width: 1px;
  background: rgba(255, 255, 255, 0.3);
}

/* Remove the line on the last column */
.footer-column.footer-contact::after {
  display: none;
}

.footer-column h3 {
  font-weight: 700;
  margin-bottom: 15px;
}

.footer-column ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-column li {
  margin-bottom: 8px;
  cursor: pointer;
  opacity: 0.85;
  transition: opacity 0.3s ease;
}

.footer-column li:hover {
  opacity: 1;
}

/* Responsive for smaller screens */
@media (max-width: 768px) {
  .footer-container {
    flex-direction: column;
    padding: 30px 20px;
  }

  .footer-column {
    max-width: 100%;
    padding: 15px 0;
  }

  .footer-column:not(:last-child)::after {
    display: none;
  }
}

        .site-footer { background: #009688; color: white; text-align: center; padding: 20px; }
.footer-links a { color: white; text-decoration: none; margin: 0 10px; font-weight: bold; }
.footer-links span { margin: 0 5px; }

        /* RESPONSIVE */
        @media (max-width: 768px) {

            footer {
                padding: 40px 25px 20px;
            }

            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-divider {
                display: none;
            }

            .footer-links {
                flex-direction: column;
                gap: 10px;
            }

            .cta-content h2 {
                font-size: 22px;
            }
        }

        .why-section {

            background: linear-gradient(135deg, #0639667e, #021051, #ec4899, #021051, #0639667e);
            color: white;
        }

        /* Heading */
        .why-heading {
            text-align: center;
            margin-bottom: 60px;
        }

        .why-heading h2 {
            font-size: 30px;
            margin-bottom: 12px;
            letter-spacing: 0.4px;

            /* Glow Effect */
            text-shadow:
                0 0 10px rgba(255, 255, 255, 0.7),
                0 0 22px rgba(255, 255, 255, 0.35);

            display: inline-block;
            padding: 10px 22px;
            border-radius: 12px;

            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(6px);
        }

        .why-heading p {
            font-size: 15px;
            opacity: 0.95;
        }

        /* Cards Layout */
        .why-container {
            display: none;
            gap: inherit;
            justify-content: flex-start;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        /* Glass Cards */
        .why-card {
            width: 300px;
            padding: 28px;
            border-radius: 20px;

            background: rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(20px);

            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.18),
                inset 0 0 0 1px rgba(255, 255, 255, 0.45);

            transition: all 0.35s ease;
        }

        .why-card:hover {
            transform: translateY(-10px);
        }

        .why-card h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #0f172a;

            background: rgba(255, 255, 255, 0.95);
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
        }

        .why-card p {
            font-size: 14px;
            line-height: 1.6;
            color: #0f172a;

            background: rgba(255, 255, 255, 0.95);
            padding: 12px;
            border-radius: 1px;
        }

        /* Responsive */
        @media (max-width: 768px) {

            .why-section {
                padding: 0%;
            }

            .why-container {
                justify-content: center;
            }

            .why-card {
                width: 100%;
                max-width: 340px;
            }

            .why-heading h2 {
                font-size: 24px;
            }
        }

        .why-container {
            display: flex;
            gap: 30px;
            justify-content: center;
            /* ✅ CENTER ALIGN */
            align-items: flex-start;
            flex-wrap: wrap;
        }

        body {
            margin: 0;
            min-height: 100vh;

            background: linear-gradient(to bottom, #ec4899, #6b47ee, #021051, #1c5983, #06b6d4);

            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .profile {
            width: 45px;
            height: 45px;
            background: #2563eb;
            color: rgb(252, 252, 252);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(52, 128, 147, 0.915);
            display: none;
            justify-content: center;
            align-items: center;
        }

        /* Popup */
        .popup {
            width: 300px;
            background: rgba(236, 238, 239, 0.997);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            position: relative;

            transform: scale(0.8);
            opacity: 0;
            transition: 0.3s ease;
        }

        /* Active State */
        .overlay.active {
            display: flex;
        }

        .overlay.active .popup {
            transform: scale(1);
            opacity: 1;
        }

        /* Close Button */
        .close-btn {
            position: absolute;
            right: 12px;
            top: 10px;
            cursor: pointer;
        }

        .popup h3 {
            color: rgb(17, 16, 16);
        }

        .popup p {
            color: #000;
        }

        .profile-info {
            color: #000;
        }

        .profile-pic {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
        }

    </style>
</head>
<body>
<?php include 'navigationbar.php'; ?>
    <!-- HERO -->
    <section class="hero">
        <div class="hero-left">
            <span class="tag">✨ Transform Your Career Today</span>
            <h1>Master Programming with <span>SkillSpark</span></h1>
            <p>Learn from industry experts with interactive courses, real-time quizzes, and hands-on projects. Start your journey to becoming a professional developer today!</p>
            <div class="hero-buttons">
                <a href="Courses.php" class="btn-outline">🚀 Start Learning Free</a>
                <a href="Subscription.php" class="btn-fill">🧾 Subscription</a>
            </div>
            <div class="stats-strip">
                <span>⭐ 4.9/5 Rating</span>
                <span>👩‍🎓 50K+ Students</span>
                <span>📚 10+ Courses</span>
            </div>
        </div>
        <div class="hero-right">
            <div class="image-bg"></div>
            <img src="student.jpg" alt="Students Learning">
        </div>
    </section>

    <!-- STATS -->
    <section class="stats">
        <div class="stat-card">
            👥<h2>50K+</h2><p>Active Students</p>
        </div>
        <div class="stat-card">
            📖<h2>10+</h2><p>Expert Courses</p>
        </div>
        <div class="stat-card">
            🎓<h2>95%</h2><p>Success Rate</p>
        </div>
        <div class="stat-card">
            ⭐<h2>4.9/5</h2><p>Average Rating</p>
        </div>
    </section>

    <!-- COURSES -->
    <section class="courses-section">
        <div class="courses-label">OUR COURSES</div>
        <h2>Available Courses</h2>
        <p>Choose from our comprehensive collection of programming courses designed by industry experts</p>
        <div class="courses-card">
            <div class="courses-list">
                <ul>
                    <li>Python</li>
                    <li>C++</li>
                    <li>Advanced JavaScript</li>
                    <li>Object Oriented System</li>
                    <li>Web App Development</li>
                </ul>
                <ul>
                    <li>C</li>
                    <li>JavaScript</li>
                    <li>Data Structure</li>
                    <li>Computer Organisation</li>
                    <li>Web Technology</li>
                </ul>
            </div>
            <a href="Courses.php">
                <button class="browse-btn">📘 Browse All Courses</button>
            </a>
        </div>
    </section>
 <!-- TESTIMONIALS -->
    <section class="testimonials">
        <span class="badge">STUDENT TESTIMONIALS</span>
        <h2>What Our Students Say</h2>

        <div class="testimonial-grid">
            <div class="testimonial-card">
                <div class="avatar">SK</div>
                <h4>Siddik Khan</h4>
                <p>Excellent course!The Python tutorials are very well structured and easy to follow.Highly recommend
                </p>
                <span class="rating">⭐⭐⭐⭐⭐</span>
                <span class="course-tag">Python</span>
            </div>

            <div class="testimonial-card">
                <div class="avatar">MP</div>
                <h4>Manasa Pidugu</h4>
                <p>"Great content on javascript. The examples are practical and the quizzes really help reinforce
                    learning".</p>
                <span class="rating">⭐⭐⭐⭐⭐</span>
                <span class="course-tag">Javascript</span>
            </div>

            <div class="testimonial-card">
                <div class="avatar">YK</div>
                <h4>Yasmin Khan</h4>
                <p>"The web development course is outstanding.I built my first fuu-stack app after completing it!".</p>
                <span class="rating">⭐⭐⭐⭐⭐</span>
                <span class="course-tag">Web App Development</span>
            </div>

            <div class="testimonial-card">
                <div class="avatar">AK</div>
                <h4>Anjusha Kokodkar</h4>
                <p>Data Structures course is incredibly detailed. The explaination make complex easy to grasp.</p>
                <span class="rating">⭐⭐⭐⭐⭐</span>
                <span class="course-tag">Data stuctures</span>
            </div>

            <div class="testimonial-card">
                <div class="avatar">AL</div>
                <h4>Akshata Lawande</h4>
                <p>"Advanced javascript helped me level up my skills. The instructor explains complex topics
                    brilliantly!"</p>
                <span class="rating">⭐⭐⭐⭐⭐</span>
                <span class="course-tag">Advanced javascript</span>
            </div>
        </div><br><br>

        <!-- HERO -->
        <section class="why-section">

            <div class="why-heading">
                <h2>Why Choose SkillSpark?</h2>
                <p>Everything you need to succeed in your programming journey</p>
            </div>

            <div class="why-container">

                <div class="why-card">
                    <h3>Interactive Learning</h3>
                    <p>
                        Hands-on coding exercises, real-time quizzes,
                        and instant feedback after each video lesson
                    </p>
                </div>

                <div class="why-card">
                    <h3>Expert Mentors</h3>
                    <p>
                        Learn from industry professionals with real-world
                        experience and practical teaching methods
                    </p>
                </div>

                <div class="why-card">
                    <h3>Career Focused</h3>
                    <p>
                        Job-ready skills, project-based learning,
                        and interview preparation support
                    </p>
                </div>

            </div>

        </section><br> <br>


        <!-- IMAGE / CTA -->
        <section class="cta-section">

            <div class="cta-overlay"></div>
            <br><br>

            <div class="cta-content">
                <h2>Ready to Spark Your Skills?</h2>
                <p>Join 50,000+ students already learning on SkillSpark. Start your free trial today!</p><br><br><br>


                <a href="Subscription.php">
                    <button class="cta-button">
                        🚀 Start Free Trial – No Credit Card Required
                    </button>
                </a>

                <div class="cta-features">
                    <span>7-day free trial</span> •
                    <span>Cancel anytime</span> •
                    <span>No hidden fees</span>
                </div>
            </div>

        </section>


        <!-- FOOTER -->
        <?php include 'footer.php'; ?>
<script>
        function openPopup() {
            document.getElementById("overlay").classList.add("active");
        }
        function closePopup() {
            document.getElementById("overlay").classList.remove("active");
        }
    </script>
</body>
</html>



