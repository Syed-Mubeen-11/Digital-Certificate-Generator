<?php
include "../admin_pages/config.php";
$message = "";

/* ================= FETCH EVENTS ================= */
$events = [];
$res = $conn->query("SELECT id, name FROM events ORDER BY name");
while ($row = $res->fetch_assoc()) {
    $events[] = $row;
}

/* ================= REGISTER ================= */
if (isset($_POST['register'])) {
    $event_id = $_POST['event_id'];
    $name     = trim($_POST['name']);
    $branch   = trim($_POST['branch']);
    $rollNo   = trim($_POST['rollNo']);

    if (empty($name) || empty($branch) || empty($rollNo) || empty($event_id)) {
        $message = "⚠️ Please fill all fields!";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO participants (event_id, name, branch, rollNo) VALUES (?, ?, ?, ?)"
        );

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("isss", $event_id, $name, $branch, $rollNo);

        if ($stmt->execute()) {
            $message = "🎉 Registration successful!";
        } else {
            $message = "⚠️ Roll Number already exists!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Event Registration</title>
<link rel="stylesheet" href="register.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="page-container">
    <div class="form-card">
        <h2>Register for Event</h2>

        <?php if($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="branch" placeholder="Branch" required>
            <input type="text" name="rollNo" placeholder="Roll Number" required>
            <select name="event_id" required>
                <option value="">-- Select Event --</option>
                <?php foreach($events as $event): ?>
                    <option value="<?php echo $event['id']; ?>">
                        <?php echo $event['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="register">Register</button>
        </form>

        <a class="back-link" href="../index_pages/index.php">← Back to Home</a>
    </div>
</div>

</body>
</html>
