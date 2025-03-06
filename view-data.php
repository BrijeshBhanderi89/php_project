<?php

$con = mysqli_connect("localhost", "root", "", "brijesh");
if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
}


$records_per_page = 3;  
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$start_from = ($page - 1) * $records_per_page;


$search = ''; 
$sql = "SELECT * FROM `insert-data`";
if (isset($_GET['search_text']) && !empty($_GET['search_text'])) {
    $search = $_GET['search_text']; 
    $sql .= " WHERE `name` LIKE ? OR `email` LIKE ?"; 
}


$sql .= " LIMIT ?, ?";


$stmt = $con->prepare($sql);
if (!empty($search)) {
    
    $search_param = "%" . $search . "%";
    $stmt->bind_param("ssii", $search_param, $search_param, $start_from, $records_per_page);
} else {
    $stmt->bind_param("ii", $start_from, $records_per_page);
}
$stmt->execute();
$qry = $stmt->get_result();
if (!$qry) {
    die("Query Failed: " . mysqli_error($con));
}


$sql_total = "SELECT COUNT(*) FROM `insert-data`";
if (!empty($search)) {
    $sql_total .= " WHERE `name` LIKE ? OR `email` LIKE ?";
    $total_stmt = $con->prepare($sql_total);
    $search_param = "%" . $search . "%";
    $total_stmt->bind_param("ss", $search_param, $search_param);
} else {
    $total_stmt = $con->prepare($sql_total);
}
$total_stmt->execute();
$result_total = $total_stmt->get_result();
$total_records = mysqli_fetch_array($result_total)[0];
$total_pages = ceil($total_records / $records_per_page); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Form</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    
    <div class="row mb-4">
        <div class="col text-center">
            <form method="GET" action="">
                <input type="text" name="search_text" class="form-control w-50 d-inline" placeholder="Search by Name or Email" value="<?php echo isset($_GET['search_text']) ? htmlspecialchars($_GET['search_text']) : ''; ?>">
                <input type="submit" name="search" class="btn btn-primary ml-2" value="SEARCH">
            </form>
        </div>
    </div>

   
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Hobby</th>
                <th>Phone Number</th>
                <th>Message</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($record = mysqli_fetch_assoc($qry)): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['id']); ?></td>
                <td><?php echo htmlspecialchars($record['name']); ?></td>
                <td><?php echo htmlspecialchars($record['email']); ?></td>
                <td><?php echo htmlspecialchars($record['hobby']); ?></td>
                <td><?php echo htmlspecialchars($record['phone']); ?></td>
                <td><?php echo htmlspecialchars($record['message']); ?></td>
                <td>
                    <img src="assets/image/<?php echo htmlspecialchars($record['image']); ?>" alt="Image" class="img-fluid" style="max-width: 100px;">
                </td>
                <td>
                    <a href="delete-data.php?id=<?php echo $record['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this data?')">DELETE</a>
                    <a href="updata-data.php?id=<?php echo $record['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to update this data?')">UPDATE</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

   
    <div class="pagination justify-content-center">
        <?php
        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . '&search_text=' . urlencode($search) . '" class="btn btn-secondary btn-sm mr-1">Previous</a>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo '<span class="btn btn-outline-secondary btn-sm mr-1" disabled>' . $i . '</span>';
            } else {
                echo '<a href="?page=' . $i . '&search_text=' . urlencode($search) . '" class="btn btn-outline-secondary btn-sm mr-1">' . $i . '</a>';
            }
        }

        if ($page < $total_pages) {
            echo '<a href="?page=' . ($page + 1) . '&search_text=' . urlencode($search) . '" class="btn btn-secondary btn-sm ml-1">Next</a>';
        }
        ?>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
