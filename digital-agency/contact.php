<?php
require_once "includes/header.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $subject && $message) {

        $to = "alihanza85@gmail.com";
        $mail_subject = "New Contact Message: " . $subject;

        $mail_body  = "You have received a new message from your website.\n\n";
        $mail_body .= "Name: $name\n";
        $mail_body .= "Email: $email\n";
        $mail_body .= "Subject: $subject\n\n";
        $mail_body .= "Message:\n$message\n";

        $headers  = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8";

        if (mail($to, $mail_subject, $mail_body, $headers)) {
            $success = "Your message has been sent successfully.";
        } else {
            $error = "Failed to send message. Please try again later.";
        }

    } else {
        $error = "All fields are required.";
    }
}
?>

<style>
/* ================= PREMIUM PAGE STYLES (CONTACT) ================= */
.premium-wrapper {
    background-color: #050505;
    color: #e2e8f0;
    font-family: 'Inter', sans-serif;
    overflow-x: hidden;
}

.premium-wrapper h1, 
.premium-wrapper h2, 
.premium-wrapper h3, 
.premium-wrapper h4, 
.premium-wrapper h5, 
.premium-wrapper h6 {
    font-family: 'Outfit', sans-serif;
}

.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border-radius: 24px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.text-gradient {
    background: linear-gradient(135deg, #60a5fa, #c084fc, #f472b6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.btn-premium {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff !important;
    border: none;
    border-radius: 50px;
    padding: 14px 32px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px -5px rgba(124, 58, 237, 0.5);
    display: inline-block;
    text-decoration: none;
    text-align: center;
}
.btn-premium:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 25px -5px rgba(124, 58, 237, 0.6);
}

.premium-section {
    padding: 120px 0;
    position: relative;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

/* Page Specific Overrides */
.hero-glow-bg {
    position: absolute;
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, rgba(236, 72, 153, 0.15) 0%, rgba(0,0,0,0) 70%);
    top: -300px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
}

.premium-badge {
    display: inline-block;
    padding: 8px 16px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #f472b6;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 20px;
}

/* Custom Dark Form Elements */
.form-control-dark {
    background-color: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
    color: #f8fafc;
    border-radius: 12px;
    padding: 14px 20px;
    transition: all 0.3s ease;
}
.form-control-dark::placeholder {
    color: #64748b;
}
.form-control-dark:focus {
    background-color: rgba(255,255,255,0.05);
    border-color: rgba(244, 114, 182, 0.5);
    box-shadow: 0 0 15px rgba(244, 114, 182, 0.15);
    color: #fff;
}
label {
    font-weight: 500;
    margin-bottom: 8px;
    color: #cbd5e1;
}

/* Contact Info List */
.contact-info-list {
    list-style: none;
    padding: 0;
}
.contact-info-list li {
    display: flex;
    align-items: flex-start;
    margin-bottom: 25px;
}
.contact-info-icon {
    width: 45px;
    height: 45px;
    min-width: 45px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 50%;
    color: #f472b6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 15px;
}
.contact-info-text strong {
    display: block;
    color: #fff;
    margin-bottom: 4px;
}
.contact-info-text span {
    color: #94a3b8;
}

.map-placeholder {
    height: 300px;
    background: rgba(255,255,255,0.01);
    border: 1px dashed rgba(255,255,255,0.1);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}
</style>

