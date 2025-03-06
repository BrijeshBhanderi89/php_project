<?php
$con = mysqli_connect("localhost", "root", "", "brijesh") or die("Connection Failed");

if (isset($_POST["submit"])) {
    $name = mysqli_real_escape_string($con, $_POST["name"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $hobby = isset($_POST["hobby"]) ? implode(",", $_POST["hobby"]) : '';
    
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_type = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($image_type, $allowed_types) && $image_size <= 5000000) { 
        $image_name = uniqid() . '.' . $image_type;
        $path = 'assets/image/' . $image_name;

        if (!file_exists('assets/image')) {
            mkdir('assets/image', 0777, true);
        }

        if (move_uploaded_file($image_tmp, $path)) {
            $upload_status = "Image uploaded successfully!";
        } else {
            die("Image upload failed.");
        }
    } else {
        die("Invalid image file or file too large.");
    }

    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    $stmt = $con->prepare("INSERT INTO `insert-data` (`name`, `email`, `hobby`, `image`, `phone`, `message`) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $hobby, $path, $phone, $message);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Something went wrong: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            gap: 20px;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 10px;
        }
        table {
            width: 100%;
        }
        td {
            padding: 8px;
        }
        input[type="text"],
        input[type="email"],
        input[type="file"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .hobby-checkbox {
            margin-right: 10px;
        }
        .response {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <form method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td><label for="name">Name:</label></td>
                <td><input type="text" id="name" name="name" placeholder="Enter Your name..." required></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="email" id="email" name="email" placeholder="Enter Your Email..." required></td>
            </tr>
            <tr>
                <td><label>Hobby:</label></td>
                <td>
                    <input type="checkbox" class="hobby-checkbox" name="hobby[]" value="cricket"> Cricket
                    <input type="checkbox" class="hobby-checkbox" name="hobby[]" value="coding"> Coding
                    <input type="checkbox" class="hobby-checkbox" name="hobby[]" value="singing"> Singing
                    <input type="checkbox" class="hobby-checkbox" name="hobby[]" value="dancing"> Dancing
                </td>
            </tr>
            <tr>
                <td><label for="image">Image:</label></td>
                <td><input type="file" id="image" name="image" required></td>
            </tr>
            <tr>
                <td><label for="phone">Phone Number:</label></td>
                <td><input type="tel" id="phone" name="phone" placeholder="Enter Your Phone Number..." required></td>
            </tr>
            <tr>
                <td><label for="message">Message:</label></td>
                <td><textarea id="message" name="message" rows="4" placeholder="Enter Your Message..." required></textarea></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="submit" value="Submit" id="submitForm">
                </td>
            </tr>
        </table>
    </form>

    <div class="response"></div>

    <script>
        $(document).ready(function() {
            $("#submitForm").on("submit", function(e) {
                e.preventDefault(e);

                var formData = new FormData(this);

                $.ajax({
                    url: 'view-data.php',  
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $(".response").html('<p>' + response + '</p>');
                    },
                    error: function() {
                        $(".response").html('<p>Error while submitting the form!</p>');
                    }
                });
            });
        });
    </script>

</body>
</html>
