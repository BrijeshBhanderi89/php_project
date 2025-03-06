<?php

$con = mysqli_connect("localhost", "root", "", "brijesh") or die("Connection Failed");


$id = null;
$hobby = [];
$phone = '';
$message = '';
$image = '';


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $qry = "SELECT * FROM `insert-data` WHERE `id`=?";
    $stmt = mysqli_prepare($con, $qry);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    if ($row) {
        $hobby = explode(",", $row["hobby"]);
        $phone = $row['phone'];
        $message = $row['message'];
        $image = $row['image']; 
    }
}


function handleImageUpload($oldImage) {
    if (!empty($_FILES['image']['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        
        if (in_array($file_extension, $allowed_extensions) && $_FILES['image']['size'] <= 5000000) {
            
            if (!empty($oldImage) && file_exists('assets/image/' . $oldImage)) {
                unlink('assets/image/' . $oldImage);
            }

            
            $newImage = uniqid() . '.' . $file_extension;
            $image_tmp = $_FILES['image']['tmp_name'];
            $path = 'assets/image/' . $newImage;
            move_uploaded_file($image_tmp, $path);

            return $newImage;
        } else {
            echo "Invalid image type or size exceeds 5MB.";
            exit();
        }
    }
    return $oldImage; 
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = mysqli_real_escape_string($con, $_POST["name"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $hobby = isset($_POST["hobby"]) ? implode(",", $_POST["hobby"]) : '';
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $message = mysqli_real_escape_string($con, $_POST['message']);
    
    
    $oldimage = $_POST['oldimage'] ?? '';
    $image = handleImageUpload($oldimage);

   
    if ($id) {
        $qry = "UPDATE `insert-data` SET `name`=?, `email`=?, `hobby`=?, `image`=?, `phone`=?, `message`=? WHERE `id`=?";
        $stmt = mysqli_prepare($con, $qry);
        mysqli_stmt_bind_param($stmt, 'ssssssi', $name, $email, $hobby, $image, $phone, $message, $id);
    } else {
        $qry = "INSERT INTO `insert-data` (`name`, `email`, `hobby`, `image`, `phone`, `message`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $qry);
        mysqli_stmt_bind_param($stmt, 'ssssss', $name, $email, $hobby, $image, $phone, $message);
    }

 
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "message" => "Form submitted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something went wrong."]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Form</title>
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
    </style>
</head>
<body>

    <form method="POST" enctype="multipart/form-data">
        
        <?php if ($id) { ?>
            <input type="hidden" name="oldimage" value="<?php echo htmlspecialchars($image); ?>" />
        <?php } ?>

        <table>
            <tr>
                <td><label for="name">Name:</label></td>
                <td><input type="text" id="name" name="name" value="<?php echo isset($row['name']) ? htmlspecialchars($row['name']) : ''; ?>" placeholder="Enter Your name..." required></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="email" id="email" name="email" value="<?php echo isset($row['email']) ? htmlspecialchars($row['email']) : ''; ?>" placeholder="Enter Your Email..." required></td>
            </tr>
            <tr>
                <td><label>Hobby:</label></td>
                <td>
                    <input type="checkbox" class="hobby-checkbox" name="hobby[]" value="cricket" <?php echo in_array("cricket", $hobby) ? 'checked' : ''; ?>> Cricket
                    <input type="checkbox" class="hobby-checkbox" name="hobby[]" value="coding" <?php echo in_array("coding", $hobby) ? 'checked' : ''; ?>> Coding
                    <input type="checkbox" class="hobby-checkbox" name="hobby[]" value="singing" <?php echo in_array("singing", $hobby) ? 'checked' : ''; ?>> Singing
                    <input type="checkbox" class="hobby-checkbox" name="hobby[]" value="dancing" <?php echo in_array("dancing", $hobby) ? 'checked' : ''; ?>> Dancing
                </td>
            </tr>
            <tr>
                <td><label for="image">Image:</label></td>
                <td><input type="file" id="image" name="image"></td>
            </tr>
            <tr>
                <td><label for="phone">Phone Number:</label></td>
                <td><input type="tel" id="phone" name="phone" value="<?php echo isset($row['phone']) ? htmlspecialchars($row['phone']) : ''; ?>" placeholder="Enter Your Phone Number..." required></td>
            </tr>
            <tr>
                <td><label for="message">Message:</label></td>
                <td><textarea id="message" name="message" rows="4" placeholder="Enter Your Message..." required><?php echo isset($row['message']) ? htmlspecialchars($row['message']) : ''; ?></textarea></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" name="submit" value="Submit">
                </td>
            </tr>
        </table>
    </form>

</body>
</html>
