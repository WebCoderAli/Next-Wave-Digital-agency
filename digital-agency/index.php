<?php
require_once "includes/header.php";
?>

<style>
/* Premium Fonts */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;500;600&display=swap');

/* Global overwrites for index */
.index-page-wrapper {
    font-family: 'Inter', sans-serif;
    color: #e2e8f0;
    background-color: #050505; /* Deep dark background */
    overflow-x: hidden;
}

.index-page-wrapper h1, 
.index-page-wrapper h2, 
.index-page-wrapper h3, 
.index-page-wrapper h4, 
.index-page-wrapper h5, 
.index-page-wrapper h6 {
    font-family: 'Outfit', sans-serif;
}

/* Glassmorphism Utilities */
.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border-radius: 24px;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease, border-color 0.4s ease;
}

.glass-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    border-color: rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.05);
}

/* Gradient Text */
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

/* Gradient Buttons */
.btn-premium {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 14px 32px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px -5px rgba(124, 58, 237, 0.5);
    position: relative;
    overflow: hidden;
    z-index: 1;
    display: inline-block;
    text-decoration: none;
}
.btn-premium::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
    z-index: -1;
    transition: opacity 0.3s ease;
    opacity: 0;
}
.btn-premium:hover::before {
    opacity: 1;
}
.btn-premium:hover {
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 15px 25px -5px rgba(124, 58, 237, 0.6);
}

.btn-premium-outline {
    background: rgba(255,255,255,0.05);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    padding: 14px 32px;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    display: inline-block;
    text-decoration: none;
}
.btn-premium-outline:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    transform: translateY(-3px);
    border-color: rgba(255,255,255,0.4);
}

/* Sections */
.premium-section {
    padding: 120px 0;
    position: relative;
}

/* Hero Modifications */
.hero-wrapper {
    position: relative;
    min-height: 90vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: radial-gradient(circle at 15% 50%, rgba(79, 70, 229, 0.15), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(192, 132, 252, 0.15), transparent 25%);
}

.hero-glow {
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.2) 0%, rgba(0,0,0,0) 70%);
    top: -200px;
    right: -200px;
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
}

/* Stats */
.stat-item h3 {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

/* Team Images Overwrite */
.team-glass {
    padding: 20px;
    border-radius: 30px;
}
.team-glass img {
    border-radius: 20px;
    aspect-ratio: 1;
    object-fit: cover;
    margin-bottom: 20px;
    filter: grayscale(80%) sepia(10%) hue-rotate(200deg);
    transition: all 0.5s ease;
}
.team-glass:hover img {
    filter: grayscale(0%) sepia(0%) hue-rotate(0deg);
    transform: scale(1.03);
}

/* Gradient line */
.gradient-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    width: 100%;
}

/* Animations */
@keyframes dashGlow {
    0% { transform: translateY(0); box-shadow: 0 0 20px rgba(96, 165, 250, 0.2); }
    50% { transform: translateY(-10px); box-shadow: 0 0 40px rgba(192, 132, 252, 0.4); }
    100% { transform: translateY(0); box-shadow: 0 0 20px rgba(96, 165, 250, 0.2); }
}

.floating-element {
    animation: dashGlow 6s infinite ease-in-out;
}

/* Badges */
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

/* Fix navbar color bleed if previous CSS isn't fully isolated */
nav.navbar {
    z-index: 1020;
}
</style>

