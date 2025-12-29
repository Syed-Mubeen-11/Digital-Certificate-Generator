<?php
session_start();
require_once "config.php";

$error = "";
$success = "";

/* ================= LOGIN ================= */
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['username'];
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}

/* ================= LOGOUT ================= */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

/* ================= ADD EVENT ================= */
if (isset($_POST['add_event']) && isset($_SESSION['admin'])) {
    $name = $_POST['event_name'];
    $date = $_POST['event_date'];
    $controller = $_POST['controller'];
    $template = file_get_contents($_FILES['template']['tmp_name']);

    $stmt = $conn->prepare(
        "INSERT INTO events (name, date, controller, template) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $name, $date, $controller, $template);
    $stmt->execute();

    $success = "Event added successfully";
}

/* ================= DELETE EVENT ================= */
if (isset($_POST['delete_event']) && isset($_SESSION['admin'])) {
    $id = $_POST['event_id'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $success = "Event deleted successfully";
}

/* ================= FETCH EVENTS ================= */
$events = [];
if (isset($_SESSION['admin'])) {
    $res = $conn->query("SELECT * FROM events ORDER BY id DESC");
    while ($row = $res->fetch_assoc()) {
        $events[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<link rel="stylesheet" href="admin_p.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php if (!isset($_SESSION['admin'])): ?>

<!-- LOGIN BOX -->
<div class="page-container">
  <div class="auth-card">
    <h2>Admin Login</h2>
    <p class="error"><?= $error ?></p>

    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button name="login">Login</button>
    </form>

    <div class="auth-link">
      New admin? <a href="admin_signup.php">Create account</a>
    </div>
  </div>
</div>

<?php else: ?>

<!-- DASHBOARD -->
<div class="container">

<div class="dashboard-header">
  <h2>Welcome, <?= $_SESSION['admin']; ?> 👋</h2>
  <a class="logout" href="?logout=1">Logout</a>
</div>

<p class="success"><?= $success ?></p>

<!-- ADD EVENT -->
<div class="card card-add">
<h3>Add Event</h3>
<form method="POST" enctype="multipart/form-data">
<input type="text" name="event_name" placeholder="Event Name" required>
<input type="date" name="event_date" required>
<input type="text" name="controller" placeholder="Controller Name" required>
<input type="file" name="template" required>
<button name="add_event">Add Event</button>
</form>
</div>

<!-- REMOVE EVENT -->
<div class="card card-remove">
<h3>Remove Event</h3>
<form method="POST">
<select name="event_id" required>
<option value="">Select Event</option>
<?php foreach ($events as $e): ?>
<option value="<?= $e['id']; ?>"><?= $e['name']; ?></option>
<?php endforeach; ?>
</select>
<button name="delete_event">Delete</button>
</form>
</div>

<!-- VIEW EVENTS -->
<div class="card card-view">
<h3>Past Events</h3>

<?php foreach ($events as $e): ?>
    <div class="event-box">
        <b><?= $e['name']; ?></b>
        <p>Date: <?= $e['date']; ?></p>
        <p>Controller: <?= $e['controller']; ?></p>

        <?php
        $stmt = $conn->prepare(
            "SELECT name, branch, rollNo FROM participants WHERE event_id = ?"
        );
        $stmt->bind_param("i", $e['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        $participants = $res->fetch_all(MYSQLI_ASSOC);
        $count = count($participants);
        ?>

        <p>Participants: <?= $count; ?></p>

        <?php if ($count > 0): ?>
            <details>
                <summary>View Participants</summary>
                <ul>
                    <?php foreach ($participants as $p): ?>
                        <li>
                            <?= $p['name']; ?> (Roll No: <?= $p['rollNo']; ?>, Branch: <?= $p['branch']; ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </details>
        <?php else: ?>
            <p>No participants yet.</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</div>

</div>

<?php endif; ?>

</body>
</html>
