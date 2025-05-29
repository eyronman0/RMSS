<?php
session_start();
include "conn_db.php";

$error = "";
$register_error = "";
$register_success = "";

// Login logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password']) && !isset($_POST['email'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Try to find tenant by email or first name
    $stmt = $conn->prepare("SELECT * FROM tenants WHERE email = ? OR first_name = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password == $row['password']) {
            $_SESSION['tenant_id'] = $row['id'];
            $_SESSION['tenant_name'] = $row['first_name']; // Save name for dashboard
            header("Location: main.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that username or email.";
    }
}

// Registration logic
if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['email'])
    && isset($_POST['confirm_password'])
    && isset($_POST['unit_id'])
) {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'];
    $unit_id = $_POST['unit_id'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $register_error = "Passwords do not match.";
    } elseif (empty($unit_id)) {
        $register_error = "Please select a unit.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("INSERT INTO tenants (first_name, email, password, unit_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $first_name, $email, $password, $unit_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $register_error = "Email already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO tenants (first_name, last_name, email, password, unit_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $first_name, $last_name, $email, $password, $unit_id);
            if ($stmt->execute()) {
                $register_success = "Registration successful! You can now log in.";
            } else {
                $register_error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tenant Login</title>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea,#0b1e7f);
      margin: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      color: #333;
    }
    .container {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 420px;
      padding: 40px 30px;
      text-align: center;
    }
    h1 {
      margin-bottom: 30px;
      color:rgb(6, 114, 191);
      font-weight: 700;
      font-size: 2rem;
    }
    .tab-switch {
      display: flex;
      justify-content: center;
      margin-bottom: 30px;
      gap: 20px;
    }
    .tab-switch label {
      cursor: pointer;
      padding: 10px 30px;
      border-radius: 30px;
      background: #ddd;
      font-weight: 600;
      color: #555;
      transition: background-color 0.3s ease, color 0.3s ease;
      user-select: none;
    }
    input[type="radio"] { display: none; }
    input#login-tab:checked + label[for="login-tab"],
    input#register-tab:checked + label[for="register-tab"] {
      background:rgb(25, 62, 230);
      color: white;
      box-shadow: 0 4px 10pxrgba(8, 15, 222, 0.96);
    }
    form {
      text-align: left;
      display: none;
      animation: fadeIn 0.4s ease forwards;
    }
    input[type="radio"]#login-tab:checked ~ form#login-form,
    input[type="radio"]#register-tab:checked ~ form#register-form {
      display: block;
    }
    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }
    label.form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #555;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border: 2px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select:focus {
      border-color:rgb(75, 114, 162);
      outline: none;
      box-shadow: 0 0 8pxrgba(76, 65, 234, 0.67);
    }
    input[type="submit"] {
      width: 100%;
      background-color:rgb(75, 95, 162);
      color: white;
      border: none;
      padding: 14px;
      font-size: 1.1rem;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 700;
      transition: background-color 0.3s ease;
    }
    input[type="submit"]:hover {
      background-color:rgb(5, 2, 186);
    }
    .form-footer {
      margin-top: 20px;
      font-size: 0.9rem;
      color: #666;
      text-align: center;
    }
    .form-footer a {
      color:rgb(5, 2, 186);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    .form-footer a:hover {
      color: rgb(5, 2, 186);
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Tenant Portal</h1>

    <!-- Radio buttons for toggle -->
    <input type="radio" name="tab" id="login-tab" checked />
    <label for="login-tab">Login</label>
    <input type="radio" name="tab" id="register-tab" />
    <label for="register-tab">Register</label>

    <!-- Login Form -->
    <form id="login-form" action="login.php" method="post" autocomplete="off">
      <label class="form-label" for="login-username">Username</label>
      <input type="text" id="login-username" name="username" required autofocus />

      <label class="form-label" for="login-password">Password</label>
      <input type="password" id="login-password" name="password" required />

      <input type="submit" value="Login" />

      <div class="form-footer">
        <p>Don't have an account? <label for="register-tab" style="cursor:pointer; color:#764ba2;">Register here</label></p>
        <p><a href="reset_password.html">Forgot your password?</a></p>
        <p>Or log in as a tenant</p>
        <p><a href="ownerlogin.php">Owner Login</a></p>
      </div>
      <?php if (!empty($error)): ?>
        <div style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
    </form>

    <!-- Register Form -->
    <form id="register-form" action="login.php" method="post" autocomplete="off">
      <label class="form-label" for="reg-firstname">First Name</label>
      <input type="text" id="reg-firstname" name="first_name" required />

      <label class="form-label" for="reg-lastname">Last Name</label>
      <input type="text" id="reg-lastname" name="last_name" required />

      <label class="form-label" for="reg-email">Email Address</label>
      <input type="email" id="reg-email" name="email" required />

      <?php
        $units_result = $conn->query("SELECT id, unit_number FROM units WHERE id NOT IN (SELECT unit_id FROM tenants)");
      ?>
      <label class="form-label" for="reg-unit">Unit</label>
      <select id="reg-unit" name="unit_id" required>
          <option value="">Select Unit</option>
          <?php while($unit = $units_result->fetch_assoc()): ?>
              <option value="<?= $unit['id'] ?>"><?= htmlspecialchars($unit['unit_number']) ?></option>
          <?php endwhile; ?>
      </select>

      <label class="form-label" for="reg-password">Password</label>
      <input type="password" id="reg-password" name="password" required />

      <label class="form-label" for="reg-confirm-password">Confirm Password</label>
      <input type="password" id="reg-confirm-password" name="confirm_password" required />

      <input type="submit" value="Register" />

      <div class="form-footer">
        <p>Already have an account? <label for="login-tab" style="cursor:pointer; color:#764ba2;">Login here</label></p>
      </div>
      <?php if (!empty($register_error)): ?>
        <div style="color: red; text-align: center;"><?= htmlspecialchars($register_error) ?></div>
      <?php endif; ?>
      <?php if (!empty($register_success)): ?>
        <div style="color: green; text-align: center;"><?= htmlspecialchars($register_success) ?></div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>