<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include '../server/db_connection.php';

// Fetch filter column and value
$filterColumn = isset($_POST['filterColumn']) ? $_POST['filterColumn'] : 'b.name';
$filterValue = isset($_POST['filterValue']) ? $_POST['filterValue'] : '';

// Delete products with quantity of zero
$deleteSql = "DELETE FROM total_stock WHERE quantity = 0";
$conn->query($deleteSql);

// Fetch all stock data based on filter
$sql = "SELECT ts.id, b.name AS brand_name, pt.type_name AS product_type, ts.price, ts.selling_price, ts.quantity, ts.product_id, ts.specifications, m.model_number AS model_name 
        FROM total_stock ts 
        JOIN brandname b ON ts.brand_id = b.id 
        JOIN product_type pt ON ts.product_type_id = pt.id
        JOIN models m ON ts.model_name = m.id
        WHERE ts.quantity > 0"; // Exclude zero quantity

if (!empty($filterValue)) {
    $sql .= " AND $filterColumn = ?";
}

$stmt = $conn->prepare($sql);

if (!empty($filterValue)) {
    $stmt->bind_param("s", $filterValue);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Stock</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        function confirmDelete(id) {
            $('#confirmDeleteModal').modal('show');
            $('#confirmDeleteBtn').attr('data-id', id);
        }

        $(document).ready(function() {
            $('#confirmDeleteBtn').click(function() {
                var id = $(this).attr('data-id');
                window.location.href = 'delete_stock.php?id=' + id;
            });
        });
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
        <img src="images/logoipsum-325.svg" alt="Logo" style="max-height: 50px;
            max-width:150px">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_stock.php">Add Stock</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_stock.php">View Stock</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="make_bill.php">Make Bill</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="setting.php"><i class="fas fa-cog"></i> Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- View Stock -->
    <div class="container mt-4">
        <h1>View Stock</h1>

        <!-- Filter Form -->
        <form method="POST" action="view_stock.php" class="mb-4">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <label for="filterColumn" class="sr-only">Filter By</label>
                    <select class="form-control mb-2" id="filterColumn" name="filterColumn">
                        <option value="b.name" <?php if ($filterColumn == 'b.name') echo 'selected'; ?>>Brand Name</option>
                        <option value="pt.type_name" <?php if ($filterColumn == 'pt.type_name') echo 'selected'; ?>>Product Type</option>
                        <option value="ts.price" <?php if ($filterColumn == 'ts.price') echo 'selected'; ?>>Price</option>
                        <option value="ts.selling_price" <?php if ($filterColumn == 'ts.selling_price') echo 'selected'; ?>>Selling Price</option>
                        <option value="ts.quantity" <?php if ($filterColumn == 'ts.quantity') echo 'selected'; ?>>Quantity</option>
                        <option value="ts.product_id" <?php if ($filterColumn == 'ts.product_id') echo 'selected'; ?>>Product ID</option>
                        <option value="ts.specifications" <?php if ($filterColumn == 'ts.specifications') echo 'selected'; ?>>Specifications</option>
                        <option value="m.model_number" <?php if ($filterColumn == 'm.model_number') echo 'selected'; ?>>Model Name</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control mb-2" id="filterValue" name="filterValue" placeholder="Filter..." value="<?php echo htmlspecialchars($filterValue); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-2">Filter</button>
                    <a href="view_stock.php" class="btn btn-secondary mb-2">View All</a>
                </div>
            </div>
        </form>

        <!-- Stock Table -->
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>Brand Name</th>
                    <th>Product Type</th>
                    <th>Price</th>
                    <th>Selling Price</th>
                    <th>Quantity</th>
                    <th>Product ID</th>
                    <th>Specifications</th>
                    <th>Model Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['brand_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['selling_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['specifications']); ?></td>
                            <td><?php echo htmlspecialchars($row['model_name']); ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
