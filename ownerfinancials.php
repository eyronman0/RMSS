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
if (isset($_POST['add_payment'])) {
    $tenant_id = $_POST['tenant_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];

    // Insert payment into database
    $stmt = $conn->prepare("INSERT INTO payments (tenant_id, amount, payment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $tenant_id, $amount, $payment_date);
    if ($stmt->execute()) {
        // Fetch tenant and unit info for receipt
        $receipt_stmt = $conn->prepare("SELECT CONCAT(t.first_name, ' ', t.last_name) AS name, u.unit_number FROM tenants t JOIN units u ON t.unit_id = u.id WHERE t.id = ?");
        $receipt_stmt->bind_param("i", $tenant_id);
        $receipt_stmt->execute();
        $receipt_result = $receipt_stmt->get_result();
        $receipt = $receipt_result->fetch_assoc();
        $receipt['amount'] = $amount;
        $receipt['payment_date'] = $payment_date;
    } else {
        $receipt = false;
    }
}
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
      <?php
        // Fetch tenants for dropdown
        $tenants = $conn->query("SELECT t.id, CONCAT(t.first_name, ' ', t.last_name) AS name, u.unit_number FROM tenants t JOIN units u ON t.unit_id = u.id");
        ?>
        <div class="card mb-4 p-3">
          <h3>Add Payment</h3>
          <form method="post" action="" class="row g-3">
            <div class="col-md-4">
              <label for="tenant_id" class="form-label">Tenant</label>
              <select name="tenant_id" id="tenant_id" class="form-select" required>
                <option value="">Select Tenant</option>
                <?php while($tenant = $tenants->fetch_assoc()): ?>
                  <option value="<?= $tenant['id'] ?>">
                    <?= htmlspecialchars($tenant['name']) ?> (Unit <?= htmlspecialchars($tenant['unit_number']) ?>)
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="amount" class="form-label">Amount</label>
              <input type="number" step="0.01" min="0" name="amount" id="amount" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label for="payment_date" class="form-label">Payment Date</label>
              <input type="date" name="payment_date" id="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" name="add_payment" class="btn btn-primary w-100">Save & Print Receipt</button>
            </div>
          </form>
        </div>
        <?php if (isset($receipt) && $receipt): ?>
        <div id="receipt" class="alert alert-success mt-3">
          <h4>Payment Receipt</h4>
          <p><strong>Tenant:</strong> <?= htmlspecialchars($receipt['name']) ?></p>
          <p><strong>Unit:</strong> <?= htmlspecialchars($receipt['unit_number']) ?></p>
          <p><strong>Amount:</strong> $<?= number_format($receipt['amount'], 2) ?></p>
          <p><strong>Date:</strong> <?= htmlspecialchars($receipt['payment_date']) ?></p>
          <button onclick="window.print()" class="btn btn-secondary mt-2">Print Receipt</button>
        </div>
        <?php elseif (isset($receipt) && $receipt === false): ?>
        <div class="alert alert-danger mt-3">Failed to save payment.</div>
        <?php endif; ?>
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
      <?php
      $total_income = 0;
      $result2 = $conn->query($sql);
      while($row = $result2->fetch_assoc()) {
          $total_income += $row['amount'];
      }
      ?>
      <div class="alert alert-info mb-4">
        <strong>Total Income:</strong> $<?= number_format($total_income, 2) ?>
      </div>
      
    </main>
  </div>
</div>
</body>
</html>
