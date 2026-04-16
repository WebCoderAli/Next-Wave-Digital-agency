<?php
// Added embedded premium styles for the footer
?>
<style>
/* ================= PREMIUM FOOTER STYLES ================= */
.footer-premium {
    background-color: #050505;
    border-top: 1px solid rgba(255,255,255,0.05);
    color: #94a3b8;
    padding-top: 80px;
    padding-bottom: 30px;
    position: relative;
    overflow: hidden;
    margin-top: 0; /* Resetting bootstrap mt-5 if present */
}
.footer-premium::before {
    content: '';
    position: absolute;
    top: 0; left: 50%;
    transform: translateX(-50%);
    width: 100%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(124, 58, 237, 0.4), transparent);
}
.footer-premium h5, .footer-premium h6 {
    color: #f8fafc;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.footer-brand {
    background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 1.5rem;
    font-family: 'Outfit', sans-serif;
    font-weight: 800;
    letter-spacing: -0.5px;
}
.footer-links-premium li {
    margin-bottom: 8px;
}
.footer-links-premium a, .footer-links-premium span {
    color: #94a3b8;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    padding: 2px 0;
}
.footer-links-premium a:hover {
    color: #c084fc;
    transform: translateX(4px);
}
.footer-bottom-divider {
    border-color: rgba(255,255,255,0.1);
    margin: 40px 0 20px;
}
.social-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
    color: #e2e8f0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    margin-right: 12px;
}
.social-icon:hover {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-color: transparent;
    transform: translateY(-4px);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);
}
.contact-list li {
    display: flex;
    align-items: center;
    gap: 12px;
}
.contact-icon {
    color: #a5b4fc;
    font-size: 1.1rem;
}
</style>

<!-- ================= FOOTER ================= -->
<footer class="footer-premium">
    <div class="container">
        <div class="row gy-5">

            <!-- ABOUT -->
            <div class="col-lg-4 col-md-6 pe-lg-5">
                <div class="mb-4">
                    <span class="footer-brand">Next Wave Digital</span>
                </div>
                <p style="line-height: 1.7;">
                    We are a modern digital agency providing state-of-the-art web development, pristine design, and data-driven marketing services. We fuel growth through technology and innovation.
                </p>
                <div class="mt-4">
                    <a href="#" class="social-icon">IN</a>
                    <a href="#" class="social-icon">FB</a>
                    <a href="#" class="social-icon">TW</a>
                    <a href="#" class="social-icon">IG</a>
                </div>
            </div>

            <!-- QUICK LINKS -->
            <div class="col-lg-2 col-md-6">
                <h6 class="mb-4 text-uppercase" style="font-size: 0.9rem; letter-spacing: 1px;">Quick Links</h6>
                <ul class="list-unstyled footer-links-premium">
                    <li><a href="<?= BASE_URL ?>/">Home</a></li>
                    <li><a href="<?= BASE_URL ?>/about.php">About Us</a></li>
                    <li><a href="<?= BASE_URL ?>/services.php">Services</a></li>
                    <li><a href="<?= BASE_URL ?>/pricing.php">Pricing</a></li>
                    <li><a href="<?= BASE_URL ?>/portfolio.php">Portfolio</a></li>
                    <li><a href="<?= BASE_URL ?>/contact.php">Contact</a></li>
                </ul>
            </div>

            <!-- SERVICES -->
            <div class="col-lg-3 col-md-6">
                <h6 class="mb-4 text-uppercase" style="font-size: 0.9rem; letter-spacing: 1px;">Our Services</h6>
                <ul class="list-unstyled footer-links-premium">
                    <li><a href="<?= BASE_URL ?>/services.php">Web Development</a></li>
                    <li><a href="<?= BASE_URL ?>/services.php">Graphic Design</a></li>
                    <li><a href="<?= BASE_URL ?>/services.php">UI / UX Design</a></li>
                    <li><a href="<?= BASE_URL ?>/services.php">Digital Marketing</a></li>
                    <li><a href="<?= BASE_URL ?>/services.php">SEO Optimization</a></li>
                </ul>
            </div>

            <!-- CONTACT -->
            <div class="col-lg-3 col-md-6">
                <h6 class="mb-4 text-uppercase" style="font-size: 0.9rem; letter-spacing: 1px;">Contact Info</h6>
                <ul class="list-unstyled contact-list">
                    <li class="mb-3">
                        <span class="contact-icon">📧</span>
                        <a href="mailto:support@nextwavedigital.com" class="text-decoration-none" style="color: #94a3b8; transition: color 0.3s;" onmouseover="this.style.color='#c084fc'" onmouseout="this.style.color='#94a3b8'">support@nextwave.com</a>
                    </li>
                    <li class="mb-3">
                        <span class="contact-icon">📞</span>
                        <span>+92 300 1234567</span>
                    </li>
                    <li class="mb-3">
                        <span class="contact-icon">📍</span>
                        <span>Lahore, Pakistan</span>
                    </li>
                    <li class="mb-3">
                        <span class="contact-icon">🕘</span>
                        <span>Mon – Fri (9AM – 6PM)</span>
                    </li>
                </ul>
            </div>

        </div>

        <hr class="footer-bottom-divider">

        <!-- BOTTOM -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0 small" style="color: #64748b;">
                    © <?= date('Y'); ?> Next Wave Digital Agency. All rights reserved.
                </p>
            </div>

            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 small" style="color: #64748b;">
                    Designed & Developed as a Project
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- GLOBAL JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/guest.js"></script>
</body>
</html>
