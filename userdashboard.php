<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your existing CSS -->
</head>
<body>
    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <h1>Welcome to Your Dashboard</h1>

        <!-- Sidebar or Navigation (Optional) -->
        <nav class="dashboard-nav">
            <ul>
                <li><a href="#upcoming-appointments">Upcoming Appointments</a></li>
                <li><a href="#past-appointments">Past Appointments</a></li>
                <li><a href="#account-settings">Account Settings</a></li>
                <li><a href="#promotions-rewards">Promotions & Rewards</a></li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <div class="dashboard-main-content">
            
            <!-- Upcoming Appointments Section -->
            <section id="upcoming-appointments">
                <h2>Upcoming Appointments</h2>
                <div class="appointment-list">
                    <!-- Sample Appointment Card -->
                    <div class="appointment-card">
                        <p><strong>Massage Therapy - $50</strong></p>
                        <p>Scheduled for: <span class="appointment-date">Dec 15, 2024</span> at <span class="appointment-time">10:00 AM</span></p>
                        <button class="cancel-btn">Cancel</button>
                        <button class="reschedule-btn">Reschedule</button>
                    </div>
                    <!-- Repeat the above card structure for other upcoming appointments -->
                </div>
            </section>

            <!-- Past Appointments Section -->
            <section id="past-appointments">
                <h2>Past Appointments</h2>
                <div class="appointment-list">
                    <!-- Sample Past Appointment Card -->
                    <div class="appointment-card">
                        <p><strong>Facial Treatment - $40</strong></p>
                        <p>Date: <span class="appointment-date">Nov 20, 2024</span> at <span class="appointment-time">3:00 PM</span></p>
                        <button class="review-btn">Leave a Review</button>
                    </div>
                    <!-- Repeat for other past appointments -->
                </div>
            </section>

            <!-- Account Settings Section -->
            <section id="account-settings">
                <h2>Account Settings</h2>
                <div class="settings-options">
                    <!-- Profile Settings -->
                    <div class="profile-settings">
                        <h3>Profile</h3>
                        <form>
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name">
                            
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email">
                            
                            <label for="phone">Phone Number:</label>
                            <input type="text" id="phone" name="phone" placeholder="Enter your phone number">
                            
                            <button type="submit">Save Changes</button>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="change-password">
                        <h3>Change Password</h3>
                        <form>
                            <label for="current-password">Current Password:</label>
                            <input type="password" id="current-password" name="current-password" placeholder="Enter current password">
                            
                            <label for="new-password">New Password:</label>
                            <input type="password" id="new-password" name="new-password" placeholder="Enter new password">
                            
                            <button type="submit">Update Password</button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Promotions and Rewards Section -->
            <section id="promotions-rewards">
                <h2>Promotions & Rewards</h2>
                <div class="promotions-list">
                    <div class="promotion-card">
                        <p><strong>10% off on your next appointment!</strong></p>
                        <p>Expires: <span class="promotion-expiry">Dec 31, 2024</span></p>
                    </div>
                    <!-- Repeat for other promotions or rewards -->
                </div>
            </section>

        </div>
    </div>
</body>
</html>
