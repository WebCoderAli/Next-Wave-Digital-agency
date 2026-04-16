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
<section class="py-4">
    <div class="container">
        <h2 class="fw-bold mb-1">Our Services</h2>
        <p class="text-muted mb-0">
            Choose a service that best fits your project needs.
        </p>
    </div>
</section>

<!-- ================= SERVICES GRID ================= -->
<section class="pb-5">
    <div class="container">
        <div class="row g-4">

        <?php if (mysqli_num_rows($services) > 0): ?>
            <?php while ($service = mysqli_fetch_assoc($services)): ?>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">

                        <?php if ($service['image']): ?>
                            <img
                                src="../uploads/services/<?php echo $service['image']; ?>"
                                class="card-img-top"
                                alt="Service Image"
                                style="height:220px;object-fit:cover;"
                            >
                        <?php endif; ?>

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
                                    <h4 class="fw-bold mb-0">
                                        $<?php echo number_format($service['price'], 2); ?>
                                    </h4>
                                </div>

                                <a
                                    href="order-service.php?id=<?php echo $service['id']; ?>"
                                    class="btn btn-dark w-100"
                                >
                                    🚀 Order This Service
                                </a>

                            </div>

                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>

            <div class="col-12">
                <div class="alert alert-info text-center">
                    No services available at the moment.
                </div>
            </div>

        <?php endif; ?>

        </div>
    </div>
</section>

<?php require_once "../includes/footer.php"; ?>
