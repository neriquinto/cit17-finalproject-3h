<!-- PHP FILE -->
<?php
    include "db.php";

    $serviceType = isset($_GET['service-type']) ? $_GET['service-type'] : 'all';
    $priceRange = isset($_GET['price-range']) ? $_GET['price-range'] : 100; // Default max price
    $duration = isset($_GET['duration']) ? $_GET['duration'] : 'all';
    $sortBy = isset($_GET['sort-by']) ? $_GET['sort-by'] : 'popularity'; // Default sorting by popularity

    // Build the WHERE clause based on selected filters
    $whereConditions = [];

    if ($serviceType != 'all') {
        $whereConditions[] = "service_name LIKE '%$serviceType%'";
    }

    if ($duration != 'all') {
        $whereConditions[] = "duration = $duration";
    }

    if ($priceRange != 100) {
        $whereConditions[] = "price <= $priceRange";
    }

    // Combine conditions into the WHERE clause
    $whereClause = "";
    if (count($whereConditions) > 0) {
        $whereClause = "WHERE " . implode(" AND ", $whereConditions);
    }

    // Build the ORDER BY clause based on sorting option
    $orderBy = "ORDER BY price"; // Default sorting by price
    if ($sortBy == "popularity") {
        $orderBy = "ORDER BY price ASC"; // Sorting by popularity based on lowest price
    } elseif ($sortBy == "duration") {
        $orderBy = "ORDER BY duration"; // Sorting by duration
    }

    // Query to fetch services based on filters and sorting
    $query = "SELECT * FROM services $whereClause $orderBy";
    $result = $conn->query($query);
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Our Services</title>
    <script>
        // JavaScript to update price range value and submit the form
        function updatePriceRange(value) {
            document.getElementById("price-range-value").textContent = "$0 - $" + value;
        }

        // JavaScript to auto-submit the form when sorting changes
        function autoSubmitSort() {
            document.getElementById("filter-form").submit();
        }
    </script>
</head>
<body>
    <div class="container">
        <!-- Filter and Sorting Options -->
        <aside class="filter-sidebar">
            <h2>Filter Services</h2>
            <form id="filter-form" method="GET">
                <!-- Service Type -->
                <div class="filter-group">
                    <label for="service-type">Service Type</label>
                    <select id="service-type" name="service-type" onchange="this.form.submit()">
                        <option value="all" <?php if ($serviceType == 'all') echo 'selected'; ?>>All</option>
                        <option value="massage" <?php if ($serviceType == 'massage') echo 'selected'; ?>>Massage</option>
                        <option value="facial" <?php if ($serviceType == 'facial') echo 'selected'; ?>>Facial</option>
                        <option value="body-scrub" <?php if ($serviceType == 'body-scrub') echo 'selected'; ?>>Body Scrub</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div class="filter-group">
                    <label for="price-range">Price Range</label>
                    <input type="range" id="price-range" name="price-range" min="0" max="5000" value="<?php echo $priceRange; ?>" oninput="updatePriceRange(this.value); this.form.submit();">
                    <span id="price-range-value">₱0 - ₱5000</span>
                </div>

                <!-- Duration -->
                <div class="filter-group">
                    <label for="duration">Duration (minutes)</label>
                    <select id="duration" name="duration" onchange="this.form.submit()">
                        <option value="all" <?php if ($duration == 'all') echo 'selected'; ?>>All</option>
                        <option value="30" <?php if ($duration == '30') echo 'selected'; ?>>30 mins</option>
                        <option value="60" <?php if ($duration == '60') echo 'selected'; ?>>60 mins</option>
                        <option value="90" <?php if ($duration == '90') echo 'selected'; ?>>90 mins</option>
                    </select>
                </div>

                <button type="submit" class="apply-filters-btn">Apply Filters</button>
            </form>
        </aside>

        <!-- Service List -->
        <main class="service-list">
            <div class="sorting-options">
                <label for="sort-by">Sort By:</label>
                <select id="sort-by" name="sort-by" onchange="autoSubmitSort()">
                    <option value="popularity" <?php if ($sortBy == 'popularity') echo 'selected'; ?>>Popularity</option>
                    <option value="price" <?php if ($sortBy == 'price') echo 'selected'; ?>>Price</option>
                    <option value="duration" <?php if ($sortBy == 'duration') echo 'selected'; ?>>Duration</option>
                </select>
            </div>

            <div class="service-cards">
                <?php
                // Display services from the database
                if ($result->num_rows > 0) {
                    while ($service = $result->fetch_assoc()) {
                        echo '<div class="service-card">';
                        echo '<img src="service' . $service['service_id'] . '.jpg" alt="' . htmlspecialchars($service['service_name']) . '" class="service-image">';
                        echo '<h3>' . htmlspecialchars($service['service_name']) . '</h3>';
                        echo '<span class="price">₱' . htmlspecialchars($service['price']) . '</span>';
                        echo '<span class="duration">' . htmlspecialchars($service['duration']) . ' mins</span>';
                        echo '<p>' . htmlspecialchars($service['description']) . '</p>';
                        echo '<a href="booking.php" class="cta-btn">Book Now</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No services found.</p>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>