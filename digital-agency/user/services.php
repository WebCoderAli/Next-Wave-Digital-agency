<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

// Fetch active services
$services = mysqli_query(
    $conn,
    "SELECT * FROM services WHERE status = 'active' ORDER BY id DESC"
);
?>

<?php require_once "../includes/header.php"; ?>

<!-- ================= SERVICES HEADER ================= -->
<section class="py-4 py-md-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end justify-content-between gap-3">
            <div>
                <h2 class="fw-bold mb-1">Services</h2>
                <p class="text-muted mb-0">Choose a service that fits your project needs.</p>
            </div>

            <a href="<?= BASE_URL ?>/user/my-orders.php" class="btn btn-premium-outline d-inline-flex align-items-center gap-2">
                <i class="bi bi-receipt"></i>
                <span>View my orders</span>
            </a>
        </div>
    </div>
</section>

<!-- ================= SERVICES GRID ================= -->
<section class="pb-5">
    <div class="container">
        <div class="row g-4">

        <?php if (mysqli_num_rows($services) > 0): ?>
            <?php while ($service = mysqli_fetch_assoc($services)): ?>

                <div class="col-md-4">
                    <div class="card glass-card service-card h-100">

                        <div class="service-card-media">
                            <?php if (!empty($service['image'])): ?>
                                <img
                                    src="../uploads/services/<?php echo htmlspecialchars($service['image']); ?>"
                                    class="service-card-img"
                                    alt="<?php echo htmlspecialchars($service['title']); ?>"
                                    loading="lazy"
                                >
                            <?php else: ?>
                                <div class="service-card-placeholder">
                                    <i class="bi bi-lightning-charge-fill" style="font-size: 2rem; opacity: .9;"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body d-flex flex-column p-4">

                            <h5 class="fw-bold mb-2">
                                <?php echo htmlspecialchars($service['title']); ?>
                            </h5>

                            <p class="text-muted mb-4">
                                <?php echo htmlspecialchars($service['short_description']); ?>
                            </p>

                            <!-- PRICE + CTA -->
                            <div class="mt-auto">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">Starting from</span>
                                    <div class="service-price mb-0">
                                        $<?php echo number_format($service['price'], 2); ?>
                                    </div>
                                </div>

                                <a
                                    href="order-service.php?id=<?php echo $service['id']; ?>"
                                    class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2"
                                >
                                    <i class="bi bi-chat-dots"></i>
                                    <span>Request & chat</span>
                                </a>

                            </div>

                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>

            <div class="col-12">
                <div class="glass-card p-4 p-md-5 text-center">
                    <div class="mb-2" style="opacity: .75;">
                        <i class="bi bi-grid-3x3-gap" style="font-size: 2rem;"></i>
                    </div>
                    <div class="fw-bold text-white mb-1">No services available</div>
                    <div class="text-muted">Please check back later.</div>
                </div>
            </div>

        <?php endif; ?>

        </div>
    </div>
</section>

<?php require_once "../includes/footer.php"; ?>
