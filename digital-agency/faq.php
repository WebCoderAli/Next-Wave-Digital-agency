<?php
require_once "includes/header.php";
?>

<!-- HERO -->
<section class="py-5 bg-dark text-white text-center">
    <div class="container">
        <h1 class="fw-bold">Frequently Asked Questions</h1>
        <p class="lead mt-3">
            Find answers to common questions about our services,
            pricing, and workflow.
        </p>
    </div>
</section>


<!-- FAQ SECTION -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-4">General Questions</h2>

        <div class="accordion" id="faqAccordion">

            <!-- Q1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="q1">
                    <button class="accordion-button" type="button"
                            data-bs-toggle="collapse" data-bs-target="#a1">
                        What services do you offer?
                    </button>
                </h2>
                <div id="a1" class="accordion-collapse collapse show"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">
                        We offer web development, graphic design,
                        UI/UX design, and digital marketing services
                        tailored to your business needs.
                    </div>
                </div>
            </div>

            <!-- Q2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="q2">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#a2">
                        How does the ordering process work?
                    </button>
                </h2>
                <div id="a2" class="accordion-collapse collapse"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">
                        You create an account, choose a service, submit
                        your requirements, upload payment proof, and
                        our admin team reviews and approves your order.
                    </div>
                </div>
            </div>

            <!-- Q3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="q3">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#a3">
                        Are your prices fixed?
                    </button>
                </h2>
                <div id="a3" class="accordion-collapse collapse"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">
                        We offer both fixed packages and custom pricing.
                        The final price depends on project requirements.
                    </div>
                </div>
            </div>

            <!-- Q4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="q4">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#a4">
                        How long does a project take?
                    </button>
                </h2>
                <div id="a4" class="accordion-collapse collapse"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">
                        Project timelines vary by complexity. Small
                        projects may take a few days, while larger
                        systems may take several weeks.
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SUPPORT FAQ -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-4">Support & Payments</h2>

        <div class="row g-4">

            <div class="col-md-6">
                <div class="card shadow-sm h-100 p-4">
                    <h5 class="fw-bold">Is payment secure?</h5>
                    <p class="text-muted mt-2">
                        Yes. Payments are verified manually by the admin
                        team using uploaded proof to ensure security.
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100 p-4">
                    <h5 class="fw-bold">Do you provide after-sales support?</h5>
                    <p class="text-muted mt-2">
                        Absolutely. We provide support and revisions
                        based on the selected service package.
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100 p-4">
                    <h5 class="fw-bold">Can I contact admin directly?</h5>
                    <p class="text-muted mt-2">
                        Yes. Once registered, you can chat directly
                        with the admin via the built-in chat system.
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100 p-4">
                    <h5 class="fw-bold">Do you offer custom projects?</h5>
                    <p class="text-muted mt-2">
                        Yes. You can describe custom requirements while
                        placing an order or contact us directly.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-dark text-white text-center">
    <div class="container">
        <h2 class="fw-bold">Still Have Questions?</h2>
        <p class="lead mt-2">
            Create an account or contact us for personalized assistance.
        </p>

        <a href="contact.php" class="btn btn-outline-light btn-lg mt-3">
            Contact Us
        </a>
        <a href="user/register.php" class="btn btn-primary btn-lg mt-3 ms-2">
            Register Now
        </a>
    </div>
</section>

<?php
require_once "includes/footer.php";
?>
