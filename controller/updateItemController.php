<?php
include_once "../config/dbconnect.php";

$product_id = $_POST['product_id'];
$p_name = $_POST['p_name'];
$p_desc = $_POST['p_desc'];
$p_price = $_POST['p_price'];
$category = $_POST['category'];

if (isset($_FILES['newImage'])) {
    $location = "./uploads/";
    $img = $_FILES['newImage']['name'];
    $tmp = $_FILES['newImage']['tmp_name'];
    $dir = '../uploads/';
    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
    $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp');
    $image = rand(1000, 1000000) . "." . $ext;
    $final_image = $location . $image;
    if (in_array($ext, $valid_extensions)) {
        move_uploaded_file($tmp, $dir . $image);
    }
} else {
    $final_image = $_POST['existingImage'];
}

// Use prepared statement to update the data
$stmt = $conn->prepare("UPDATE product SET 
    product_name = ?,
    product_desc = ?,
    price = ?,
    category_id = ?,
    product_image = ?
    WHERE product_id = ?");

$stmt->bind_param("sssisi", $p_name, $p_desc, $p_price, $category, $final_image, $product_id);

if ($stmt->execute()) {
    echo "true";
} else {
    echo "false";
}

$stmt->close();
$conn->close();
?>
