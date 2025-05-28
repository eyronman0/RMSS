<?php
include "conn_db.php";
$sql = "SELECT 
            p.*, 
            CONCAT(t.first_name, ' ', t.last_name) AS tenant_name, 
            u.unit_number 
        FROM payments p
        JOIN tenants t ON p.tenant_id = t.id
        JOIN units u ON t.unit_id = u.id
        ORDER BY p.payment_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Owner Financials</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
    .card h3, .card h2 {
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
                    <h2 class="fs-5 text-center mb-4">Owner Panel</h2>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link active" href="ownerpage.php">Dashboard</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownerunits.php">Manage Units</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownertenants.php">Manage Tenants</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownermaintenance.php">Maintenance</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownerfinancials.php">Financials</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownerdocs.php">Documents</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>

    <main class="col-md-10 ms-sm-auto px-md-4 py-4">
      <h1>Payment History</h1>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Tenant</th>
            <th>Unit</th>
            <th>Amount</th>
            <th>Payment Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while($payment = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($payment['tenant_name']) ?></td>
            <td><?= htmlspecialchars($payment['unit_number']) ?></td>
            <td>$<?= number_format($payment['amount'], 2) ?></td>
            <td><?= htmlspecialchars($payment['payment_date']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>
  </div>
</div>
</body>
</html>
