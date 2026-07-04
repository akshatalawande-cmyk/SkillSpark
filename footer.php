<style>
.shared-footer {
    background: linear-gradient(90deg, #22083f 0%, #1c1460 45%, #0f2a73 100%);
    color: #ffffff;
    padding: 72px 32px 24px;
    margin-top: 48px;
}

.shared-footer__grid {
    max-width: 1180px;
    margin: 0 auto 52px;
    display: grid;
    grid-template-columns: repeat(3, minmax(220px, 1fr));
    gap: 36px;
}

.shared-footer__column {
    text-align: center;
    position: relative;
}

.shared-footer__column:not(:last-child)::after {
    content: "";
    position: absolute;
    right: -18px;
    top: 6px;
    width: 1px;
    height: 76%;
    background: rgba(255, 255, 255, 0.32);
}

.shared-footer__column h3 {
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 26px;
}

.shared-footer__list,
.shared-footer__contact {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 14px;
}

.shared-footer__list a,
.shared-footer__contact li {
    color: rgba(255, 255, 255, 0.88);
    text-decoration: none;
    font-size: 15px;
    line-height: 1.45;
}

.shared-footer__list a:hover {
    color: #ffffff;
}

.shared-footer__bottom {
    max-width: 1640px;
    margin: 0 auto;
    background: linear-gradient(90deg, #2b0d52 0%, #162a72 100%);
    padding: 30px 24px;
    text-align: center;
}

.shared-footer__bottom-links {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 8px;
}

.shared-footer__bottom-links a {
    color: #ffffff;
    text-decoration: none;
}

.shared-footer__copyright {
    font-size: 15px;
    color: #ffffff;
}

@media (max-width: 900px) {
    .shared-footer {
        padding: 56px 20px 20px;
    }

    .shared-footer__grid {
        grid-template-columns: 1fr;
        gap: 28px;
    }

    .shared-footer__column:not(:last-child)::after {
        display: none;
    }

    .shared-footer__column {
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .shared-footer__column:last-child {
        border-bottom: none;
    }
}
</style>

<footer class="shared-footer">
    <div class="shared-footer__grid">
        <div class="shared-footer__column">
            <h3>Popular Courses</h3>
            <ul class="shared-footer__list">
                <li><a href="Courses.php">Python</a></li>
                <li><a href="Courses.php">C++</a></li>
                <li><a href="Courses.php">Object Oriented System</a></li>
                <li><a href="Courses.php">Advanced JavaScript</a></li>
                <li><a href="Courses.php">Web App Development</a></li>
                <li><a href="Courses.php">C</a></li>
                <li><a href="Courses.php">JavaScript</a></li>
                <li><a href="Courses.php">Computer Organisation</a></li>
                <li><a href="Courses.php">Data Structure</a></li>
                <li><a href="Courses.php">Web Technology</a></li>
            </ul>
        </div>

        <div class="shared-footer__column">
            <h3>Quick Links</h3>
            <ul class="shared-footer__list">
                <li><a href="About.php">About Us</a></li>
                <li><a href="Courses.php">Courses</a></li>
                <li><a href="Subscription.php">Blog</a></li>
                <li><a href="helpcenter.php">FAQs</a></li>
                <li><a href="Dashboard.php">Contact Us</a></li>
            </ul>
        </div>

        <div class="shared-footer__column">
            <h3>Contact Us</h3>
            <ul class="shared-footer__contact">
                <li>123 E-learning St, Education City</li>
                <li>+123 456 7890</li>
                <li>info@elearn.com</li>
            </ul>
        </div>
    </div>

    <div class="shared-footer__bottom">
        <div class="shared-footer__bottom-links">
            <a href="#">Privacy Policy</a>
            <span>|</span>
            <a href="#">Terms of Service</a>
            <span>|</span>
            <a href="helpcenter.php">Help Center</a>
        </div>
        <div class="shared-footer__copyright">&copy; 2026 Skillspark. All Rights Reserved.</div>
    </div>
</footer>




