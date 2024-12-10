<?php
include "db.php";

// Handle booking approval
if (isset($_POST['approve'])) {
    $appointment_id = $_POST['appointment_id'];
    $update_query = "UPDATE appointments SET status = 'Confirmed' WHERE appointment_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
}

// Fetch bookings data
$appointments_query = "
    SELECT 
        a.appointment_id,
        a.user_id,
        a.service_id,
        a.status,
        a.appointment_date,
        a.user_id,
        u.full_name,
        s.service_name
    FROM appointments a
    JOIN users u ON a.user_id = u.user_id
    JOIN services s ON a.service_id = s.service_id
";
$result = $conn->query($appointments_query);

// Handle adding a new service
if (isset($_POST['add_service'])) {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    $add_service_query = "INSERT INTO services (service_name, description, price, duration) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($add_service_query);
    $stmt->bind_param("ssdi", $service_name, $description, $price, $duration);
    $stmt->execute();
}
// Fetch all services data for the edit form
$services_query = "SELECT * FROM services";
$services_result = $conn->query($services_query);

// Handle updating a service
if (isset($_POST['update_service'])) {
    $service_id = $_POST['service_id'];
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];

    $update_service_query = "
        UPDATE services 
        SET service_name = ?, description = ?, price = ?, duration = ? 
        WHERE service_id = ?
    ";
    $stmt = $conn->prepare($update_service_query);
    $stmt->bind_param("ssdii", $service_name, $description, $price, $duration, $service_id);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f6f9;
    }

    header {
        background-color: #4CAF50;
        color: white;
        padding: 20px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
    }

    main {
        padding: 20px;
        margin-top: 20px;
    }

    section {
        margin-bottom: 40px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    h2 {
        font-size: 20px;
        margin-bottom: 15px;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #f7f7f7;
        color: #333;
    }

    table td {
        background-color: #fff;
        color: #555;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 14px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #45a049;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-width: 500px;
        margin: 0 auto;
    }

    form input, form textarea {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        width: 100%;
        box-sizing: border-box;
    }

    form input[type="number"] {
        max-width: 250px;
    }

    form textarea {
        resize: vertical;
        height: 100px;
    }

    form button {
        width: 100%;
        max-width: 200px;
        margin-top: 10px;
        padding: 12px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .actions button {
        padding: 8px 12px;
        font-size: 14px;
    }

    .actions button.approve {
        background-color: #4CAF50;
    }

    .actions button.approve:hover {
        background-color: #45a049;
    }

    .actions button.reject {
        background-color: #f44336;
    }

    .actions button.reject:hover {
        background-color: #e53935;
    }

    .table-actions {
        display: flex;
        justify-content: space-between;
    }

    .table-actions button {
        padding: 8px 12px;
        font-size: 14px;
    }

    /* Additional Styling for Improved Readability */
    input:focus, textarea:focus {
        border-color: #4CAF50;
        outline: none;
    }

    input, textarea {
        background-color: #f9f9f9;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        table, form {
            width: 100%;
            margin: 0;
        }

        .actions button {
            width: 100%;
        }
    }

</style>

</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <main>
        <section>
            <h2>Manage Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer Name</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['appointment_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['appointment_date'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
                                        <button type="submit" name="approve">Approve</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No bookings available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <section>
            <h2>Manage Services</h2>
            <form method="post">
                <label for="service_name">Service Name:</label>
                <input type="text" id="service_name" name="service_name" required>
                
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
                
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
                
                <label for="duration">Duration (minutes):</label>
                <input type="number" id="duration" name="duration" required>
                
                <button type="submit" name="add_service">Add Service</button>
            </form>
        </section>
        <section>
    <h2>Edit Available Services</h2>
    <?php if ($services_result && $services_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $services_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['duration']); ?> minutes</td>
                        <td>
                            <!-- Edit Service Form -->
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="service_id" value="<?php echo $row['service_id']; ?>">
                                <button type="button" onclick="openEditForm(<?php echo $row['service_id']; ?>, '<?php echo addslashes($row['service_name']); ?>', '<?php echo addslashes($row['description']); ?>', <?php echo $row['price']; ?>, <?php echo $row['duration']; ?>)">Edit</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No services available to edit.</p>
    <?php endif; ?>

    <!-- Edit Service Modal Form -->
    <div id="editServiceModal" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <h3>Edit Service</h3>
        <form method="post">
            <input type="hidden" name="service_id" id="edit_service_id">
            <label for="edit_service_name">Service Name:</label>
            <input type="text" id="edit_service_name" name="service_name" required>
            
            <label for="edit_description">Description:</label>
            <textarea id="edit_description" name="description" required></textarea>
            
            <label for="edit_price">Price:</label>
            <input type="number" id="edit_price" name="price" step="0.01" required>
            
            <label for="edit_duration">Duration (minutes):</label>
            <input type="number" id="edit_duration" name="duration" required>
            
            <button type="submit" name="update_service">Update Service</button>
        </form>
        <button onclick="closeEditForm()">Cancel</button>
    </div>
</section>

<!-- JavaScript to handle the modal for editing -->
<script>
    function openEditForm(serviceId, serviceName, description, price, duration) {
        document.getElementById('edit_service_id').value = serviceId;
        document.getElementById('edit_service_name').value = serviceName;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_duration').value = duration;

        document.getElementById('editServiceModal').style.display = 'block';
    }

    function closeEditForm() {
        document.getElementById('editServiceModal').style.display = 'none';
    }
</script>
    </main>
</body>
</html>