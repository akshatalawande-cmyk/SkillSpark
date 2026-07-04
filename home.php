<?php
session_start();

// Example: If user is logged in
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "SS";
}
?>

<?php if(isset($_SESSION['username'])): ?>
    <div class="profile">
        <?php echo strtoupper(substr($username,0,2)); ?>
    </div>
<?php else: ?>
    <a href="login.php">
        <div class="profile">SS</div>
    </a>
<?php endif; ?>


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
            font-family: 'Poppins', sans-serif;
            background: (#ff00ff, #6c6cf6, #1c5983, #06b6d4);
        }

        body {
            background: linear-gradient(180deg, #ff5ccf, #8a4fff);
            color: #f9fafc;
        }

        /* NAVBAR */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 60px;
            background: #2d2db3;
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
        }

        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #fff;
            font-weight: 500;
        }

        .login-btn {
            background: #ff5ccf;
            padding: 8px 18px;
            border-radius: 20px;
            text-decoration: none;
            color: #fff;
            font-weight: 600;
        }

        /* HERO SECTION */
        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 70px 80px;
        }

        .hero-left {
            max-width: 50%;
        }

        .badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-size: 42px;
            line-height: 1.3;
            margin-bottom: 15px;
        }

        .hero h1 span {
            color: #ffe600;
        }

        .hero p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 25px;
        }

        /* HERO BUTTONS */
        .hero-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #fff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-fill {
            background: #ffe600;
            color: #000;
            border: none;
            padding: 10px 22px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 700;
        }

        .stats-inline {
            font-size: 14px;
            opacity: 0.9;
        }

        /* HERO IMAGE */
        .hero-right img {
            width: 330px;
            border-radius: 20px;
        }

        /* STATS SECTION */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            padding: 40px 80px;
            text-align: center;
        }

        .stat-card {
            background: #ffffff;
            color: #000;
            border-radius: 18px;
            padding: 25px;
            text-align: center;
        }

        .stat-card h2 {
            color: #6a1bff;
            margin: 10px 0;
        }

        /* COURSES SECTION */
        .courses {
            text-align: center;
            padding: 60px 80px;
        }

        .section-tag {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
        }

        .courses h2 {
            margin-top: 15px;
            font-size: 32px;
        }

        .courses p {
            margin: 10px 0 30px;
            opacity: 0.9;
        }

        .course-box {
            background: #fefcfc;
            color: #000;
            border-radius: 10px;
            padding: 35px;
            display: flex;
            justify-content: space-around;
            margin-bottom: 5px;
        }

        .course-box ul {
            list-style: none;
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            padding: 0;
        }

        .course-box li {
            margin: 10px 0;
            font-weight: 900;
        }

        /* PRIMARY BUTTON */
        .btn-primary {
            background: #2d2db3;
            color: #fff;
            padding: 12px 28px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
        }

        .center {
            display: inline-block;
        }

        /* TESTIMONIALS */
        .testimonials {
            padding: 60px 80px;
            text-align: center;
        }

        .testimonials h2 {
            font-size: 32px;
            margin: 15px 0 40px;
        }

        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .testimonial-card {
            background: #fff;
            color: #000;
            border-radius: 18px;
            padding: 25px;
            text-align: left;
        }

        .course-tag {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 600;
            color: #2d2db3;
            background: #eef0ff;
            border-radius: 20px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            background: #6a1bff;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .rating {
            display: block;
            margin-top: 10px;
        }

        /* HERO SECTION */
        .heroo {
            background: linear-gradient(135deg, #5f8dff, #ff5fa2);
            color: white;
            padding: 40px 60px;
        }

        .heroo h2 {
            font-size: 26px;
            font-family: Arial, Helvetica, sans-serif;
            font-display: block;
            margin-bottom: 0px;
        }

        .heroo p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 0px;
        }

        .heroo ul {
            list-style: none;
            font-size: 14px;
            line-height: 1.8;
        }

        .heroo ul li::before {
            content: "• ";
        }

        /* IMAGE SECTION */
        .image-section {
            background: white;
            padding: 30px 60px;
            text-align: center;
        }

        .image-section h1 {
            font-size: 28px;
            color: #444;
            margin-bottom: 5px;
        }

        .image-section p {
            font-size: 14px;
            color: #777;
            margin-bottom: 25px;
        }

        .mock-image {
            width: 100%;
            max-width: 990px;
            height: 300px;
            background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f') center/cover no-repeat;
            border-radius: 1px;
            margin: auto;
            position: relative;
        }

        .cta-button {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffd84d;
            color: #333;
            padding: 12px 22px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* FOOTER */
        footer {
            background: #5fa6b5;
            color: white;
            padding: 40px 60px 20px;
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: none;
        }

        .footer-column {
            width: 200px;
            margin-bottom: none;
        }

        .footer-column h4 {
            font-size: 15px;
            margin-bottom: none;
        }

        .footer-column p {
            font-size: 13px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .footer-column p span {
            display: flex;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 15px;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            opacity: 0.9;
        }

        .stat-card,
        .testimonial-card,
        .course-box {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover,
        .testimonial-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .cta-button:hover {
            box-shadow: 0 8px 25px rgba(255, 216, 77, 0.6);
        }

        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            position: relative;
            transition: 0.3s ease;
        }

        /* ✨ Emoji Animation */
        nav a::first-letter {
            display: inline-block;
            transition: 0.35s ease;
        }

        /* 🚀 Hover Effects */
        nav a:hover {
            color: #00f0ff;
            text-shadow: 0 0 8px rgba(0, 240, 255, 0.8);
        }

        /* 🎯 Emoji Bounce + Glow */
        nav a:hover::first-letter {
            transform: translateY(-3px) scale(1.2);
            text-shadow: 0 0 12px rgba(255, 255, 255, 0.9);
        }

        /* ✨ Underline Glow Sweep */
        nav a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 0%;
            height: 2px;
            background: linear-gradient(90deg, #00f0ff, #ff00cc);
            transition: 0.35s ease;
        }

        nav a:hover::after {
            width: 100%;
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.35s ease;
        }

        .logo:hover {
            transform: scale(1.05);
            text-shadow: 0 0 15px rgba(0, 240, 255, 0.8);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f6fa;
        }

        /* 🔷 NAVBAR */
        header {
            background: linear-gradient(90deg, #5f8dff, #ff5fa2);
            padding: 18px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pro-badge {
            background: gold;
            color: black;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 10px;
            font-weight: 600;
        }

        nav a {
            margin: 0 18px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            font-weight: 500;
            opacity: 0.95;
        }

        nav a:hover {
            opacity: 1;
        }

        .profile {
            background: white;
            color: #5f8dff;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
        }

        /* 🔮 HERO */
        .hero {
            background: linear-gradient(135deg, #5f8dff, #ff5fa2);
            margin: 20px;
            border-radius: 18px;
            padding: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .hero-left {
            max-width: 480px;
        }

        .tag {
            background: rgba(255, 255, 255, 0.25);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
            margin-bottom: 25px;
        }

        .hero h1 {
            font-size: 40px;
            line-height: 1.3;
            margin-bottom: 18px;
        }

        .hero h1 span {
            color: #ffd84d;
        }

        .hero p {
            font-size: 15px;
            line-height: 1.6;
            opacity: 0.95;
            margin-bottom: 30px;
        }

        /* 🔘 BUTTONS */
        .hero-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .btn-outline {
            background: white;
            color: #5f8dff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-fill {
            background: #ffb400;
            border: none;
            padding: 10px 22px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
        }

        /* ⭐ STATS */
        .stats-strip {
            display: flex;
            gap: 25px;
            font-size: 13px;
            opacity: 0.95;
        }

        /* 🖼 IMAGE */
        .hero-right {
            position: relative;
        }

        .image-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 180, 100, 0.4);
            border-radius: 20px;
            transform: rotate(-3deg);
        }

        .hero-right img {
            width: 420px;
            border-radius: 20px;
            position: relative;
            z-index: 2;
        }

        .courses-section {
            padding: 80px 20px;
            background: (#60c6d888, #1c5983);
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Top Label */
        .courses-label {
            display: inline-block;
            padding: 6px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #0f0f10;
            background: #b2c2f5f4;
            border-radius: 20px;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        /* Heading */
        .courses-section h2 {
            font-size: 32px;
            color: #f5f6f7;
            margin-bottom: 10px;
        }

        /* Subtitle */
        .courses-section p {
            color: #f7f9fb;
            font-size: 15px;
            margin-bottom: 40px;
        }

        /* Card Container */
        .courses-card {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 15px;
            padding: 40px 50px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* Two Column Layout */
        .courses-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 60px;
            text-align: left;
            margin-bottom: 35px;
        }

        /* Bullet List */
        .courses-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .courses-list li {
            position: relative;
            padding-left: 18px;
            margin-bottom: 12px;
            font-size: 14px;
            color: #0f172a;
        }

        /* Blue Bullet */
        .courses-list li::before {
            content: "";
            width: 6px;
            height: 6px;
            background: #2563eb;
            border-radius: 50%;
            position: absolute;
            left: 0;
            top: 7px;
        }

        /* Button */
        .browse-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            transition: 0.3s ease;
        }

        .browse-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(79, 70, 229, 0.35);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .courses-list {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .courses-card {
                padding: 30px 25px;
            }

            .courses-section h2 {
                font-size: 26px;
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
        }

        .cta-features {
            font-size: 13px;
            opacity: 0.9;
        }

        .cta-features span {
            margin: 0 10px;
        }

        /* FOOTER */
        footer {
            background: linear-gradient(135deg, #346b84, #06b6d4);
            color: white;
            padding: 50px 60px 25px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            margin-bottom: 35px;
        }

        .footer-column h3 {
            font-size: 15px;
            margin-bottom: 15px;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column li {
            font-size: 13px;
            margin-bottom: 8px;
            opacity: 0.9;
            cursor: pointer;
        }

        .footer-column li:hover {
            opacity: 1;
        }

        .footer-divider {
            height: 120px;
            width: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .footer-contact p {
            font-size: 13px;
            margin-bottom: 10px;
            opacity: 0.95;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.35);
            padding-top: 18px;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 35px;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .footer-links span {
            cursor: pointer;
        }

        .footer-links span:hover {
            text-decoration: underline;
        }

        .copyright {
            font-size: 12px;
            opacity: 0.9;
        }

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
        @media (max-width: 7px) {

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

        /* 🔷 NAVBAR */
        header {
            background: #4e29f5;
            /* Navy Blue */
            padding: 18px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;

            position: fixed;
            /* ✅ Fixed */
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        body {
            margin: 0;
            padding-top: 90px;
            /* space for fixed navbar */
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <header>
        <div class="logo">
            📖 SkillSpark
            <span class="pro-badge">PRO</span>
        </div>

        <nav class="navbar">
            <div class="nav-left">
                <a href="homepage.php">🏠 Home</a>
                <a href="Courses.php">📚 Courses</a> <!-- ✅ ADDED -->
                <a href="Subscription.php">💎 Subscription</a>
                <a href="About.php">ℹ️ About</a>
            </div>


        </nav>


        <!-- Profile button redirects to Login page -->
      <a href="login.php">
    <div class="profile">SS</div>
</a>


    </header>

    <!-- 🔮 HERO -->
    <section class="hero">

        <div class="hero-left">
            <span class="tag">✨ Transform Your Career Today</span>

            <h1>
                Master Programming with <br>
                <span>SkillSpark</span>
            </h1>

            <p>
                Learn from industry experts with interactive courses,
                real-time quizzes, and hands-on projects.
                Start your journey to becoming a professional developer today!
            </p>

            <div class="hero-buttons">
                <a href="Courses.html">
                    <button class="btn-outline">🚀 Start Learning Free</button>
                </a>
                <a href="Subscription.php">
                    <button class="btn-fill">🧾Subscription</button>
                </a>
            </div>


            <div class="stats-strip">
                <span>⭐ 4.9/5 Rating</span>
                <span>👩‍🎓 50K+ Students</span>
                <span>📚 10+ Courses</span>
            </div>
        </div>

        <div class="hero-right">
            <div class="image-bg"></div>
            <img src="student.jpg" alt="students">
        </div>

    </section>

    <!-- STATS SECTION -->
    <section class="stats">
        <div class="stat-card">
            👥
            <h2>50K+</h2>
            <p>Active Students</p>
        </div>
        <div class="stat-card">
            📖
            <h2>10+</h2>
            <p>Expert Courses</p>
        </div>
        <div class="stat-card">
            🎓
            <h2>95%</h2>
            <p>Success Rate</p>
        </div>
        <div class="stat-card">
            ⭐
            <h2>4.9/5</h2>
            <p>Average Rating</p>
        </div>
    </section>

    <!-- COURSES SECTION -->
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

            <a href="Courses.html">
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
        <footer>

            <div class="footer-container">

                <div class="footer-column">
                    <h3>Popular Courses</h3>
                    <ul>
                        <li>Python</li>
                        <li>C++</li>
                        <li>Object Oriented System</li>
                        <li>Advanced JavaScript</li>
                        <li>Web App Development</li>
                        <li>C</li>
                        <li>JavaScript</li>
                        <li>Computer Organisation</li>
                        <li>Data Structure</li>
                        <li>Web Technology</li>
                    </ul>
                </div>

                <div class="footer-divider"></div>

                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li>About Us</li>
                        <li>Courses</li>
                        <li>Blog</li>
                        <li>FAQs</li>
                        <li>Contact Us</li>
                    </ul>
                </div>

                <div class="footer-divider"></div>

                <div class="footer-column footer-contact">
                    <h3>Contact Us</h3>
                    <p>📍 123 E-learning St, Education City</p>
                    <p>📞 +123 456 7890</p>
                    <p>📧 info@elearn.com</p>
                </div>

            </div>

            <div class="footer-bottom">

                <div class="footer-links">
                    <span>Privacy Policy</span>
                    <span>Terms of Services</span>
                    <span>Help Centre</span>
                </div>

                <div class="copyright">
                    © 2026 eLearn. All Rights Reserved.
                </div>

            </div>

        </footer>

</body>
<script>
    function openPopup() {
        document.getElementById("overlay").classList.add("active");
    }

    function closePopup() {
        document.getElementById("overlay").classList.remove("active");
    }
</script>



</html>