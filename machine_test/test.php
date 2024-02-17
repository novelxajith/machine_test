<?php

include 'upload.php';  // Include the file with your functions

// Folder containing test images
$folder = "C:\\xampp\\htdocs\\machine_test\\test_image\\";

// Establish database connection (modify as needed)
include 'conn.php';

// Get a list of files in the folder
$files = scandir($folder);

// Remove "." and ".." from the list
$files = array_diff($files, array('.', '..'));

// Loop through each file and upload
foreach ($files as $filename) {
    // Full path to the file
    $fullPath = $folder . $filename;

    // Simulate a file array
    $file = [
        "name" => $filename,
        "full_path" => $fullPath,
        "type" => mime_content_type($fullPath),
        "tmp_name" => $fullPath,
        "error" => 0,
        "size" => filesize($fullPath),
    ];

    // Call the uploadImage function
    $result = uploadImage($file);

    // Output the result for each file
    echo $result . "<br>";
}

// Close the database connection
mysqli_close($conn);
?>
