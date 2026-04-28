<?php
require_once "includes/header.php";
?>

<style>
/* ================= PREMIUM PAGE STYLES (PORTFOLIO) ================= */
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
.glass-card-hover:hover {
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
    text-align: center;
}
.btn-premium:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 25px -5px rgba(124, 58, 237, 0.6);
}

.btn-premium-outline {
    background: rgba(255,255,255,0.05);
    color: #fff !important;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    padding: 14px 32px;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    display: inline-block;
    text-decoration: none;
    text-align: center;
}
.btn-premium-outline:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-3px);
    border-color: rgba(255,255,255,0.4);
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
    background: radial-gradient(circle, rgba(14, 165, 233, 0.15) 0%, rgba(0,0,0,0) 70%);
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
    color: #38bdf8;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 20px;
}

/* Portfolio Cards */
.portfolio-card {
    padding: 0;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.portfolio-img-wrapper {
    position: relative;
    overflow: hidden;
    padding: 15px 15px 0 15px; /* Offset the image to look framed within the glass card */
}
.portfolio-img-wrapper img {
    border-radius: 16px;
    width: 100%;
    height: 240px;
    object-fit: cover;
    transition: transform 0.6s ease;
    filter: brightness(0.9) contrast(1.1);
}
.portfolio-card:hover .portfolio-img-wrapper img {
    transform: scale(1.05);
    filter: brightness(1) contrast(1.1);
}

.tech-pill {
    display: inline-block;
    padding: 4px 10px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    font-size: 0.75rem;
    color: #a5b4fc;
    margin-right: 5px;
    margin-bottom: 5px;
}

.process-step-number {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, rgba(56, 189, 248, 0.1), rgba(59, 130, 246, 0.1));
    border: 1px solid rgba(56, 189, 248, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: #38bdf8;
    margin: 0 auto 20px;
}
</style>

<div class="premium-wrapper">

    <!-- ================= HERO ================= -->
    <section class="premium-section pb-0" style="padding-top: 150px; border-bottom: none;">
        <div class="hero-glow-bg"></div>
        <div class="container text-center position-relative" style="z-index: 1;">
            <span class="premium-badge">Our Work</span>
            <h1 class="display-3 fw-bold mb-4">Explore Our <span class="text-gradient">Portfolio</span></h1>
            <p class="lead text-secondary mx-auto" style="max-width: 600px; font-size: 1.25rem;">
                A curated glimpse into the sophisticated digital platforms, immersive brand identities, and stunning web products we have successfully delivered.
            </p>
        </div>
    </section>

    <!-- ================= PORTFOLIO INTRO ================= -->
    <section class="premium-section pb-4 text-center" style="border-bottom: none;">
        <div class="container">
            <h2 class="display-6 fw-bold mb-3">Real Projects, <span class="text-gradient-alt">Real Results</span></h2>
            <p class="text-secondary mx-auto" style="max-width: 700px; font-size: 1.1rem; line-height: 1.8;">
                Each project is carefully architected with an unrelenting attention to detail, flawless performance scaling, and compelling user experiences. Below are key selections representing our capabilities.
            </p>
        </div>
    </section>

    <!-- ================= PROJECTS ================= -->
    <section class="premium-section pt-0">
        <div class="container">
            <div class="row g-5">

                <!-- PROJECT 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card glass-card-hover portfolio-card">
                        <div class="portfolio-img-wrapper">
                            <img src="assets/images/portfolio/web-agency.jpg" 
                                 alt="Online Agency System"
                                 onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';">
                        </div>
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-white mb-2">Online Digital Agency System</h5>
                            <p class="text-secondary small mb-4" style="line-height: 1.6;">
                                A highly modular web-based operational system equipped with a full admin panel, intuitive user dashboard, robust service routing, live chat, and a full revenue management suite.
                            </p>
                            <div>
                                <span class="tech-pill">PHP & MySQL</span>
                                <span class="tech-pill">Admin Dashboard</span>
                                <span class="tech-pill">Live Chat Matrix</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PROJECT 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card glass-card-hover portfolio-card">
                        <div class="portfolio-img-wrapper">
                            <img src="assets/images/portfolio/resturant-pos.webp" 
                                 alt="Restaurant POS"
                                 onerror="this.src='https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';">
                        </div>
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-white mb-2">Modern Restaurant POS System</h5>
                            <p class="text-secondary small mb-4" style="line-height: 1.6;">
                                A highly responsive point-of-sale command center built for bustling restaurants to handle heavy order management, fast billing integration, and distinct role-based access logic.
                            </p>
                            <div>
                                <span class="tech-pill">Order & Billing</span>
                                <span class="tech-pill">Role-based Auth</span>
                                <span class="tech-pill">Data Analytics</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PROJECT 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card glass-card-hover portfolio-card">
                        <div class="portfolio-img-wrapper">
                            <img src="assets/images/portfolio/ecommerce-site.jpg" 
                                 alt="E-Commerce Website"
                                 onerror="this.src='https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';">
                        </div>
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-white mb-2">High-Conversion E-Commerce</h5>
                            <p class="text-secondary small mb-4" style="line-height: 1.6;">
                                A hyper-modern e-commerce product platform ensuring fluid product listings, an optimized secure checkout flow, and extensive logistical admin management controls.
                            </p>
                            <div>
                                <span class="tech-pill">Product Matrix</span>
                                <span class="tech-pill">Secure Checkout</span>
                                <span class="tech-pill">Responsive UI</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= PROCESS ================= -->
    <section class="premium-section" style="background: rgba(255,255,255,0.01);">
        <div class="container">
            <div class="text-center mb-5 pb-4">
                <span class="premium-badge">Methodology</span>
                <h2 class="display-5 fw-bold mb-3">How We <span class="text-gradient">Deliver</span></h2>
            </div>
            
            <div class="row text-center g-4 justify-content-center">
                <div class="col-lg-3 col-sm-6">
                    <div class="glass-card p-4 h-100">
                        <div class="process-step-number">1</div>
                        <h5 class="fw-bold text-white">Planning</h5>
                        <p class="text-secondary small mb-0">Deep requirements analysis phase and structural solution mapping.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="glass-card p-4 h-100 mt-lg-4" style="border-color: rgba(168, 85, 247, 0.2);">
                        <div class="process-step-number" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(124, 58, 237, 0.1)); border-color: rgba(168, 85, 247, 0.2); color: #c084fc;">2</div>
                        <h5 class="fw-bold text-white">Design</h5>
                        <p class="text-secondary small mb-0">Executing UI/UX mockups entirely focused on aesthetic & workflow friction.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="glass-card p-4 h-100">
                        <div class="process-step-number" style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(244, 114, 182, 0.1)); border-color: rgba(236, 72, 153, 0.2); color: #f472b6;">3</div>
                        <h5 class="fw-bold text-white">Development</h5>
                        <p class="text-secondary small mb-0">Coding deeply secure, highly scalable, and structurally optimized systems.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="glass-card p-4 h-100 mt-lg-4" style="border-color: rgba(74, 222, 128, 0.2);">
                        <div class="process-step-number" style="background: linear-gradient(135deg, rgba(74, 222, 128, 0.1), rgba(34, 197, 94, 0.1)); border-color: rgba(74, 222, 128, 0.2); color: #4ade80;">4</div>
                        <h5 class="fw-bold text-white">Delivery</h5>
                        <p class="text-secondary small mb-0">Rigorous load testing, seamless deployment, and continuous live support.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CTA ================= -->
    <section class="premium-section text-center position-relative" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(124, 58, 237, 0.1)); border-bottom: none;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="glass-card p-5 mx-auto" style="max-width: 800px; background: rgba(255,255,255,0.02); border: 1px solid rgba(124, 58, 237, 0.2);">
                <h2 class="display-4 fw-bold mb-3">Want to See Your <span class="text-gradient">Project Here?</span></h2>
                <p class="lead text-secondary mb-5">
                    Let’s collaborate to forcefully transition your vision from an idea into a highly successful digital product empire.
                </p>

                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="user/register.php" class="btn-premium px-5">
                        Start a Project
                    </a>
                    <a href="contact.php" class="btn-premium-outline px-5">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>

<?php
require_once "includes/footer.php";
?>
