<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

adminAuth();

/*
|--------------------------------------------------------------------------
| DASHBOARD STATS
|--------------------------------------------------------------------------
*/

// Total Orders
$totalOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders")
)['total'];

// Approved Orders
$approvedOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status='approved'")
)['total'];

// Pending Orders
$pendingOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status='pending'")
)['total'];

// Total Revenue
$totalRevenue = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(services.price) AS revenue
         FROM orders
         JOIN services ON services.id = orders.service_id
         WHERE orders.status='approved'"
    )
)['revenue'] ?? 0;

// Today Revenue
$todayRevenue = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(services.price) AS revenue
         FROM orders
         JOIN services ON services.id = orders.service_id
         WHERE orders.status='approved'
         AND DATE(orders.created_at) = CURDATE()"
    )
)['revenue'] ?? 0;

/*
|--------------------------------------------------------------------------
| MONTHLY REVENUE (LAST 12 MONTHS)
|--------------------------------------------------------------------------
*/
$monthlyRevenue = array_fill(1, 12, 0);

$result = mysqli_query(
    $conn,
    "SELECT 
        MONTH(orders.created_at) AS month,
        SUM(services.price) AS revenue
     FROM orders
     JOIN services ON services.id = orders.service_id
     WHERE orders.status='approved'
       AND YEAR(orders.created_at) = YEAR(CURDATE())
     GROUP BY MONTH(orders.created_at)"
);

while ($row = mysqli_fetch_assoc($result)) {
    $monthlyRevenue[(int)$row['month']] = (float)$row['revenue'];
}

// Recent Orders
$recentOrders = mysqli_query(
    $conn,
    "SELECT orders.*, users.name AS user_name, services.title, services.price
     FROM orders
     JOIN users ON users.id = orders.user_id
     JOIN services ON services.id = orders.service_id
     ORDER BY orders.created_at DESC
     LIMIT 5"
);
?>

<?php require_once "../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Command <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Overview</span></h3>
    <span class="badge" style="background: rgba(99, 102, 241, 0.15); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.3); padding: 8px 16px; font-size: 0.9rem;">
        LIVE FEED
    </span>
</div>

<!-- STAT CARDS -->
<div class="row g-4 mb-5">

    <div class="col-md-3">
        <div class="glass-card glass-card-hover text-center p-4">
            <h6 class="text-secondary text-uppercase fw-bold mb-3" style="letter-spacing: 1px; font-size: 0.8rem;">Total Orders</h6>
            <h2 class="fw-bold mb-0 text-white" style="font-family: 'Outfit', sans-serif;"><?php echo $totalOrders; ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="glass-card glass-card-hover text-center p-4" style="border-bottom: 2px solid rgba(34, 197, 94, 0.4);">
            <h6 class="text-secondary text-uppercase fw-bold mb-3" style="letter-spacing: 1px; font-size: 0.8rem;">Approved Orders</h6>
            <h2 class="fw-bold mb-0" style="color: #4ade80; font-family: 'Outfit', sans-serif;"><?php echo $approvedOrders; ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="glass-card glass-card-hover text-center p-4" style="border-bottom: 2px solid rgba(234, 179, 8, 0.4);">
            <h6 class="text-secondary text-uppercase fw-bold mb-3" style="letter-spacing: 1px; font-size: 0.8rem;">Pending Orders</h6>
            <h2 class="fw-bold mb-0" style="color: #facc15; font-family: 'Outfit', sans-serif;"><?php echo $pendingOrders; ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="glass-card glass-card-hover text-center p-4" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(124, 58, 237, 0.05)); border: 1px solid rgba(124, 58, 237, 0.3);">
            <h6 class="text-uppercase fw-bold mb-3" style="color: #c084fc; letter-spacing: 1px; font-size: 0.8rem;">Total Revenue</h6>
            <h2 class="fw-bold mb-0 text-white" style="font-family: 'Outfit', sans-serif;">
                $<?php echo number_format($totalRevenue, 2); ?>
            </h2>
        </div>
    </div>

</div>

