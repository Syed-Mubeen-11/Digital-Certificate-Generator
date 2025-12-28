<?php
include "../admin_pages/config.php";

$matchedEvents = [];

if(isset($_POST['search'])){
    $rollNo = trim($_POST['rollNo']);

    $stmt = $conn->prepare(
        "SELECT p.*, e.name AS event_name, e.date AS event_date, e.controller, e.template
         FROM participants p
         JOIN events e ON p.event_id = e.id
         WHERE p.rollNo = ?"
    );
    $stmt->bind_param("s", $rollNo);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = $res->fetch_all(MYSQLI_ASSOC);

    // Convert template to base64 for JS
    foreach($rows as &$row){
        $row['template'] = base64_encode($row['template']);
    }
    $matchedEvents = $rows;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Get Certificate</title>
<link rel="stylesheet" href="certificate.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="page-container">
    <div class="form-card">
        <h2>🎓 Get Your Certificate</h2>

        <form method="POST" id="searchForm">
            <input type="text" name="rollNo" placeholder="Enter your Roll Number" required>
            <button type="submit" name="search">Search</button>
        </form>

        <?php if(!empty($matchedEvents)): ?>
            <p class="message">Select event to generate certificate:</p>
            <select id="eventSelector">
                <?php foreach($matchedEvents as $index => $e): ?>
                    <option value="<?php echo $index; ?>">
                        <?php echo $e['event_name'] . " (" . $e['event_date'] . ")"; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button id="generateBtn" type="button">Generate Certificate</button>
            <button id="downloadBtn" type="button" style="display:none;">Download Certificate</button>
        <?php elseif(isset($_POST['search'])): ?>
            <p class="message" style="color:red;">No participant found!</p>
        <?php endif; ?>

        <div id="certificate-container">
            <canvas id="certificateCanvas" width="1000" height="600"></canvas>
        </div>

        <a class="back-link" href="../index_pages/index.php">← Back to Home</a>
    </div>
</div>

<script>
const matchedEvents = <?php echo json_encode($matchedEvents); ?>;
</script>
<script src="certificate.js"></script>

</body>
</html>
