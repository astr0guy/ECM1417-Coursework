<!DOCTYPE html>
<?php
session_start();
session_unset();
?>
<html lang="en">
<head>
    <title>Landing Page</title>
    <link rel="stylesheet" href="uniform-styles.css">
    <style>
        .main {
            color: white;
            background-color: royalblue;
            font-family: Consolas, serif;
            alignment: center;
            text-align: center;
            padding: 50px 50px 50px;
            margin-top: 10%;
            margin-left: 28%;
            margin-right: 28%;
            overflow: hidden;
        }
        input[type="radio"] {
            margin-left:10px;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a style="float: left;" href="index.php"><b>home</b></a>
    <a style="float: right;" href="pairs.php"><b>memory</b></a>
    <a class="active" style="float: right;" href="registration.php"><b>register</b></a>
</div>



<?php


    $passes = true;
    $username = "";
    $usernameErr="";
    $colourErr="";
    $eyeErr="";
    $mouthErr="";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["username"])) {
            $usernameErr = "Username is required";
            $passes = false;
        } else {
            $username = ($_POST["username"]);
            if (preg_match("/[”!@#%\^&\*\(\)\+=\{\}\[\]—;:“’<>\?\/]/", $username)) {
                $usernameErr = "<br>Invalid username! The following characters are not allowed:<br> <br> ” ! @ # % ^ & * ( ) + = { } [ ] — ; : “ ’ < > ? / <br>";
                $passes = false;
            }
        }
        if (empty($_POST["colour"])) {
            $colourErr = "Colour is required";
            $passes = false;
        }
        if (empty($_POST["eyes"])) {
            $eyeErr = "Eyes are required";
            $passes = false;
        }
        if (empty($_POST["mouth"])) {
            $mouthErr = "Mouth is required";
            $passes = false;
        } else
        if ($passes) {
            $_SESSION["user"] = $username;
            $_SESSION["avatar-colour"] = $_POST["colour"];
            $_SESSION["avatar-eyes"] = $_POST["eyes"];
            $_SESSION["avatar-mouth"] = $_POST["mouth"];
            header("Location: index.php");
        }
    }
?>
<div class="main">
    <form style="display: inline" action="registration.php" method="post">
        Username: <label>
            <input type="text" name="username">
        </label>
        <br>
        <?php echo "<p style='color: red'>".$usernameErr."<p>"; ?>
        <br>
        Colour: <br>
        <label><img src="emoji%20assets/skin/green.png" alt="green circle" style="width: 20px" > Green
            <input type="radio" name="colour" value="emoji%20assets/skin/green.png">
        </label>
        <label><img src="emoji%20assets/skin/red.png" alt="red circle" style="width: 20px"> Red
            <input type="radio" name="colour" value="emoji%20assets/skin/red.png">
        </label>
        <label><img src="emoji%20assets/skin/yellow.png" alt="yellow circle" style="width: 20px"> Yellow
            <input type="radio" name="colour" value="emoji%20assets/skin/yellow.png">
        </label>
        <br>
        <?php echo "<p style='color: red'>".$colourErr."<p>"; ?>
        <br>
        Eyes: <br>
        <label><img src="emoji%20assets/eyes/closed.png" alt="closed eyes" style="width: 40px">
            <input type="radio" name="eyes" value="emoji%20assets/eyes/closed.png">
        </label>
        <label><img src="emoji%20assets/eyes/laughing.png" alt="squinting eyes" style="width: 40px">
            <input type="radio" name="eyes" value="emoji%20assets/eyes/laughing.png">
        </label>
        <label><img src="emoji%20assets/eyes/long.png" alt="long open eyes" style="width: 40px">
            <input type="radio" name="eyes" value="emoji%20assets/eyes/long.png">
        </label>
        <label><img src="emoji%20assets/eyes/normal.png" alt="open eyes" style="width: 40px">
            <input type="radio" name="eyes" value="emoji%20assets/eyes/normal.png">
        </label>
        <label><img src="emoji%20assets/eyes/rolling.png" alt="wide eyes looking upwards and to the right" style="width: 40px">
            <input type="radio" name="eyes" value="emoji%20assets/eyes/rolling.png">
        </label>
        <label><img src="emoji%20assets/eyes/winking.png" alt="winking eyes" style="width: 40px">
            <input type="radio" name="eyes" value="emoji%20assets/eyes/winking.png">
        </label>
        <br>
        <?php echo "<p style='color: red'>".$eyeErr."<p>"; ?>
        <br>
        Mouth: <br>
        <label><img src="emoji%20assets/mouth/open.png" alt="smiling mouth with teeth" style="width: 40px">
            <input type="radio" name="mouth" value="emoji%20assets/mouth/open.png">
        </label>
        <label><img src="emoji%20assets/mouth/sad.png" alt="downward turned closed mouth" style="width: 40px">
            <input type="radio" name="mouth" value="emoji%20assets/mouth/sad.png">
        </label>
        <label><img src="emoji%20assets/mouth/smiling.png" alt="upward turned mouth without teeth" style="width: 40px">
            <input type="radio" name="mouth" value="emoji%20assets/mouth/smiling.png">
        </label>
        <label><img src="emoji%20assets/mouth/straight.png" alt="straight closed mouth" style="width: 40px">
            <input type="radio" name="mouth" value="emoji%20assets/mouth/straight.png">
        </label>
        <label><img src="emoji%20assets/mouth/surprise.png" alt="surprised mouth" style="width: 40px">
            <input type="radio" name="mouth" value="emoji%20assets/mouth/surprise.png">
        </label>
        <label><img src="emoji%20assets/mouth/teeth.png" alt="grinning mouth with teeth" style="width: 40px">
            <input type="radio" name="mouth" value="emoji%20assets/mouth/teeth.png">
        </label>
        <br>
        <?php echo "<p style='color: red'>".$mouthErr."<p>"; ?>
        <br>
        <input type="submit">
    </form>
</div>

</body>
</html>