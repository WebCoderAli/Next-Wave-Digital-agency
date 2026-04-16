<?php
require_once "includes/header.php";
?>

<style>
/* ================= PREMIUM PAGE STYLES (SERVICES) ================= */
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

.glass-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.05);
}

.text-gradient {
    background: linear-gradient(135deg, #60a5fa, #c084fc, #f472b6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-gradient-alt {
    background: linear-gradient(135deg, #34d399, #3b82f6);
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
    background: radial-gradient(circle, rgba(79, 70, 229, 0.15) 0%, rgba(0,0,0,0) 70%);
    top: -400px;
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
    color: #c084fc;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 20px;
}

.service-list-premium {
    list-style: none;
    padding: 0;
}
.service-list-premium li {
    position: relative;
    padding-left: 35px;
    margin-bottom: 15px;
    color: #cbd5e1;
    font-size: 1.1rem;
}
.service-list-premium li::before {
    content: '✓';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
}

.service-image-glass {
    border-radius: 30px;
    padding: 15px;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.05);
    position: relative;
}
.service-image-glass img {
    border-radius: 20px;
    object-fit: cover;
    box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    filter: grayscale(20%) contrast(110%);
    transition: filter 0.5s ease;
}
.service-image-glass:hover img {
    filter: grayscale(0%) contrast(100%);
}

.why-icon-box {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
    border: 1px solid rgba(255,255,255,0.1);
}
</style>

<div class="premium-wrapper">

    <!-- ================= SERVICES HERO ================= -->
    <section class="premium-section" style="padding-top: 150px; padding-bottom: 80px; overflow: hidden;">
        <div class="hero-glow-bg"></div>
        <div class="container text-center position-relative" style="z-index: 1;">
            <span class="premium-badge">What We Do</span>
            <h1 class="services-title display-3 fw-bold mb-4">Our Digital <span class="text-gradient">Services</span></h1>
            <p class="services-subtitle lead text-secondary mx-auto" style="max-width: 650px; font-size: 1.25rem;">
                We provide complete digital solutions to help your business
                grow, scale, and stand out in an an increasingly competitive world.
            </p>
        </div>
    </section>

    <!-- ================= SERVICES DETAILS ================= -->
    <section class="premium-section">
        <div class="container">

            <!-- WEB DEVELOPMENT -->
            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
                    <div class="d-inline-flex align-items-center gap-3 mb-4">
                        <div class="why-icon-box m-0" style="width: 50px; height: 50px; font-size: 1.2rem; background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.3);">💻</div>
                        <h2 class="fw-bold mb-0">Web Development</h2>
                    </div>
                    <p class="text-secondary fs-5 mb-4" style="line-height: 1.7;">
                        We build fast, secure, and highly scalable web architectures tailored to your unique business needs, ensuring flawless logic and stunning UI.
                    </p>
                    <ul class="service-list-premium mb-0">
                        <li>Custom PHP & MySQL systems</li>
                        <li>High-performance SPA & Responsive design</li>
                        <li>Secure Admin Dashboards</li>
                        <li>Enterprise E-commerce portals</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="service-image-glass">
                        <img src="assets/images/services/web.png"
                             class="img-fluid w-100"
                             alt="Web Development"
                             onerror="this.src='https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';">
                    </div>
                </div>
            </div>

            <!-- GRAPHIC DESIGN -->
            <div class="row align-items-center flex-lg-row-reverse mb-5 pb-5">
                <div class="col-lg-6 mb-5 mb-lg-0 ps-lg-5">
                    <div class="d-inline-flex align-items-center gap-3 mb-4">
                        <div class="why-icon-box m-0" style="width: 50px; height: 50px; font-size: 1.2rem; background: rgba(168, 85, 247, 0.1); border-color: rgba(168, 85, 247, 0.3);">🎨</div>
                        <h2 class="fw-bold mb-0">Graphic Design</h2>
                    </div>
                    <p class="text-secondary fs-5 mb-4" style="line-height: 1.7;">
                        Creative designs that communicate your brand identity
                        and leave a strong visual impression across all major platforms.
                    </p>
                    <ul class="service-list-premium mb-0">
                        <li>Premium Logo & Branding Guidelines</li>
                        <li>User-centric UI/UX Design Models</li>
                        <li>High-converting Marketing Creatives</li>
                        <li>Social media assets and graphics</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="service-image-glass">
                        <img src="assets/images/services/graphics.png"
                             class="img-fluid w-100"
                             alt="Graphic Design"
                             onerror="this.src='https://images.unsplash.com/photo-1561070791-2526d30994b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';">
                    </div>
                </div>
            </div>

            <!-- DIGITAL MARKETING -->
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
                    <div class="d-inline-flex align-items-center gap-3 mb-4">
                        <div class="why-icon-box m-0" style="width: 50px; height: 50px; font-size: 1.2rem; background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3);">📈</div>
                        <h2 class="fw-bold mb-0">Digital Marketing</h2>
                    </div>
                    <p class="text-secondary fs-5 mb-4" style="line-height: 1.7;">
                        Data-driven marketing strategies to substantially increase traffic,
                        boost deep engagement, and massively multiply conversions.
                    </p>
                    <ul class="service-list-premium mb-0">
                        <li>Deep Search Engine Optimization (SEO)</li>
                        <li>Viral Social Media Marketing</li>
                        <li>High ROI Paid Advertising (PPC)</li>
                        <li>Strategic Content Strategy</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="service-image-glass">
                        <img src="assets/images/services/digital.png"
                             class="img-fluid w-100"
                             alt="Digital Marketing"
                             onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';">
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ================= WHY CHOOSE SERVICES ================= -->
    <section class="premium-section" style="background: rgba(255,255,255,0.01);">
        <div class="container">
            <h2 class="display-5 fw-bold text-center mb-5 pb-3">
                Why Choose <span class="text-gradient">Us?</span>
            </h2>

            <div class="row g-4 text-center justify-content-center">

                <div class="col-lg-3 col-md-6">
                    <div class="glass-card p-5 h-100">
                        <div class="why-icon-box text-white">💎</div>
                        <h4 class="fw-bold mb-3 text-white">Quality</h4>
                        <p class="text-secondary mb-0">
                            We follow rigorous best practices and strictly adhere to modern development standards.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="glass-card p-5 h-100 mt-lg-4" style="border-color: rgba(96,165,250,0.2);">
                        <div class="why-icon-box text-white" style="box-shadow: 0 0 20px rgba(96,165,250,0.2);">🔮</div>
                        <h4 class="fw-bold mb-3 text-white">Transparency</h4>
                        <p class="text-secondary mb-0">
                            Pristine communication logic with completely honest and upfront pricing.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="glass-card p-5 h-100">
                        <div class="why-icon-box text-white">🛡️</div>
                        <h4 class="fw-bold mb-3 text-white">Support</h4>
                        <p class="text-secondary mb-0">
                            We offer unparalleled ongoing technical support long after the final project delivery.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="glass-card p-5 h-100 mt-lg-4" style="border-color: rgba(244,114,182,0.2);">
                        <div class="why-icon-box text-white" style="box-shadow: 0 0 20px rgba(244,114,182,0.2);">🚀</div>
                        <h4 class="fw-bold mb-3 text-white">Results</h4>
                        <p class="text-secondary mb-0">
                            Absolute focus entirely directed towards measurable, scaling business outcomes.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= SERVICES CTA ================= -->
    <section class="premium-section text-center position-relative" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(124, 58, 237, 0.1)); border-bottom: none;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="glass-card p-5 mx-auto" style="max-width: 750px; background: rgba(255,255,255,0.02); border: 1px solid rgba(124, 58, 237, 0.2);">
                <span class="premium-badge mb-3">Get Started</span>
                <h2 class="display-5 fw-bold mb-3">Upgrade Your <span class="text-gradient">Presence</span></h2>
                <p class="lead text-secondary mb-5">
                    Ready to scale your digital efforts? Create an account and start executing your master project with us today.
                </p>
                <a href="user/register.php" class="btn-premium btn-lg px-5">
                    Register Now
                </a>
            </div>
        </div>
    </section>

</div>

<?php
require_once "includes/footer.php";
?>
