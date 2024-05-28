<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search and sorting
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'id';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] == 'desc' ? 'DESC' : 'ASC';

// Fetch data
$sql = "SELECT * FROM users WHERE username LIKE '%$search%' ORDER BY $order_by $order_dir LIMIT $start, $limit";
$result = $conn->query($sql);

$total_sql = "SELECT COUNT(*) FROM users WHERE username LIKE '%$search%'";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
</head>
<body>
    <form action="index.php" method="get">
        Search: <input type="text" name="search" value="<?php echo $search; ?>">
        <input type="submit" value="Search">
    </form>

    <table border="1">
        <thead>
            <tr>
                <th><a href="?order_by=username&order_dir=<?php echo $order_dir == 'ASC' ? 'desc' : 'asc'; ?>">Username</a></th>
                <th>Images</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td>
                    <?php 
                    $images = unserialize($row['images']);
                    foreach ($images as $image) {
                        echo "<img src='$image' width='50' height='50'>";
                    }
                    ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>&order_by=<?php echo $order_by; ?>&order_dir=<?php echo $order_dir; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</body>
</html>
