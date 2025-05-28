
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Personal Info</title>
    <link rel="stylesheet" href="styles.css">
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
        .error { color: #d9534f; }
        .success { color: #198754; }
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
                        <a class="nav-link active" href="personal_info.php">View Personal Info</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="submit_maintenance_request.php">Submit Maintenance Request</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="track_maintenance_requests.php">Track Maintenance Requests</a>
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
                <h1>View Personal Info</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form>
                                <?php if (isset($_GET['error'])): ?>
                                    <p class="error">
                                        <?= htmlspecialchars($_GET['error']) ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (isset($_GET['success'])): ?>
                                    <p class="success">
                                        <?= htmlspecialchars($_GET['success']) ?>
                                    </p>
                                <?php endif; ?> 
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name"
                                        value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email"
                                        value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone"
                                        value="<?= isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : '' ?>" readonly>
                                </div>
                            </form>
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