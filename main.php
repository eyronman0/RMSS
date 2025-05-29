<?php
session_start();
if (!isset($_SESSION['tenant_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <!-- Bootstrap CSS CDN -->
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
    .dashboard-header {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .card h3 {
      margin-top: 0;
      margin-bottom: 10px;
      color: #0b1e7f;
      font-size: 1.25rem;
    }
    .card p {
      margin: 0;
      color: #555;
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
              <a class="nav-link active" href="main.php">Dashboard</a>
            </li>
            <li class="nav-item mb-2">
              <a class="nav-link" href="personal_info.php">View Personal Info</a>
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
        <div class="dashboard-header mb-2">
          Welcome, <?= htmlspecialchars($_SESSION['tenant_name'] ?? 'Resident') ?>!
        </div>
        <div class="mb-4">Announcements summary</div>
        <div class="row g-4">
          <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h3><a href="personal_info.php" class="text-decoration-none">Personal Info</a></h3>
                <p>Access your personal and resident details.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h3><a href="submit_maintenance_request.php" class="text-decoration-none">Submit Maintenance Request</a></h3>
                <p>Request repairs and services quickly.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h3><a href="pay_rent.php" class="text-decoration-none">Pay Rent</a></h3>
                <p>Make rent payments online.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h3><a href="track_maintenance_requests.php" class="text-decoration-none">Track Maintenance Requests</a></h3>
                <p>Check the progress of your requests.</p>
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