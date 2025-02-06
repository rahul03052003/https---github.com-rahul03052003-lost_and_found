<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Lost Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-danger text-white text-center py-3">
        <h1>Lost Items List</h1>
    </header>
    <div class="container my-4">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title">All Lost Items</h2>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Registration Number</th>
                            <th>Item Name</th>
                            <th>Brand</th>
                            <th>Contact Number</th>
                            <th>Last Seen At</th>
                            <th>Description</th>
                            <th>Photo</th>
                            <th>Actions</th> <!-- Added Actions Column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "lost_and_found";

                        // Create connection
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Fetch all records from the database
                        $sql = "SELECT * FROM lost_items";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['name']) . "</td>
                                        <td>" . htmlspecialchars($row['reg_no']) . "</td>
                                        <td>" . htmlspecialchars($row['item_name']) . "</td>
                                        <td>" . htmlspecialchars($row['brand']) . "</td>
                                        <td>" . htmlspecialchars($row['phno']) . "</td>
                                        <td>" . htmlspecialchars($row['lastseen']) . "</td>
                                        <td>" . htmlspecialchars($row['describeit']) . "</td>
                                        <td><img src='" . $row['photo_path'] . "' alt='Item Photo' width='100'></td>
                                        <td>
                                            <a href='edit_item.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='delete_item.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
                        }

                        // Close connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
