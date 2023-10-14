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
            background-color: grey;
            box-shadow: 5px 5px black;
            alignment: center;
            display: flex;
            flex-wrap: wrap;
            align-content: space-around;
            text-align: center;
            padding: 20px 20px 20px 20px;
            margin-top: 10%;
            margin-left: 20%;
            margin-right: 20%;
            overflow: hidden;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 5%;
            border-spacing: 2px;
        }

        tr:nth-child(even) {
            background-color:forestgreen;
        }
        tr:nth-child(odd) {
            background-color:darkblue;
        }

        th{
            background-color: blue;
        }

        td, th {
            border: 1px solid deeppink;
            text-align: left;
            padding: 8px;
        }


    </style>
</head>
<body>
    <div class="navbar">
        <a id="home" style="float: left;" href="index.php"><b>home</b></a>
        <a id="memory" style="float: right;" href="pairs.php"><b>memory</b></a>
        <a id="leaderboard" class="active" style="float: right;" href="leaderboard.php"><b>leaderboard</b></a>
        <?php
        echo "<img src=\"".$_SESSION["avatar-colour"]."\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\";>";
        echo "<img src=\"".$_SESSION["avatar-eyes"]."\" style=\" float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\";>";
        echo "<img src=\"".$_SESSION["avatar-mouth"]."\" style=\" float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\";>";
        ?>
    </div>
    <div class="user-window">
        <?php
        if (isset($_SESSION["user"])){
            echo "<h2>".$_SESSION["user"]."</h2>";
        }
        ?>
    </div>
    <div class="main">

        <?php

        function LeaderboardGenerator ($fileName, $scoreRef){
            if (!file_exists($fileName)){
                $leaderboardFile = fopen($fileName, "x+");
                $scoreList = array(0);
            } else {
                $leaderboardFile = fopen($fileName, "r+");
                $scoreList = json_decode(fread($leaderboardFile, filesize($fileName)));
            }
            if (count($scoreList) !== 5){
                fclose($leaderboardFile);
                unlink($fileName);
                $leaderboardFile = fopen($fileName, "x+");
                $scoreList = (array(
                    (array("NOBODY", 0)),
                    (array("NOBODY", 0)),
                    (array("NOBODY", 0)),
                    (array("NOBODY", 0)),
                    (array("NOBODY", 0))
                ));
                fwrite($leaderboardFile, json_encode($scoreList));
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST"
            && $_SESSION["submitted"] == "FALSE") {
                for ($i=0;$i<count($scoreList);$i++){
                    if ($_POST[$scoreRef] > $scoreList[$i][1]){
                        $scoreList[] = array($_SESSION["user"], $_POST[$scoreRef]);
                        usort($scoreList, function($a, $b) {
                            return $b[1] <=> $a[1];
                        });
                        array_pop($scoreList);
                        break;
                    }
                }
                }
            fclose($leaderboardFile);
            unlink($fileName);
            $leaderboardFile = fopen($fileName, "x");
            fwrite($leaderboardFile, json_encode($scoreList));
            fclose($leaderboardFile);
            for ($i=0;$i<count($scoreList);$i++) {
                echo "<tr>";
                echo "<td>" . $scoreList[$i][0] . "</td>";
                echo "<td>" . $scoreList[$i][1] . "</td>";
                echo "</tr>";

            }
            echo "</table>";
        }



        echo "<h1> TOP 5 OVERALL SCORES</h1>
        <table id=\"top5table\">
        <tr>
            <th><b>USERNAME</b></th>
            <th><b>SCORE</b></th>

        </tr>";
        LeaderboardGenerator("leaderboard.json", "score");

        for ($i=1;$i<7;$i++){
            echo "<h1> TOP 5 LEVEL ".$i." SCORES </h1>
            <table id=\"level".$i."table\">
        <tr>
            <th><b>USERNAME</b></th>
            <th><b>SCORE</b></th>

        </tr>";
            LeaderboardGenerator("leaderboard".$i.".json", "score".$i);
            }
        $_SESSION["submitted"] = "TRUE";



        ?>
    </div>
</body>
</html>