<div class="premium-wrapper">

    <!-- ================= HERO ================= -->
    <section class="premium-section pb-0" style="padding-top: 150px; border-bottom: none;">
        <div class="hero-glow-bg"></div>
        <div class="container text-center position-relative" style="z-index: 1;">
            <span class="premium-badge">Connect</span>
            <h1 class="display-3 fw-bold mb-4">Let's <span class="text-gradient">Talk</span></h1>
            <p class="lead text-secondary mx-auto" style="max-width: 600px; font-size: 1.25rem;">
                Have a breakthrough project in mind? We'd absolutely love to hear from you. Get in direct touch with our core team today.
            </p>
        </div>
    </section>

    <!-- ================= CONTACT BLOCK ================= -->
    <section class="premium-section pt-5">
        <div class="container">
            <div class="row g-5 align-items-center">

                <!-- LEFT: INFO -->
                <div class="col-lg-5 pe-lg-5">
                    <h2 class="display-6 fw-bold mb-4">Get In <span class="text-gradient">Touch</span></h2>
                    <p class="text-secondary mb-5" style="line-height: 1.7;">
                        Whether you need a dynamic new website architecture, high-converting branding, or a scale-oriented digital marketing backbone, our specialized team is ready to deploy.
                    </p>

                    <ul class="contact-info-list">
                        <li>
                            <div class="contact-info-icon">📧</div>
                            <div class="contact-info-text">
                                <strong>Email Address</strong>
                                <span>support@digitalagency.com</span>
                            </div>
                        </li>
                        <li>
                            <div class="contact-info-icon">📞</div>
                            <div class="contact-info-text">
                                <strong>Phone Matrix</strong>
                                <span>+92 300 1234567</span>
                            </div>
                        </li>
                        <li>
                            <div class="contact-info-icon">🕘</div>
                            <div class="contact-info-text">
                                <strong>Office Availability</strong>
                                <span>Mon – Fri (9:00 AM – 6:00 PM)</span>
                            </div>
                        </li>
                        <li>
                            <div class="contact-info-icon">📍</div>
                            <div class="contact-info-text">
                                <strong>Headquarters</strong>
                                <span>Lahore, Pakistan</span>
                            </div>
                        </li>
                    </ul>

                    <p class="text-secondary mt-5 small">
                        * Our designated representatives typically respond within 24 operational hours.
                    </p>
                </div>

                <!-- RIGHT: FORM -->
                <div class="col-lg-7">
                    <div class="glass-card p-5">
                        <h4 class="fw-bold mb-4 text-white">Send Us a Direct Message</h4>

                        <?php if ($success): ?>
                            <div class="alert alert-success" style="background: rgba(74, 222, 128, 0.1); border-color: rgba(74, 222, 128, 0.3); color: #4ade80;">
                                <?= $success ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger" style="background: rgba(248, 113, 113, 0.1); border-color: rgba(248, 113, 113, 0.3); color: #f87171;">
                                <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label>Your Name</label>
                                    <input type="text" name="name"
                                           class="form-control form-control-dark"
                                           placeholder="Enter your name" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Email Address</label>
                                    <input type="email" name="email"
                                           class="form-control form-control-dark"
                                           placeholder="Enter your email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Subject Outline</label>
                                <input type="text" name="subject"
                                       class="form-control form-control-dark"
                                       placeholder="Main subject of inquiry" required>
                            </div>

                            <div class="mb-4">
                                <label>Message Details</label>
                                <textarea name="message"
                                          class="form-control form-control-dark"
                                          rows="6"
                                          placeholder="Write your comprehensive message here..."
                                          style="resize: none;"
                                          required></textarea>
                            </div>

                            <button type="submit" class="btn-premium w-100">
                                Transmit Message
                            </button>

                            <p class="text-secondary mt-3 mb-0 text-center small">
                                * Private transmission encrypted directly to admin terminal.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- MAP / LOCATION -->
    <section class="premium-section" style="background: rgba(255,255,255,0.01);">
        <div class="container text-center">
            <h2 class="display-6 fw-bold mb-3">Geographic <span class="text-gradient">Location</span></h2>
            <p class="text-secondary mb-5 mx-auto" style="max-width: 600px;">
                While we maintain a global digital footprint serving clients completely remotely, we anchor our physical reality right here.
            </p>

            <div class="glass-card p-3">
                <div class="map-placeholder">
                    <span>📍 Tactical Map Integration Space (Google Maps / OpenStreetMap)</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="premium-section text-center position-relative" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(124, 58, 237, 0.1)); border-bottom: none;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="glass-card p-5 mx-auto" style="max-width: 750px; background: rgba(255,255,255,0.02); border: 1px solid rgba(124, 58, 237, 0.2);">
                <span class="premium-badge mb-3">Onboarding</span>
                <h2 class="display-5 fw-bold mb-3">Ready to <span class="text-gradient">Launch?</span></h2>
                <p class="lead text-secondary mb-5">
                    Initialize your secure account right now and interface directly with our premier digital workforce.
                </p>

                <a href="user/register.php" class="btn-premium btn-lg px-5">
                    Create Secure Account
                </a>
            </div>
        </div>
    </section>

</div>

<?php
require_once "includes/footer.php";
?>
