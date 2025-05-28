<?php
// Get payment method from POST or fallback to GET for demo/testing
$paymentMethod = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';
} elseif (isset($_GET['paymentMethod'])) {
    $paymentMethod = $_GET['paymentMethod'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Rent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #0b1e7f;
            color: white;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #2336a3;
            color: #fff;
        }
        @media (max-width: 767px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar py-4">
            <div class="position-sticky">
                <h2 class="fs-5 text-center mb-4">Apartment Management</h2>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="main.php">Dashboard</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="personal_info.php">View Personal Info</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="submit_maintenance_request.php">Submit Maintenance Request</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link active" href="track_maintenance_requests.php">Track Maintenance Requests</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="pay_rent.php">Pay Rent</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-4">
            <div class="mb-4 text-center">
                <h1>Pay Rent</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <?php if ($paymentMethod === 'cash'): ?>
                                <h2 class="card-title mb-4">Thank You for Your Payment!</h2>
                                <p class="mb-3">Your cash payment has been recorded. The owner will be notified to collect the payment.</p>
                                <form>
                                    <div class="alert alert-success" role="alert">
                                        Thank you for paying with cash! The owner has been notified.
                                    </div>
                                    <a href="main.php" class="btn btn-primary">Back to Dashboard</a>
                                </form>
                            <?php else: ?>
                                <h2 class="card-title mb-4">Transaction Confirmed</h2>
                                <div class="alert alert-success" role="alert">
                                    Your payment has been successfully submitted!
                                </div>
                                <a href="main.php" class="btn btn-primary">Back to Dashboard</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<!-- Bootstrap JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>