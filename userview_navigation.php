<style>
.userview-navbar {
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

.userview-nav-left,
.userview-nav-links,
.userview-nav-right {
    display: flex;
    align-items: center;
}

.userview-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 20px;
    font-weight: 700;
    color: white;
    cursor: pointer;
}

.userview-pro-badge {
    background: gold;
    color: black;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: bold;
}

.userview-nav-links {
    list-style: none;
    display: flex;
    gap: 25px;
    margin: 0;
    padding: 0;
}

.userview-nav-links a {
    text-decoration: none;
    color: white;
    font-weight: 500;
    font-size: 15px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.userview-nav-links a:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

.userview-nav-right {
    gap: 15px;
}

.userview-help-icon,
.userview-login-btn {
    min-width: 36px;
    height: 36px;
    border-radius: 999px;
    background: white;
    color: #2c1ccf;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.userview-help-icon {
    width: 36px;
    min-width: 36px;
    font-size: 16px;
}

.userview-login-btn {
    padding: 0 18px;
}

.userview-help-icon:hover,
.userview-login-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

@media (max-width: 768px) {
    .userview-navbar {
        padding: 12px 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .userview-nav-links {
        gap: 12px;
        flex-wrap: wrap;
    }

    .userview-nav-links a {
        font-size: 14px;
        padding: 6px 10px;
    }
}
</style>

<header class="userview-navbar">

    <div class="userview-nav-left">
        <a href="userview.php" class="userview-logo">
            <span>📖 SkillSpark</span>
            <span class="userview-pro-badge">PRO</span>
        </a>
    </div>

    <ul class="userview-nav-links">
        <li><a href="userview.php">🏠 Home</a></li>
        <li><a href="Courses.php">📚 Courses</a></li>
        <li><a href="Subscription.php">💎 Subscription</a></li>
        <li><a href="About.php">ℹ️ About</a></li>
    </ul>

    <div class="userview-nav-right">
        <a href="helpcenter.php" class="userview-help-icon">?</a>
        <a href="login.php" class="userview-login-btn">Login</a>
    </div>

</header>


