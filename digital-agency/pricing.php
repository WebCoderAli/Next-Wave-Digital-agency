<?php
require_once "includes/header.php";
?>

<style>
/* ================= PREMIUM PAGE STYLES (PRICING) ================= */
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
    background: radial-gradient(circle, rgba(192, 132, 252, 0.15) 0%, rgba(0,0,0,0) 70%);
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
    color: #c084fc;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 20px;
}

/* Pricing Cards */
.pricing-card-premium {
    padding: 40px 30px;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.pricing-card-featured {
    border-color: rgba(124, 58, 237, 0.4);
    background: linear-gradient(180deg, rgba(124, 58, 237, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%);
    transform: scale(1.05);
    position: relative;
    z-index: 2;
}
.pricing-card-featured:hover {
    transform: scale(1.05) translateY(-8px);
}
@media (max-width: 991.98px) {
    .pricing-card-featured {
        transform: scale(1);
    }
    .pricing-card-featured:hover {
        transform: translateY(-8px);
    }
}

.price-box-premium {
    margin: 30px 0;
}
.price-box-premium .currency {
    font-size: 1.5rem;
    font-weight: 600;
    vertical-align: top;
    margin-right: 2px;
}
.price-box-premium .amount {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1;
}

.plan-features-premium {
    list-style: none;
    padding: 0;
    margin: 0 0 30px 0;
    flex-grow: 1;
}
.plan-features-premium li {
    padding: 12px 0;
    color: #cbd5e1;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
}
.plan-features-premium li:last-child {
    border-bottom: none;
}
.plan-features-premium li i.check {
    color: #4ade80;
    margin-right: 12px;
    font-weight: bold;
}
.plan-features-premium li i.x-mark {
    color: #f87171;
    margin-right: 12px;
    font-weight: bold;
}

.popular-badge-premium {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #4f46e5, #ec4899);
    color: #fff;
    font-size: 0.8rem;
    font-weight: bold;
    padding: 6px 16px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
}

.faq-item-glass {
    padding: 25px;
    height: 100%;
}
</style>

<div class="premium-wrapper">

    <!-- ================= PRICING HERO ================= -->
    <section class="premium-section pb-0" style="padding-top: 150px; border-bottom: none;">
        <div class="hero-glow-bg"></div>
        <div class="container text-center position-relative" style="z-index: 1;">
            <span class="premium-badge">Investment</span>
            <h1 class="display-3 fw-bold mb-4">Our Pricing <span class="text-gradient">Plans</span></h1>
            <p class="lead text-secondary mx-auto" style="max-width: 600px; font-size: 1.25rem;">
                Simple, transparent, and undeniably flexible pricing architected for scaling businesses of all sizes.
            </p>
        </div>
    </section>

    <!-- ================= PRICING PLANS ================= -->
    <section class="premium-section pt-5">
        <div class="container">
            <div class="row g-4 justify-content-center align-items-center">

                <!-- BASIC -->
                <div class="col-lg-4 col-md-6 z-index-1">
                    <div class="glass-card glass-card-hover pricing-card-premium text-center">
                        <h4 class="fw-bold mb-2">Basic</h4>
                        <p class="text-secondary mb-0">For small businesses</p>

                        <div class="price-box-premium text-gradient-alt">
                            <span class="currency">$</span><span class="amount">99</span>
                        </div>

                        <ul class="plan-features-premium text-start text-secondary fs-5">
                            <li class="text-secondary"><i class="check">✓</i> 1 Page Website</li>
                            <li class="text-secondary"><i class="check">✓</i> Basic Design</li>
                            <li class="text-secondary"><i class="check">✓</i> Mobile Responsive</li>
                            <li class="text-secondary"><i class="check">✓</i> Standard Email Support</li>
                            <li class="text-secondary"><i class="x-mark">✗</i> Custom Admin Panel</li>
                            <li class="text-secondary"><i class="x-mark">✗</i> SEO Optimization</li>
                        </ul>

                        <a href="user/register.php" class="btn-premium-outline w-100">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- STANDARD -->
                <div class="col-lg-4 col-md-6 z-index-2">
                    <div class="glass-card pricing-card-premium pricing-card-featured text-center">
                        <span class="popular-badge-premium">Most Popular</span>

                        <h4 class="fw-bold mb-2 text-white">Standard</h4>
                        <p class="text-secondary mb-0">The agency sweet spot</p>

                        <div class="price-box-premium text-gradient">
                            <span class="currency">$</span><span class="amount">199</span>
                        </div>

                        <ul class="plan-features-premium text-start">
                            <li class="text-secondary"><i class="check">✓</i> 5 Pages Website</li>
                            <li class="text-secondary"><i class="check">✓</i> Premium Custom Design</li>
                            <li class="text-secondary"><i class="check">✓</i> Mobile Responsive</li>
                            <li class="text-secondary"><i class="check">✓</i> Basic Admin Panel</li>
                            <li class="text-secondary"><i class="check">✓</i> SEO Optimization</li>
                            <li class="text-secondary"><i class="check">✓</i> Priority Support</li>
                        </ul>

                        <a href="user/register.php" class="btn-premium w-100 mt-2">
                            Select Plan
                        </a>
                    </div>
                </div>

                <!-- PREMIUM -->
                <div class="col-lg-4 col-md-6 z-index-1">
                    <div class="glass-card glass-card-hover pricing-card-premium text-center">
                        <h4 class="fw-bold mb-2">Premium</h4>
                        <p class="text-secondary mb-0">For growing enterprises</p>

                        <div class="price-box-premium text-white">
                            <span class="currency">$</span><span class="amount">399</span>
                        </div>

                        <ul class="plan-features-premium text-start">
                            <li class="text-secondary"><i class="check">✓</i> Unlimited Pages</li>
                            <li class="text-secondary"><i class="check">✓</i> Advanced Admin System</li>
                            <li class="text-secondary"><i class="check">✓</i> Full E-Commerce Features</li>
                            <li class="text-secondary"><i class="check">✓</i> Marketing Strategy Session</li>
                            <li class="text-secondary"><i class="check">✓</i> Deep SEO Strategy</li>
                            <li class="text-secondary"><i class="check">✓</i> 24/7 Dedicated Support</li>
                        </ul>

                        <a href="user/register.php" class="btn-premium-outline w-100">
                            Get Started
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= CUSTOM PRICING ================= -->
    <section class="premium-section" style="background: rgba(255,255,255,0.01);">
        <div class="container">
            <div class="glass-card p-5 text-center mx-auto" style="max-width: 800px; border-color: rgba(96,165,250,0.2); background: linear-gradient(135deg, rgba(255,255,255,0.02), rgba(96,165,250,0.05));">
                <h2 class="display-5 fw-bold mb-3">Need a <span class="text-gradient">Custom Solution?</span></h2>
                <p class="lead text-secondary mb-4 mx-auto" style="max-width: 600px;">
                    Not sure which plan perfectly aligns with your vision? We also architect highly specialized and perfectly scaled custom solutions.
                </p>
                <a href="contact.php" class="btn-premium-outline px-5">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- ================= FAQ ================= -->
    <section class="premium-section">
        <div class="container">
            <div class="text-center mb-5 pb-3">
                <span class="premium-badge">Questions</span>
                <h2 class="display-5 fw-bold mb-3">Pricing <span class="text-gradient">FAQs</span></h2>
            </div>

            <div class="row g-4 mx-auto" style="max-width: 900px;">
                <div class="col-md-6">
                    <div class="glass-card glass-card-hover faq-item-glass">
                        <h5 class="fw-bold text-white mb-3">Are there any hidden charges?</h5>
                        <p class="text-secondary mb-0">
                            Absolutely not. Our robust pricing strategy is 100% transparent and openly discussed before any project kickoff begins.
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="glass-card glass-card-hover faq-item-glass">
                        <h5 class="fw-bold text-white mb-3">Can I upgrade later?</h5>
                        <p class="text-secondary mb-0">
                            Yes! Our infrastructure guarantees you can seamlessly upgrade or customize your active plan dynamically at any time.
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="glass-card glass-card-hover faq-item-glass">
                        <h5 class="fw-bold text-white mb-3">Do you offer ongoing support?</h5>
                        <p class="text-secondary mb-0">
                            Absolutely. Ongoing and reliable support is heavily embedded across our DNA and included inherently in every plan.
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="glass-card glass-card-hover faq-item-glass">
                        <h5 class="fw-bold text-white mb-3">Is payment secure?</h5>
                        <p class="text-secondary mb-0">
                            Yes, all payments are securely encrypted. We utilize strict gateways and manually verify records to ensure ultimate security.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CTA ================= -->
    <section class="premium-section text-center position-relative" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(124, 58, 237, 0.1)); border-bottom: none;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="glass-card p-5 mx-auto" style="max-width: 750px; background: rgba(255,255,255,0.02); border: 1px solid rgba(124, 58, 237, 0.2);">
                <h2 class="display-4 fw-bold mb-3">Start Your <span class="text-gradient">Project</span> Today</h2>
                <p class="lead text-secondary mb-5">
                    Join our rapidly scaling platform and collaborate with an ultra-professional digital squad.
                </p>
                <a href="user/register.php" class="btn-premium btn-lg px-5">
                    Create Free Account
                </a>
            </div>
        </div>
    </section>

</div>

<?php
require_once "includes/footer.php";
?>
