<?php
session_start();
include "db.php";

// Fetch services
$servicesQuery = "SELECT service_id, service_name, price, duration FROM services";
$servicesResult = $conn->query($servicesQuery);

// Fetch therapists with availability
$therapistsQuery = "
    SELECT users.user_id, users.full_name, availability.start_time, availability.end_time 
    FROM users 
    INNER JOIN availability 
    ON users.user_id = availability.therapist_id 
    WHERE users.role = 'therapist'";
$therapistsResult = $conn->query($therapistsQuery);

// Fetch availability for time slots
$availabilityQuery = "SELECT therapist_id, start_time, end_time FROM availability";
$availabilityResult = $conn->query($availabilityQuery);
$availabilityData = [];
while ($row = $availabilityResult->fetch_assoc()) {
    $availabilityData[$row['therapist_id']] = [
        'start_time' => $row['start_time'],
        'end_time' => $row['end_time'],
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate session user ID
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('You need to be logged in to book an appointment.'); window.location.href = 'login.php';</script>";
        exit();
    }

    // Capture form inputs
    $userId = $_SESSION['user_id']; // Assuming user login session stores `user_id`
    $serviceId = $_POST['service_id'];
    $therapistId = $_POST['therapist_id'];
    $appointmentDate = $_POST['appointment_date'];
    $startTime = $_POST['start_time'];
    $promoCode = $_POST['promo_code'] ?? null; // Optional promo code
    $paymentMethod = $_POST['payment_method'];

    // Fetch service duration and price
    $serviceQuery = "SELECT duration, price FROM services WHERE service_id = ?";
    $stmt = $conn->prepare($serviceQuery);
    $stmt->bind_param("i", $serviceId);
    $stmt->execute();
    $serviceResult = $stmt->get_result()->fetch_assoc();
    $duration = $serviceResult['duration'];
    $price = $serviceResult['price'];

    // Calculate end time
    $endTime = date("H:i:s", strtotime($startTime) + ($duration * 60));

    // Determine appointment status
    $status = (strtotime("$appointmentDate $startTime") > time()) ? "pending" : "completed";

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into appointments table
        $appointmentQuery = "
            INSERT INTO appointments (user_id, therapist_id, service_id, appointment_date, start_time, end_time, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $appointmentStmt = $conn->prepare($appointmentQuery);
        $appointmentStmt->bind_param("iiissss", $userId, $therapistId, $serviceId, $appointmentDate, $startTime, $endTime, $status);

        if (!$appointmentStmt->execute()) {
            throw new Exception("Error inserting into appointments: " . $appointmentStmt->error);
        }

        $appointmentId = $appointmentStmt->insert_id; // Get last inserted ID

        // Insert into payments table
        $paymentStatus = ($paymentMethod === "cash") ? "unpaid" : "paid";
        $transactionId = uniqid("TXN");

        $paymentQuery = "
            INSERT INTO payments (appointment_id, amount, payment_method, payment_status, transaction_id) 
            VALUES (?, ?, ?, ?, ?)";
        $paymentStmt = $conn->prepare($paymentQuery);
        $paymentStmt->bind_param("idsss", $appointmentId, $price, $paymentMethod, $paymentStatus, $transactionId);

        if (!$paymentStmt->execute()) {
            throw new Exception("Error inserting into payments: " . $paymentStmt->error);
        }

        // Commit transaction
        $conn->commit();

        // Redirect to userdashboard.php with a success message
        $_SESSION['message'] = "Appointment successfully scheduled!";
        header("Location: userdashboard.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction and show error
        $conn->rollback();
        echo "<script>alert('An error occurred: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        const availabilityData = <?= json_encode($availabilityData) ?>;
    </script>
    <script src="scripts.js" defer></script>
</head>
<body>
<div class="booking-container">
    <h1>Book Your Appointment</h1>
    <div class="step-indicator">
        <span class="step active">1. Select Service & Therapist</span>
        <span class="step">2. Choose Date & Time</span>
        <span class="step">3. Confirm and Pay</span>
    </div>

    <form id="booking-form" method="POST">
        <!-- Step 1 -->
        <div class="form-step active">
            <h2>Select a Service</h2>
            <select id="service-select" name="service_id" class="form-input" required>
                <option value="">Select a Service</option>
                <?php while ($service = $servicesResult->fetch_assoc()): ?>
                    <option value="<?= $service['service_id'] ?>" data-price="<?= $service['price'] ?>" data-duration="<?= $service['duration'] ?>">
                        <?= $service['service_name'] ?> - $<?= $service['price'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <h2>Choose a Therapist</h2>
            <select id="therapist-select" name="therapist_id" class="form-input" required>
                <option value="">Select a Therapist</option>
                <?php while ($therapist = $therapistsResult->fetch_assoc()): ?>
                    <option value="<?= $therapist['user_id'] ?>">
                        <?= $therapist['full_name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="button" class="next-btn">Next</button>
        </div>

        <!-- Step 2 -->
        <div class="form-step">
            <h2>Choose Date</h2>
            <input type="date" id="date-picker" name="appointment_date" class="form-input" required>
            <h2>Available Time Slots</h2>
            <div class="time-slots"></div>
            <input type="hidden" id="start-time" name="start_time" required>
            <button type="button" class="prev-btn">Previous</button>
            <button type="button" class="next-btn">Next</button>
        </div>

        <!-- Step 3 -->
        <div class="form-step">
            <h2>Confirm Your Booking</h2>
            <div class="summary">
                <p><strong>Service:</strong> <span id="summary-service"></span></p>
                <p><strong>Therapist:</strong> <span id="summary-therapist"></span></p>
                <p><strong>Date:</strong> <span id="summary-date"></span></p>
                <p><strong>Time:</strong> <span id="summary-time"></span></p>
                <p><strong>Total Price:</strong> $<span id="summary-price"></span></p>
            </div>
            <h2>Promo Code</h2>
            <input type="text" name="promo_code" class="form-input" placeholder="Enter Promo Code (Optional)">
            <h2>Payment Options</h2>
            <select id="payment-method" class="form-input" name="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="cash">Cash</option>
                <option value="credit-card">Credit Card</option>
                <option value="paypal">PayPal</option>
            </select>
            <button type="button" class="prev-btn">Previous</button>
            <button type="submit" class="confirm-btn">Confirm Appointment</button>
        </div>
    </form>
</div>
</body>
</html>
