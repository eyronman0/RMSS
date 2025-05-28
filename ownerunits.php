<?php
include "conn_db.php";
$sql = "SELECT t.*, u.unit_number FROM tenants t JOIN units u ON t.unit_id = u.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Owner Tenants</title>
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
      <h1>Tenants</h1>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Unit Number</th>
            <th>Rent Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($tenant = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($tenant['first_name'] . ' ' . $tenant['last_name']) ?></td>
            <td><?= htmlspecialchars($tenant['contact']) ?></td>
            <td><?= htmlspecialchars($tenant['unit_number']) ?></td>
            <td><?= htmlspecialchars(ucfirst($tenant['payment_status'])) ?></td>
            <td>
              <a href="?delete=<?= $tenant['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this tenant?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>
  </div>
</div>
</body>
</html>
