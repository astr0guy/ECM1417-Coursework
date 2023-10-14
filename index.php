<!DOCTYPE html>
<?php
session_start();
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
            margin: 10% 30% 20%;
            padding-bottom: 20px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a id="home" class="active" style="float: left;" href="index.php"><b>home</b></a>
        <a id="memory" style="float: right;" href="pairs.php"><b>memory</b></a>
        <?php
            if (isset($_SESSION["user"])){
                echo "<a id=\"leaderboard\" style=\"float: right;\" href=\"leaderboard.php\"><b>leaderboard</b></a>";
                echo "<img src=\"".$_SESSION["avatar-colour"]."\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\";>";
                echo "<img src=\"".$_SESSION["avatar-eyes"]."\" style=\" float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\";>";
                echo "<img src=\"".$_SESSION["avatar-mouth"]."\" style=\" float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\";>";
            } else {
                echo "<a id=\"register\" style=\"float: right;\" href=\"registration.php\"><b>register</b></a>";
            }
        ?>
    </div>
    <?php
        if (isset($_SESSION["user"])){
            echo "<div class=\"user-window\"> <h2>".$_SESSION["user"]."</h2> </div>";
        }
    ?>

    <div class="main">
        <?php
        if (isset($_SESSION["user"])){
            echo "<h1>Welcome to Pairs</h1>
            <a style=\"float: inside; text-shadow: 1px 1px 2px black;\" href=\"pairs.php\"><b>Click here to play</b></a>";
        } else {
            echo "<h1>You're not using a registered session?</h1>
            <a style=\"float: inside; font-size: 30px; text-shadow: 1px 1px 2px black;\" href=\"registration.php\"><b>Register now</b></a>";
        }
        ?>
    </div>
</body>
</html>