<?php
require_once "config.php";
$message = "";

if (isset($_POST['signup'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if ($password !== $confirm) {
        $message = "Passwords do not match";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO admins (username, password) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $username, $hash);

        $message = $stmt->execute()
            ? "Admin account created successfully"
            : "Username already exists";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Signup</title>
<link rel="stylesheet" href="admin_p.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="page-container">
  <div class="auth-card">
    <h2>Admin Signup</h2>

    <p class="success"><?= $message ?></p>

    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm" placeholder="Confirm Password" required>
      <button name="signup">Create Admin</button>
    </form>

    <div class="auth-link">
      <a href="admin.php">Back to Login</a>
    </div>
  </div>
</div>

</body>
</html>

