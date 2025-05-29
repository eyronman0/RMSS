<?php
session_start();
include "conn_db.php";

$error = "";

// LOGIN LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password']) && !isset($_POST['email'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check owner credentials (by name or email)
    $stmt = $conn->prepare("SELECT * FROM owners WHERE name = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password == $row['password']) {
            $_SESSION['owner_id'] = $row['id'];
            $_SESSION['owner_name'] = $row['name'];
            header("Location: ownerpage.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that username or email.";
    }
}

// REGISTRATION LOGIC (your existing code below)
if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['email'])
    && isset($_POST['confirm_password'])
    && isset($_POST['username'])
) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email or username already exists
        $stmt = $conn->prepare("SELECT id FROM owners WHERE email = ? OR name = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email or username already exists.";
        } else {
            // Insert new owner
            $stmt = $conn->prepare("INSERT INTO owners (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password);
            if ($stmt->execute()) {
                $error = "Registration successful! You can now log in.";
            } else {
                $error = "Registration failed. Please try again.";
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
  <title>Owner Login & Registration</title>
  <style>
    /* Reset and base */
    * {
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea,rgb(64, 101, 233));
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
      color: #4b3f72;
      font-weight: 700;
      font-size: 2rem;
    }

    /* Tabs container */
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
    input[type="radio"] {
      display: none;
    }
    input#login-tab:checked + label[for="login-tab"],
    input#register-tab:checked + label[for="register-tab"] {
      background: #764ba2;
      color: white;
      box-shadow: 0 4px 10px #764ba2aa;
    }

    /* Forms */
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
    input[type="password"] {
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
    input[type="password"]:focus {
      border-color: #764ba2;
      outline: none;
      box-shadow: 0 0 8px #764ba2aa;
    }
    input[type="submit"] {
      width: 100%;
      background-color: #764ba2;
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
      background-color: #5b367a;
    }

    /* Links under forms */
    .form-footer {
      margin-top: 20px;
      font-size: 0.9rem;
      color: #666;
      text-align: center;
    }
    .form-footer a {
      color: #764ba2;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    .form-footer a:hover {
      color: #5b367a;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Owner Portal</h1>

    <!-- Radio buttons for toggle -->
    <input type="radio" name="tab" id="login-tab" checked />
    <label for="login-tab">Login</label>

    <input type="radio" name="tab" id="register-tab" />
    <label for="register-tab">Register</label>

    <!-- Login Form -->
    <form id="login-form" action="ownerlogin.php" method="post" autocomplete="off">
      <label class="form-label" for="login-username">Username</label>
      <input type="text" id="login-username" name="username" required autofocus />

      <label class="form-label" for="login-password">Password</label>
      <input type="password" id="login-password" name="password" required />

      <input type="submit" value="Login" />

      <div class="form-footer">
        <p>Don't have an account? <label for="register-tab" style="cursor:pointer; color:#764ba2;">Register here</label></p>
        <p><a href="reset_password.html">Forgot your password?</a></p>
        <p>Or log in as a tenant</p>
        <p><a href="login.php">Tenant Login</a></p>
      </div>
    </form>

    <!-- Register Form -->
    <form id="register-form" action="ownerlogin.php" method="post" autocomplete="off">
      <label class="form-label" for="reg-username">Username</label>
      <input type="text" id="reg-username" name="username" required />

      <label class="form-label" for="reg-email">Email Address</label>
      <input type="email" id="reg-email" name="email" required />

      <label class="form-label" for="reg-password">Password</label>
      <input type="password" id="reg-password" name="password" required />

      <label class="form-label" for="reg-confirm-password">Confirm Password</label>
      <input type="password" id="reg-confirm-password" name="confirm_password" required />

      <input type="submit" value="Register" />

      <div class="form-footer">
        <p>Already have an account? <label for="login-tab" style="cursor:pointer; color:#764ba2;">Login here</label></p>
      </div>
    </form>
    <?php if (!empty($error)): ?>
        <div style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
