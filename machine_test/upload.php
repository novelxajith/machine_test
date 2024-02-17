<?php
    function uploadImage($file, $targetDir = "uploads/", $allowedFormats = ["jpg", "png", "pdf", "docx"], $maxFileSize = 5 * 1024 * 1024) {
      
        include 'conn.php';
        
        $uploadOk = 1;
        $unique = sanitizeFileName(basename($file["name"]));
        $unique_name = $unique[0];
        $targetFile = $targetDir . $unique_name;
        $original_name = basename($file["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is an actual image or a valid document
        $allowedImageFormats = ["jpg", "png"];
        if (in_array($fileType, $allowedImageFormats)) {
            $check = getimagesize($file["tmp_name"]);
            if ($check === false) {
                $get_ip_address = getUploaderIPAddress();
                logUploadEvent($conn,$get_ip_address, $original_name, $unique_name, "Rejected", $rejectionReason = "Not a Valid Image");
                return "Error: File is not a valid image.";
            }
        }

        // Check if the file already exists
        if (file_exists($targetFile)) {
            $get_ip_address = getUploaderIPAddress();
            logUploadEvent($conn,$get_ip_address, $original_name, $unique_name, "Rejected", $rejectionReason = "file already exists");
            return "Error: Sorry, file already exists.";
        }

        // Check file size
        if ($file["size"] > $maxFileSize) {
            $get_ip_address = getUploaderIPAddress();
            logUploadEvent($conn,$get_ip_address, $original_name, $unique_name, "Rejected", $rejectionReason = "file is too large");
            return "Error: Sorry, your file is too large.";
        }

        // Allow certain file formats
        if (!in_array($fileType, $allowedFormats)) {
            $get_ip_address = getUploaderIPAddress();
            logUploadEvent($conn,$get_ip_address, $original_name, $unique_name, "Rejected", $rejectionReason = "Not a Valid file");
            return "Error: Sorry, only JPG, PNG, PDF, and DOCX files are allowed.";
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return "Error: Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                $user_id=$_SESSION["user_id"];
                $insertqry="INSERT INTO `uploads` (`user_id`, `filename`,`entry_date`) VALUES ('$user_id','$unique_name', NOW())";
                mysqli_query($conn, $insertqry);

                $get_ip_address = getUploaderIPAddress();
                if($unique[1] == ""){
                    logUploadEvent($conn,$get_ip_address, $original_name, $unique_name, "Success", $rejectionReason = null);
                }else{
                    logUploadEvent($conn,$get_ip_address, $original_name, $unique_name, "Suspicious", $rejectionReason = "Contains potentially harmful
                    characters and symbols");
                }
                
                return "Success: The file " . basename($file["name"]) . " has been uploaded.";
            } else {
                return "Error: Sorry, there was an error uploading your file.";
            }
        }
    }

function sanitizeFileName($fileName) {
    // Get the original file extension
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Remove the extension from the file name
    $fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);

    // Define a whitelist of allowed characters
    $allowedCharacters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';

    // Replace spaces with underscores
    $cleanedFileName = str_replace(' ', '_', $fileNameWithoutExtension);

    // Remove any characters not in the whitelist
    $cleanedFileName = preg_replace('/[^' . preg_quote($allowedCharacters, '/') . ']/', '', $cleanedFileName);

    // Limit the length of the file name (e.g., 255 characters)
    $cleanedFileName = substr($cleanedFileName, 0, 255);

    // Optionally, add a timestamp or unique identifier to avoid overwriting existing files
    $timestamp = time();
    $cleanedFileName = $cleanedFileName . '_' . $timestamp . '.' . $fileExtension;

    // Check if the filename contains potentially harmful characters
    if (preg_match('/[^' . preg_quote($allowedCharacters, '/') . ']/', $fileNameWithoutExtension)) {
        // Handle the case where potentially harmful characters are found
        return [$cleanedFileName, $fileName];
    } else {
        return [$cleanedFileName,""];
    }
}

function getUploaderIPAddress() {
    // Check for the client IP address in different headers
    $ipHeaders = [
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR',
    ];

    foreach ($ipHeaders as $header) {
        if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
            // Split the list of IPs (if it's present) and take the first one
            $ipList = explode(',', $_SERVER[$header]);
            $ipAddress = trim($ipList[0]);
            
            // Validate the IP address
            if (filter_var($ipAddress, FILTER_VALIDATE_IP)) {
                $ip=sanitizeIPAddress($ipAddress);
                return $ip;
            }
        }
    }

    return "IP address not available.";
}

function sanitizeIPAddress($ipAddress) {
    // Validate the IP address
    if (filter_var($ipAddress, FILTER_VALIDATE_IP)) {
        // If it's a valid IP address, return it
        return $ipAddress;
    } else {
        // If it's not valid, you might choose to return a default value or handle it as needed
        return "Invalid IP address";
    }
}

function logUploadEvent($conn,$uploaderIP, $original_name, $fileName, $uploadStatus, $rejectionReason = null) {

    $uploadStatus = in_array($uploadStatus, ['Success', 'Suspicious', 'Rejected']) ? $uploadStatus : 'Unknown';
    $user_id=$_SESSION["user_id"];
    $sql = "INSERT INTO logs (entry_date, user_id, ip_address, file_name, unique_name, upload_status, rejection_reason)
            VALUES (NOW(),'$user_id','$uploaderIP', '$original_name', '$fileName', '$uploadStatus', '$rejectionReason')";

    mysqli_query($conn, $sql);
}




?>
