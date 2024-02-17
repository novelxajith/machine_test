<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload with Bootstrap</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Image Upload</h2>

    <form action="insert.php" method="post" enctype="multipart/form-data" id="uploadForm">
        <div class="mb-3">
            <label for="image" class="form-label">Choose an image:</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*,application/pdf,.docx">
        </div>
        <button type="submit" class="btn btn-primary">Upload Image</button>
    </form>
    <a href="logout.php" class="btn btn-primary mt-2">Logout</a>
</div>

</body>
</html>
