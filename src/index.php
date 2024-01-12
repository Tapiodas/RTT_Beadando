<?php
$servername = "mysql";
$username = "rtt-database";
$password = "rtt-database";
$dbname = "rtt-database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tableCreationQuery = "
CREATE TABLE IF NOT EXISTS felhasz (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    email VARCHAR(50),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
    ";

if ($conn->query($tableCreationQuery) === TRUE) {
    echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["create"])) {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $insertQuery = "INSERT INTO felhasz (name, email) VALUES ('$name', '$email')";
        $conn->query($insertQuery);
    }

    elseif (isset($_POST["read"])) {
        $selectQuery = "SELECT * FROM felhasz";
        $result = $conn->query($selectQuery);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td><td>" . $row["email"] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No records found</td></tr>";
        }
    }

    elseif (isset($_POST["update"])) {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $updateQuery = "UPDATE felhasz SET name='$name', email='$email' WHERE id=$id";
        $conn->query($updateQuery);
    }

    elseif (isset($_POST["delete"])) {
        $id = $_POST["id"];
        $deleteQuery = "DELETE FROM felhasz WHERE id=$id";
        $conn->query($deleteQuery);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD with Bootstrap</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="text-center">Main Page</h1>
    <div class="text-center">
      <a href="contact.php" class="btn btn-primary">Contact</a>
      <a href="about.php" class="btn btn-secondary">About</a>
    </div>
  </div>

    <div class="container mt-5">
        <h2>CRUD with Bootstrap</h2>
        <form method="post" class="mb-3">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary" name="create">Create</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $selectQuery = "SELECT * FROM felhasz";
                    $result = $conn->query($selectQuery);
                
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["id"] . "</td>
                                    <td>" . $row["name"] . "</td>
                                    <td>" . $row["email"] . "</td>
                                    <td>
                                        <form method='post' style='display:inline;'>
                                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                                            <button type='submit' class='btn btn-warning btn-sm' name='edit'>Edit</button>
                                        </form>
                                        <form method='post' style='display:inline;'>
                                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                                            <button type='submit' class='btn btn-danger btn-sm' name='delete'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No records found</td></tr>";
                    }
                
                
                if (isset($_POST["edit"])) {
                    $id = $_POST["id"];
                    $selectEditQuery = "SELECT * FROM felhasz WHERE id = $id";
                    $result = $conn->query($selectEditQuery);
                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();
                        echo "<form method='post'>
                                <tr>
                                    <td><input type='hidden' name='id' value='" . $row["id"] . "'>" . $row["id"] . "</td>
                                    <td><input type='text' name='name' value='" . $row["name"] . "'></td>
                                    <td><input type='email' name='email' value='" . $row["email"] . "'></td>
                                    <td>
                                        <button type='submit' class='btn btn-success btn-sm' name='update'>Update</button>
                                        <button type='submit' class='btn btn-secondary btn-sm' name='cancel'>Cancel</button>
                                    </td>
                                </tr>
                            </form>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