<div class="index-page-wrapper">

    <!-- ================= HERO SECTION ================= -->
    <section class="hero-wrapper">
        <div class="hero-glow"></div>
        <div class="container position-relative" style="z-index: 1;">
            <div class="row align-items-center">
                <!-- HERO TEXT -->
                <div class="col-lg-6 hero-content text-center text-lg-start mb-5 mb-lg-0">
                    <span class="premium-badge">Next-Gen Agency</span>
                    <h1 class="hero-title display-3 fw-bold mb-4">
                        Crafting Digital <br class="d-none d-lg-block" />
                        <span class="text-gradient">Masterpieces</span>
                    </h1>
                    <p class="hero-subtitle lead mb-5 text-secondary" style="font-size: 1.25rem;">
                        Transform your digital presence with our cutting-edge design, 
                        innovative development, and data-driven marketing solutions tailored for the future.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="<?= BASE_URL ?>/user/register.php" class="btn-premium">
                            Start Your Journey
                        </a>
                        <a href="<?= BASE_URL ?>/portfolio.php" class="btn-premium-outline">
                            View Our Work
                        </a>
                    </div>
                    
                    <div class="mt-5 d-flex flex-wrap justify-content-center justify-content-lg-start align-items-center gap-4 text-secondary opacity-75">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-white fw-bold">✓</span> Fast Delivery
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-white fw-bold">✓</span> 24/7 Support
                        </div>
                    </div>
                </div>

                <!-- HERO IMAGE / ILLUSTRATION -->
                <div class="col-lg-6 text-center position-relative">
                    <div class="floating-element glass-card p-4 mx-auto" style="max-width: 450px; border-radius: 40px; background: rgba(255,255,255,0.02)">
                        <!-- Fallback using unsplash if the asset is missing or low quality -->
                        <img src="assets/images/hero.png" class="img-fluid rounded-4 w-100" style="filter: drop-shadow(0 20px 30px rgba(0,0,0,0.5)); object-fit: cover;" alt="Digital Agency Illustration" onerror="this.src='https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';">
                        
                        <!-- Floating chips -->
                        <div class="position-absolute top-0 start-0 translate-middle glass-card p-3 d-none d-md-block" style="border-radius: 20px; animation: dashGlow 4s infinite ease-in-out reverse;">
                            <h6 class="mb-0 fw-bold text-gradient">UI/UX Design</h6>
                        </div>
                        <div class="position-absolute bottom-0 end-0 glass-card p-3 d-none d-md-block" style="border-radius: 20px; transform: translate(20%, -20%); animation: dashGlow 5s infinite ease-in-out;">
                            <h6 class="mb-0 fw-bold text-gradient-alt">Web Dev</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="gradient-divider"></div>

    <!-- ================= SERVICES ================= -->
    <section class="premium-section" id="services">
        <div class="container">
            <div class="text-center mb-5 pb-3">
                <span class="premium-badge">What We Do</span>
                <h2 class="display-5 fw-bold mb-3">Elevate Your <span class="text-gradient">Brand</span></h2>
                <p class="text-secondary mx-auto" style="max-width: 600px; font-size: 1.1rem;">
                    We leverage state-of-the-art technologies and pristine design principles to deliver solutions that outshine the competition.
                </p>
            </div>

            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card h-100 p-5 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, rgba(96,165,250,0.2), rgba(59,130,246,0.1)); border: 1px solid rgba(96,165,250,0.2);">
                            <span style="font-size: 2rem;">💻</span>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">Web Development</h4>
                        <p class="text-secondary mb-0">
                            High-performance, scalable web applications built with modern frameworks and flawless architecture.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="glass-card h-100 p-5 text-center mt-lg-n4" style="border-color: rgba(192, 132, 252, 0.2);">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, rgba(192,132,252,0.2), rgba(168,85,247,0.1)); border: 1px solid rgba(192,132,252,0.2);">
                            <span style="font-size: 2rem;">✨</span>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">UI/UX Design</h4>
                        <p class="text-secondary mb-0">
                            Aesthetic, user-centric interfaces crafted to provide unforgettable digital experiences and high conversions.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="glass-card h-100 p-5 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, rgba(52,211,153,0.2), rgba(16,185,129,0.1)); border: 1px solid rgba(52,211,153,0.2);">
                            <span style="font-size: 2rem;">🚀</span>
                        </div>
                        <h4 class="fw-bold mb-3 text-white">Digital Marketing</h4>
                        <p class="text-secondary mb-0">
                            Data-driven growth strategies, SEO, and targeted campaigns that scale your audience and revenue.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= STATS / WHY CHOOSE US ================= -->
    <section class="premium-section" style="background: rgba(255,255,255,0.01); border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05);">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5 text-center text-lg-start">
                    <h2 class="display-5 fw-bold mb-4">Why we are the <span class="text-gradient">Right Choice</span></h2>
                    <p class="text-secondary mb-4 mx-auto mx-lg-0" style="font-size: 1.1rem; line-height: 1.8; max-width: 500px;">
                        We go beyond just delivering projects. We build lasting partnerships and craft solutions designed for ultimate success. Our track record speaks for itself.
                    </p>
                    <a href="<?= BASE_URL ?>/about.php" class="btn-premium-outline">Discover Our Story</a>
                </div>
                
                <div class="col-lg-7">
                    <div class="row g-4 text-center">
                        <div class="col-sm-6">
                            <div class="glass-card p-4 p-md-5 stat-item h-100">
                                <h3 class="text-gradient">10+</h3>
                                <p class="text-secondary fw-semibold text-uppercase mb-0" style="letter-spacing: 1px;">Projects Done</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="glass-card p-4 p-md-5 stat-item h-100">
                                <h3 class="text-gradient-alt">100%</h3>
                                <p class="text-secondary fw-semibold text-uppercase mb-0" style="letter-spacing: 1px;">Satisfaction</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="glass-card p-4 p-md-5 stat-item h-100">
                                <h3 class="text-white">24/7</h3>
                                <p class="text-secondary fw-semibold text-uppercase mb-0" style="letter-spacing: 1px;">Premium Support</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="glass-card p-4 p-md-5 stat-item h-100" style="background: linear-gradient(135deg, rgba(79,70,229,0.1), rgba(124,58,237,0.1)); border-color: rgba(124, 58, 237, 0.2);">
                                <h3 class="text-gradient">5x</h3>
                                <p class="text-secondary fw-semibold text-uppercase mb-0" style="letter-spacing: 1px;">Growth Average</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= PROCESS ================= -->
    <section class="premium-section position-relative">
        <div class="position-absolute top-50 start-0 end-0 d-none d-lg-block" style="height: 2px; background: linear-gradient(90deg, transparent, rgba(124,58,237,0.3), transparent); z-index: 0; transform: translateY(-50%);"></div>
        
        <div class="container position-relative" style="z-index: 1;">
            <div class="text-center mb-5 pb-4">
                <span class="premium-badge">Workflow</span>
                <h2 class="display-5 fw-bold mb-3">Our Seamless <span class="text-gradient">Process</span></h2>
            </div>

            <div class="row g-4 text-center">
                <div class="col-lg-3 col-sm-6">
                    <div class="glass-card p-4 h-100">
                        <div class="mx-auto mb-4 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px; border-radius: 50%; background: #4f46e5; color: #fff; box-shadow: 0 0 20px rgba(79,70,229,0.5);">1</div>
                        <h5 class="fw-bold text-white mb-2">Discovery</h5>
                        <p class="text-secondary fs-6 mb-0">Deep dive into your brand, vision, and core requirements.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="glass-card p-4 h-100 mt-lg-4">
                        <div class="mx-auto mb-4 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px; border-radius: 50%; background: #8b5cf6; color: #fff; box-shadow: 0 0 20px rgba(139,92,246,0.5);">2</div>
                        <h5 class="fw-bold text-white mb-2">Strategy</h5>
                        <p class="text-secondary fs-6 mb-0">Crafting a bespoke roadmap and flawless architectural design.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="glass-card p-4 h-100">
                        <div class="mx-auto mb-4 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px; border-radius: 50%; background: #d946ef; color: #fff; box-shadow: 0 0 20px rgba(217,70,239,0.5);">3</div>
                        <h5 class="fw-bold text-white mb-2">Execution</h5>
                        <p class="text-secondary fs-6 mb-0">Turning designs into robust, high-performance digital products.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="glass-card p-4 h-100 mt-lg-4">
                        <div class="mx-auto mb-4 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px; border-radius: 50%; background: #ec4899; color: #fff; box-shadow: 0 0 20px rgba(236,72,153,0.5);">4</div>
                        <h5 class="fw-bold text-white mb-2">Launch</h5>
                        <p class="text-secondary fs-6 mb-0">Deploying with perfection and delivering ongoing premium support.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TEAM ================= -->
    <section class="premium-section" style="background: radial-gradient(circle at top, rgba(255,255,255,0.02) 0%, transparent 70%); border-top: 1px solid rgba(255,255,255,0.05);">
        <div class="container">
            <div class="text-center mb-5 pb-3">
                <span class="premium-badge">The Minds</span>
                <h2 class="display-5 fw-bold mb-3">Meet the <span class="text-gradient">Innovators</span></h2>
            </div>

            <div class="row g-5 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card team-glass text-center">
                        <img src="assets/images/team/ali.jpg" class="w-100" alt="Ali Hamza" onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80';">
                        <h4 class="fw-bold text-white mb-1">Ali Hamza</h4>
                        <p class="text-gradient fw-semibold mb-0">Lead Developer</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card team-glass text-center">
                        <img src="assets/images/team/razzaq.jpg" class="w-100" alt="Abdul Razzaq" onerror="this.src='https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80';">
                        <h4 class="fw-bold text-white mb-1">Abdul Razzaq</h4>
                        <p class="text-gradient-alt fw-semibold mb-0">Project Manager</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card team-glass text-center">
                        <img src="assets/images/team/fiza.jpg" class="w-100" alt="Fiza Mehmood" onerror="this.src='https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80';">
                        <h4 class="fw-bold text-white mb-1">Fiza Mehmood</h4>
                        <!-- Inlined gradient for text -->
                        <p class="fw-semibold mb-0" style="background: linear-gradient(135deg, #f472b6, #fb7185); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">UI/UX Designer</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CTA ================= -->
    <section class="premium-section text-center position-relative" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(124, 58, 237, 0.05)); border-top: 1px solid rgba(255,255,255,0.05);">
        <div class="container position-relative" style="z-index: 1;">
            <div class="glass-card p-5 mx-auto" style="max-width: 800px; background: rgba(255,255,255,0.02); border: 1px solid rgba(124, 58, 237, 0.3);">
                <h2 class="display-4 fw-bold mb-4">Let's Build Something <span class="text-gradient">Extraordinary</span></h2>
                <p class="lead text-secondary mb-5">
                    Ready to transform your ideas into reality? Partner with us and redefine what's possible for your business.
                </p>
                <a href="<?= BASE_URL ?>/user/register.php" class="btn-premium btn-lg">
                    Start Your Project Today
                </a>
            </div>
        </div>
    </section>

</div>

<?php
require_once "includes/footer.php";
?>
