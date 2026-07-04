<?php
session_start();
$conn = mysqli_connect("localhost","root","","skillspark");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

$isLoggedIn = isset($_SESSION['user_email']);
$fullname = "Guest User";
$email = "";
$contact = "";

if($isLoggedIn){
    $user_email = $_SESSION['user_email'];
    $query = "SELECT fname, lname, email, contact FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $query);

    if($stmt){
        mysqli_stmt_bind_param($stmt, "s", $user_email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if($result && mysqli_num_rows($result) > 0){
            $user = mysqli_fetch_assoc($result);
            $fullname = $user['fname'] . " " . $user['lname'];
            $email = $user['email'];
            $contact = $user['contact'];
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SkillSpark</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
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
            flex-wrap: wrap;
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
            flex-wrap: wrap;
        }

        .hero-right img {
            width: 400px;
            border-radius: 20px;
        }

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
            text-align: left;
        }

        .course-link {
            width: 100%;
            background: transparent;
            border: none;
            text-align: left;
            position: relative;
            padding-left: 25px;
            margin-bottom: 15px;
            font-size: 16px;
            color: #0f172a;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
            text-decoration: none;
            display: inline-block;
        }

        .course-link::before {
            content: "";
            width: 8px;
            height: 8px;
            background: #4f46e5;
            border-radius: 50%;
            position: absolute;
            left: 0;
            top: 10px;
        }

        .course-link:hover {
            color: #4f46e5;
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

        .guest-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1300;
        }

        .guest-overlay.active {
            display: flex;
        }

        .guest-popup {
            width: 380px;
            max-width: 100%;
            background: #fff;
            border-radius: 22px;
            padding: 28px 24px 24px;
            text-align: center;
            position: relative;
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.28);
        }

        .guest-close {
            position: absolute;
            top: 12px;
            right: 14px;
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
            background: #eef2ff;
            color: #475569;
            font-size: 20px;
            cursor: pointer;
        }

        .guest-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 14px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #ec5fc3);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
        }

        .guest-popup h3 {
            font-size: 24px;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .guest-popup p {
            color: #475569;
            margin-bottom: 20px;
        }

        .guest-login-btn {
            display: inline-block;
            text-decoration: none;
            padding: 12px 26px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 60px 30px;
            }

            .hero-left { max-width: 100%; margin-bottom: 30px; }
            .stats { grid-template-columns: 1fr; padding: 40px 20px; }
            .courses-list { grid-template-columns: 1fr; }
            .hero-buttons,
            .stats-strip { justify-content: center; }
        }
    .testimonials {
    padding: 60px 40px;
   background: linear-gradient(135deg, #a0def9, #4b1d6b, #0f2f76);

 /* dark blue background */
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

    </style>
</head>
<body>
<?php include 'userview_navigation.php'; ?>
    <section class="hero">
        <div class="hero-left">
            <span class="tag">Transform Your Career Today</span>
            <h1>Master Programming with <span>SkillSpark</span></h1>
            <p>Learn from industry experts with interactive courses, real-time quizzes, and hands-on projects. Start your journey to becoming a professional developer today!</p>
            <div class="hero-buttons">
                <?php if ($isLoggedIn): ?>
                    <a href="Courses.php" class="btn-outline">Start Learning Free</a>
                    <a href="Subscription.php" class="btn-fill">Subscription</a>
                <?php else: ?>
                    <button type="button" class="btn-outline" onclick="openGuestPopup('videos')">Start Learning Free</button>
                    <button type="button" class="btn-fill" onclick="openGuestPopup('subscription')">Subscription</button>
                <?php endif; ?>
            </div>
            <div class="stats-strip">
                <span>4.9/5 Rating</span>
                <span>50K+ Students</span>
                <span>10+ Courses</span>
            </div>
        </div>
        <div class="hero-right">
            <img src="student.jpg" alt="Students Learning">
        </div>
    </section>

    <section class="stats">
        <div class="stat-card">
            <h2>50K+</h2><p>Active Students</p>
        </div>
        <div class="stat-card">
            <h2>10+</h2><p>Expert Courses</p>
        </div>
        <div class="stat-card">
            <h2>95%</h2><p>Success Rate</p>
        </div>
        <div class="stat-card">
            <h2>4.9/5</h2><p>Average Rating</p>
        </div>
    </section>

    <section class="courses-section">
        <div class="courses-label">OUR COURSES</div>
        <h2>Available Courses</h2>
        <p>Choose from our comprehensive collection of programming courses designed by industry experts</p>
        <div class="courses-card">
            <div class="courses-list">
                <ul>
                    <li><a class="course-link" href="Courses.php">Python</a></li>
                    <li><a class="course-link" href="Courses.php">C++</a></li>
                    <li><a class="course-link" href="Courses.php">Advanced JavaScript</a></li>
                    <li><a class="course-link" href="Courses.php">Object Oriented System</a></li>
                    <li><a class="course-link" href="Courses.php">Web App Development</a></li>
                </ul>
                <ul>
                    <li><a class="course-link" href="Courses.php">C</a></li>
                    <li><a class="course-link" href="Courses.php">JavaScript</a></li>
                    <li><a class="course-link" href="Courses.php">Data Structure</a></li>
                    <li><a class="course-link" href="Courses.php">Computer Organisation</a></li>
                    <li><a class="course-link" href="Courses.php">Web Technology</a></li>
                </ul>
            </div>
            <a href="Courses.php">
                <button class="browse-btn">Browse All Courses</button>
            </a>
        </div>
    </section>
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

    <?php if (!$isLoggedIn): ?>
    <div class="guest-overlay" id="guestOverlay">
        <div class="guest-popup">
            <button type="button" class="guest-close" onclick="closeGuestPopup()">&times;</button>
            <div class="guest-icon">L</div>
            <h3 id="guestPopupTitle">Login Required</h3>
            <p id="guestPopupText">Please login to continue.</p>
            <a href="login.php" class="guest-login-btn">Login</a>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function openGuestPopup(type) {
            var overlay = document.getElementById('guestOverlay');
            var title = document.getElementById('guestPopupTitle');
            var text = document.getElementById('guestPopupText');

            if (!overlay || !title || !text) {
                return;
            }

            if (type === 'subscription') {
                title.textContent = 'Login For Subscription';
                text.textContent = 'Please login first to select a plan and continue with subscription.';
            } else {
                title.textContent = 'Login To Start Learning';
                text.textContent = 'Please login first to access learning content and continue.';
            }

            overlay.classList.add('active');
        }

        function closeGuestPopup() {
            var overlay = document.getElementById('guestOverlay');
            if (overlay) {
                overlay.classList.remove('active');
            }
        }
    </script>
<?php include 'footer.php'; ?>
</body>
</html>
