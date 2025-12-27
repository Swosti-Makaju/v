<?php
header('Content-Type: application/json');
require_once('connection.php');

if (isset($_GET['id'])) {
    $book_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "UPDATE booking SET BOOK_STATUS = 'APPROVED' WHERE BOOK_ID = '$book_id'";
    if (mysqli_query($con, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No booking ID provided']);
}
mysqli_close($con);
?>

<?php
header('Content-Type: application/json');
require_once 'connection.php';

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'No booking ID provided']);
    exit;
}

$bookId = (int)$_GET['id'];

mysqli_begin_transaction($con);

try {
    /* 1.  mark booking approved */
    $stmt = $con->prepare("UPDATE booking SET BOOK_STATUS = 'APPROVED' WHERE BOOK_ID = ?");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    if ($stmt->affected_rows === 0) {
        throw new Exception('Booking not found or already approved');
    }

    /* 2.  decrease available count of the vehicle that was booked */
    $upd = $con->prepare(
        "UPDATE vehicles v
         JOIN booking b ON v.VEHICLE_ID = b.VEHICLE_ID
         SET v.AVAILABLE = v.AVAILABLE - 1
         WHERE b.BOOK_ID = ? AND v.AVAILABLE > 0"
    );
    $upd->bind_param("i", $bookId);
    $upd->execute();

    mysqli_commit($con);
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    mysqli_rollback($con);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>