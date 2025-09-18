<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRATOR</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/addvehicle.css">
</head>
<body>
    <button id="back"><a href="adminvehicle.php">CANCEL</a></button>
    <div class="main">
        <div class="register">
            <h2>Enter Details Of New Vehicle</h2>
            <img id="image-preview" alt="Selected Vehicle Image Preview">
            <form id="register" action="upload.php" method="POST" enctype="multipart/form-data">
                <label>Vehicle Name:</label>
                <input type="text" name="vehiclename" placeholder="Enter Vehicle Name" required>
                <label>Vehicle Type:</label>
                <input type="text" name="vehicletype" placeholder="Enter Vehicle Type (e.g., Car, Bike, Scooter)" required>
                <label>Fuel Type:</label>
                <input type="text" name="ftype" placeholder="Enter Fuel Type" required>
                <label>Capacity:</label>
                <input type="number" name="capacity" min="1" placeholder="Enter Capacity Of Vehicle" required>
                <label>Price:</label>
                <input type="number" name="price" min="1" placeholder="Enter Price Of Vehicle for One Day (in rupees)" required>
                <label>Vehicle Image:</label>
                <input type="file" name="image" accept="image/*" required>
                <input type="submit" class="btnn" value="ADD VEHICLE" name="addvehicle">
            </form>
        </div>
    </div>
    <script>
        // JavaScript to handle image preview
        document.querySelector('input[name="image"]').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>