<?php
$image_message = '';
function save_image($fileobj, $imagename){
    $target_dir = "uploads/images/";
    $target_file = $target_dir . $imagename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($fileobj);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $image_message = "File is not an image.";
        return false;
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        $imagename .= random_int(10, 10000);
        $target_file = $target_dir . $imagename;
        if (file_exists($target_file)) {
            $image_message = "error saving the images (name conflict)";
            return false;
        }
    }
    // Check file size
    if ($fileobj["size"] > 500000) {
        $image_message = "Sorry, your file is too large.";
        return false;
    }
    if (move_uploaded_file($fileobj["tmp_name"], $target_file)) {
        return $imagename;
    } else {
        $image_message = 'Error saving image';
        return false;
    }
}
?>