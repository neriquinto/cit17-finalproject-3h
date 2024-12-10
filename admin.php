<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your current CSS -->
</head>
<body>
    <!-- Admin Dashboard Container -->
    <div class="admin-dashboard-container">
        <h1>Admin Dashboard</h1>

        <!-- Sidebar or Navigation (Optional) -->
        <nav class="admin-dashboard-nav">
            <ul>
                <li><a href="#manage-bookings">Manage Bookings</a></li>
                <li><a href="#manage-services">Manage Services</a></li>
                <li><a href="#therapist-schedule">Therapist Schedule</a></li>
                <li><a href="#payments-reports">Payments & Reports</a></li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <div class="admin-dashboard-main-content">

            <!-- Manage Bookings Section -->
            <section id="manage-bookings">
                <h2>Manage Bookings</h2>
                <div class="booking-filter">
                    <label for="booking-status">Filter by Status:</label>
                    <select id="booking-status" name="booking-status">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <table class="booking-list">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Booking Row -->
                        <tr>
                            <td>#12345</td>
                            <td>John Doe</td>
                            <td>Massage Therapy</td>
                            <td><span class="status pending">Pending</span></td>
                            <td>
                                <button class="approve-btn">Approve</button>
                                <button class="cancel-btn">Cancel</button>
                                <button class="reschedule-btn">Reschedule</button>
                            </td>
                        </tr>
                        <!-- Repeat rows for more bookings -->
                    </tbody>
                </table>
            </section>

            <!-- Manage Services Section -->
            <section id="manage-services">
                <h2>Manage Services</h2>
                <div class="service-list">
                    <!-- Sample Service Item -->
                    <div class="service-item">
                        <p><strong>Massage Therapy</strong></p>
                        <p>$50 - 60 mins</p>
                        <button class="edit-service-btn">Edit</button>
                        <button class="delete-service-btn">Delete</button>
                    </div>
                    <!-- Repeat for other services -->
                </div>

                <!-- Add New Service Form -->
                <h3>Add New Service</h3>
                <form id="add-service-form">
                    <label for="service-name">Service Name:</label>
                    <input type="text" id="service-name" name="service-name" required>

                    <label for="service-description">Description:</label>
                    <textarea id="service-description" name="service-description" required></textarea>

                    <label for="service-price">Price:</label>
                    <input type="number" id="service-price" name="service-price" required>

                    <label for="service-duration">Duration (minutes):</label>
                    <input type="number" id="service-duration" name="service-duration" required>

                    <button type="submit">Add Service</button>
                </form>
            </section>

            <!-- Therapist Schedule Section -->
            <section id="therapist-schedule">
                <h2>Therapist Schedule Management</h2>

                <!-- Availability Calendar -->
                <div class="availability-calendar">
                    <h3>Therapist Availability Calendar</h3>
                    <!-- Integrate calendar here -->
                </div>

                <!-- Add Therapist Availability Form -->
                <h3>Add Therapist Availability</h3>
                <form id="add-availability-form">
                    <label for="therapist-name">Therapist Name:</label>
                    <input type="text" id="therapist-name" name="therapist-name" required>

                    <label for="availability-date">Date:</label>
                    <input type="date" id="availability-date" name="availability-date" required>

                    <label for="availability-time">Time:</label>
                    <input type="time" id="availability-time" name="availability-time" required>

                    <button type="submit">Add Availability</button>
                </form>
            </section>

            <!-- Payments and Reports Section -->
            <section id="payments-reports">
                <h2>Payments & Reports</h2>

                <!-- Payments Table -->
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Payment Row -->
                        <tr>
                            <td>#98765</td>
                            <td>Jane Smith</td>
                            <td>$50</td>
                            <td><span class="status paid">Paid</span></td>
                            <td>Dec 1, 2024</td>
                        </tr>
                        <!-- Repeat rows for more payments -->
                    </tbody>
                </table>

                <!-- Reports Section (for charts and data visualizations) -->
                <div class="reports-section">
                    <h3>Booking Trends</h3>
                    <!-- Integrate charts/graphs here -->
                </div>
            </section>

        </div>
    </div>
</body>
</html>
