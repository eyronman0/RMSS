<?php
include "conn_db.php";
$tenants_result = $conn->query("SELECT t.id, CONCAT(t.first_name, ' ', t.last_name) as name FROM tenants t JOIN units u ON t.unit_id = u.id");
$documents_result = $conn->query("
    SELECT d.*, CONCAT(t.first_name, ' ', t.last_name) AS tenant_name
    FROM documents d
    JOIN tenants t ON d.tenant_id = t.id
    ORDER BY d.upload_date DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Owner Documents</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background-color: #f4f4f4; }
    .sidebar { min-height: 100vh; background-color: #0b1e7f; color: white; }
    .sidebar .nav-link { color: #fff; }
    .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #2336a3; color: #fff; }
    .dashboard-header { font-size: 2rem; font-weight: bold; margin-bottom: 10px; }
    .card h3, .card h2 { margin-top: 0; margin-bottom: 10px; color: #0b1e7f; font-size: 1.25rem; }
    .card p { margin: 0; color: #555; }
    @media (max-width: 767px) { .sidebar { min-height: auto; } }
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
          <li class="nav-item mb-2"><a class="nav-link" href="ownerpage.php">Dashboard</a></li>
          <li class="nav-item mb-2"><a class="nav-link" href="ownerunits.php">Manage Units</a></li>
          <li class="nav-item mb-2"><a class="nav-link" href="ownertenants.php">Manage Tenants</a></li>
          <li class="nav-item mb-2"><a class="nav-link" href="ownermaintenance.php">Maintenance</a></li>
          <li class="nav-item mb-2"><a class="nav-link" href="ownerfinancials.php">Financials</a></li>
          <li class="nav-item mb-2"><a class="nav-link active" href="ownerdocs.php">Documents</a></li>
          <li class="nav-item mb-2"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </nav>
    <main class="col-md-10 ms-sm-auto px-md-4 py-4">
      <h1>Documents</h1>
      <form action="ownerdocs.php" method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="row g-3 align-items-center">
          <div class="col-md-4">
            <select name="tenant_id" class="form-select" required>
              <option value="">Select Tenant</option>
              <?php while($tenant = $tenants_result->fetch_assoc()): ?>
              <option value="<?= $tenant['id'] ?>"><?= htmlspecialchars($tenant['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-4">
            <input type="file" name="document" class="form-control" required />
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Upload Document</button>
          </div>
          <div class="col-md-4">
            <input type="text" name="doc_type" class="form-control" placeholder="Document Type (e.g., Lease)" required>
          </div>
        </div>
      </form>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Tenant</th>
            <th>File</th>
            <th>Upload Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while($doc = $documents_result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($doc['tenant_name']) ?></td>
            <td><a href="<?= htmlspecialchars($doc['filename']) ?>" target="_blank"><?= basename($doc['filename']) ?></a></td>
            <td><?= htmlspecialchars($doc['upload_date']) ?></td>
            <td><?= htmlspecialchars($doc['doc_type']) ?></td>
          </tr>
          <?php endwhile; ?>
          
        </tbody>
      </table>
    </main>
  </div>
</div>
</body>
</html>