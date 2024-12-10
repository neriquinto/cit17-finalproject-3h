<!-- PHP -->
<?php
include "db.php";
session_start();

// Fetch popular services from the database
$services_query = "SELECT * FROM services LIMIT 3";
$services_result = $conn->query($services_query);

// Fetch testimonials from the database
$reviews_query = "SELECT * FROM reviews LIMIT 5";
$reviews_result = $conn->query($reviews_query);
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Spa Appointment Booking</title>
</head>
<body>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Your Wellness Journey Starts Here</h1>
            <div class="cta-buttons">
                <a href="booking.php" class="cta-btn">Book Now</a>
                <a href="services.php" class="cta-btn">View Services</a>
            </div>
        </div>
    </section>

    <!-- Services Overview Carousel -->
    <section id="services" class="services">
        <h2>Popular Services</h2>
        <div class="carousel">
            <?php
            // Loop through the fetched services and create a service card for each one
            if ($services_result->num_rows > 0) {
                while ($row = $services_result->fetch_assoc()) {
                    // Dynamically display each service's details
                    echo '<div class="service-card">';
                    echo '<img src="service' . $row['service_id'] . '.jpg" alt="' . htmlspecialchars($row['service_name']) . '">';
                    echo '<h3>' . htmlspecialchars($row['service_name']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    echo '<p class="price">₱' . htmlspecialchars($row['price']) . '</p>';
                    echo '<a href="booking.php?service_id=' . $row['service_id'] . '" class="cta-btn">Book Now</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No services available at the moment.</p>';
            }
            ?>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <h2>What Our Clients Say</h2>
        <div class="testimonial-slider">
            <?php
            // Loop through the fetched reviews and create a testimonial card for each one
            if ($reviews_result->num_rows > 0) {
                while ($review = $reviews_result->fetch_assoc()) {
                    $rating = $review['rating'];
                    // Generate stars based on the rating
                    $stars = str_repeat('⭐', $rating) . str_repeat('☆', 5 - $rating); // Display filled and empty stars
                    echo '<div class="testimonial">';
                    echo '<img src="client' . $review['users_id'] . '.jpg" alt="Client ' . $review['users_id'] . '">';
                    echo '<p>"' . htmlspecialchars($review['comment']) . '"</p>';
                    echo '<span>' . $stars . '</span>'; // Display the stars
                    echo '<p>- Client ' . $review['users_id'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No testimonials available at the moment.</p>';
            }
            ?>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta">
        <h2>Ready to book your appointment?</h2>
        <a href="booking.php" class="cta-btn">Book Now</a>
    </section>

</body>
</html>
