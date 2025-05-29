<?php
session_start();
if (!isset($_SESSION['owner_id'])) {
    header("Location: ownerlogin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                            <a class="nav-link active" href="#">Dashboard</a>
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
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-4">
                <h1 class="dashboard-header">Owner Dashboard</h1>
                <div class="row">
                    <!-- Tenant Payments -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2 class="card-title mb-3">Tenant Payments</h2>
                                <form class="row g-2">
                                    <div class="col-8">
                                        <input type="text" id="tenantName" class="form-control" placeholder="Enter tenant name">
                                    </div>
                                    <div class="col-4">
                                        <button type="submit" id="submit" class="btn btn-primary w-100">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Maintenance Requests -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2 class="card-title mb-3">Maintenance Requests</h2>
                                <ul class="list-group">
                                    <li class="list-group-item">Request 1: Leaky faucet</li>
                                    <li class="list-group-item">Request 2: Broken window</li>
                                    <li class="list-group-item">Request 3: Heating issue</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tenant Information -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2 class="card-title mb-3">Tenant Information</h2>
                                <form id="tenantForm" class="mb-4">
                                    <div class="input-group">
                                        <input type="text" id="tenantInfo" class="form-control" placeholder="Enter tenant name" required>
                                        <button type="submit" class="btn btn-success">Add Tenant</button>
                                    </div>
                                </form>
                                <ul class="list-group" id="tenantList">
                                    <!-- Tenant items will be dynamically added here -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const tenantForm = document.getElementById('tenantForm');
        const tenantList = document.getElementById('tenantList');

        tenantForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const tenantName = document.getElementById('tenantInfo').value;

            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            listItem.textContent = tenantName;

            const editButton = document.createElement('button');
            editButton.className = 'btn btn-warning btn-sm ms-2';
            editButton.textContent = 'Edit';
            editButton.onclick = function() {
                const newName = prompt('Edit tenant name:', tenantName);
                if (newName) {
                    listItem.firstChild.textContent = newName;
                }
            };

            const deleteButton = document.createElement('button');
            deleteButton.className = 'btn btn-danger btn-sm';
            deleteButton.textContent = 'Delete';
            deleteButton.onclick = function() {
                tenantList.removeChild(listItem);
            };

            listItem.appendChild(editButton);
            listItem.appendChild(deleteButton);

            tenantList.appendChild(listItem);

            tenantForm.reset();
        });
    </script>
</body>
</html>