<div class="row g-4 mb-5">
    <!-- MONTHLY REVENUE CHART -->
    <div class="col-lg-8">
        <div class="glass-card h-100">
            <h5 class="fw-bold mb-4 text-white" style="font-family: 'Outfit', sans-serif;">Monthly Revenue <span class="text-secondary fw-normal fs-6">/ This Year</span></h5>
            <canvas id="revenueChart" height="120"></canvas>
        </div>
    </div>

    <!-- TODAY REVENUE -->
    <div class="col-lg-4">
        <div class="glass-card h-100 d-flex flex-column justify-content-center align-items-center text-center p-5" style="border: 1px solid rgba(52, 211, 153, 0.3); background: radial-gradient(circle at center, rgba(52, 211, 153, 0.1) 0%, transparent 70%);">
            <div class="d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px; border-radius: 20px; background: rgba(52, 211, 153, 0.15); border: 1px solid rgba(52, 211, 153, 0.3);">
                <span style="font-size: 2rem;">📈</span>
            </div>
            <h5 class="text-secondary text-uppercase fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.9rem;">Today's Inflow</h5>
            <h1 class="fw-bold display-4 mb-0" style="color: #10b981; font-family: 'Outfit', sans-serif;">
                $<?php echo number_format($todayRevenue, 2); ?>
            </h1>
        </div>
    </div>
</div>

<!-- RECENT ORDERS -->
<div class="glass-card p-4 p-md-5 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Recent Operations</h5>
        <a href="orders/list.php" class="btn btn-sm" style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3); color: #818cf8; border-radius: 6px; padding: 6px 16px;">View All</a>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless table-premium align-middle mb-0">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Client</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Service Undertaken</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Total Value</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Status</th>
                    <th class="text-secondary fw-semibold text-uppercase text-end" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($recentOrders)): 
                    // Dynamic Avatar
                    $nameParts = explode(' ', trim($order['user_name']));
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    if (count($nameParts) > 1) {
                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                    }
                    
                    $gradients = [
                        'linear-gradient(135deg, #6366f1, #8b5cf6)',
                        'linear-gradient(135deg, #10b981, #059669)',
                        'linear-gradient(135deg, #f59e0b, #d97706)',
                        'linear-gradient(135deg, #ef4444, #b91c1c)',
                        'linear-gradient(135deg, #0ea5e9, #0369a1)',
                        'linear-gradient(135deg, #ec4899, #be185d)'
                    ];
                    $avatarColor = $gradients[abs(crc32($order['user_name'])) % count($gradients)];
                ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: <?= $avatarColor ?>; font-size: 0.9rem;">
                                    <?= $initials ?>
                                </div>
                                <span class="fw-medium"><?= htmlspecialchars($order['user_name']); ?></span>
                            </div>
                        </td>
                        <td class="py-3 fw-medium">
                            <?= htmlspecialchars($order['title']); ?>
                        </td>
                        <td class="fw-bold py-3">
                            $<?= number_format($order['price'], 2); ?>
                        </td>
                        <td class="py-3">
                            <?php if($order['status'] == 'approved'): ?>
                                <span class="badge" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac; padding: 6px 12px;">Approved</span>
                            <?php elseif($order['status'] == 'pending'): ?>
                                <span class="badge" style="background: rgba(234, 179, 8, 0.15); border: 1px solid rgba(234, 179, 8, 0.3); color: #fde047; padding: 6px 12px;">Pending</span>
                            <?php elseif($order['status'] == 'completed'): ?>
                                <span class="badge" style="background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.3); color: #7dd3fc; padding: 6px 12px;">Completed</span>
                            <?php else: ?>
                                <span class="badge" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 6px 12px;">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-secondary text-end py-3">
                            <?= date("d M Y", strtotime($order['created_at'])); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('revenueChart');

// Color Logic based on class
const getChartColors = () => {
    const isLight = document.body.classList.contains('light-mode');
    return {
        text: isLight ? '#475569' : '#94a3b8',
        grid: isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)',
        line: '#8b5cf6',
        fill: isLight ? 'rgba(139, 92, 246, 0.1)' : 'rgba(139, 92, 246, 0.2)'
    };
};

let colors = getChartColors();

let revChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Revenue ($)',
            data: <?php echo json_encode(array_values($monthlyRevenue)); ?>,
            borderColor: colors.line,
            backgroundColor: colors.fill,
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#8b5cf6',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                titleColor: '#ffffff',
                bodyColor: '#cbd5e1',
                padding: 12,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return '$' + context.parsed.y.toLocaleString();
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: colors.grid,
                    drawBorder: false
                },
                ticks: {
                    color: colors.text,
                    callback: function(value) {
                        return '$' + value;
                    }
                }
            },
            x: {
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    color: colors.text
                }
            }
        }
    }
});

// Watch for theme toggle adjustments
const observer = new MutationObserver(() => {
    colors = getChartColors();
    revChart.options.scales.x.ticks.color = colors.text;
    revChart.options.scales.y.ticks.color = colors.text;
    revChart.options.scales.y.grid.color = colors.grid;
    revChart.data.datasets[0].backgroundColor = colors.fill;
    revChart.update();
});

observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
</script>

        </div> <!-- End p-4 wrapper from sidebar -->
    </main> <!-- End admin-content -->
</div> <!-- End admin-wrapper -->
</body>
</html>

