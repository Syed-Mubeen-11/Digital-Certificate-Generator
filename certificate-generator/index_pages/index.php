<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Digital Certificate Generator</title>

<link rel="stylesheet" href="index.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
<div class="container">

<h1 class="main-title">
Welcome to Digital Certificate Generator 🎓📜
</h1>

<div class="cards">

<!-- Admin -->
<a href="../admin_pages/admin.php" class="card admin-card">
<h2>👨‍💼 Admin</h2>
<p>Access the admin panel to manage templates and issued certificates.</p>
</a>

<!-- Register -->
<a href="../register_pages/register.php" class="card register-card">
<h2>🙋‍♂️ Register</h2>
<p>Register your details to receive a digital certificate.</p>
</a>


<!-- Certificate -->
<a href="../get_certificate/certificate.php" class="card certificate-card">
<h2>📄 Get Certificate</h2>
<p>Download your digital certificate using your Registered Number.</p>
</a>

</div>
</div>

<footer>
<p>&copy; 2025 Digital Certificate Generator. All rights reserved.</p>
</footer>

</body>
</html>
