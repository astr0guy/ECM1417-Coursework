<?php
session_start();
if (!isset($_SESSION["user"])){
    header("Location: index.php");
}
$_SESSION["submitted"] = "FALSE"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Game Page</title>
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
            padding: 20px 20px 20px 20px;
            margin-top: 5%;
            margin-left: 20%;
            margin-right: 20%;
            overflow: hidden;
        }
        .ruleset{
            line-height: 0.5;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            width = 10%;
            background-color: blue;
        }
        .timer {
            color: cornflowerblue;
            text-shadow: white;
            background-color: deeppink;
            font-size: 30px;
            alignment: center;
            text-align: center;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            padding 5px 5px 5px 5px;
            width: min-content;
            height: min-content;
            overflow: hidden;
        }

        .sharelink {
            background: deepskyblue;
            color: black;
            margin-left: auto;
            margin-right: auto;
            width: 200px;
            text-align: center;
        }

        .points {
            color: lawngreen;
            background-color: black;
            border: lawngreen;
            font-size: 30px;
            alignment: center;
            text-align: center;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            padding 5px 5px 5px 5px;
            width: min-content;
            height: min-content;
            overflow: hidden;
        }
        .card{
            height: 140px;
            width: 100px;
            display: inline-block;
            position: relative;

            margin: auto;
            border-radius: 10px;
            background-color: transparent;
            perspective: 1000px;
        }
        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }
        .card:hover .card-inner {
            transform:
            perspective: 1000px
        }
        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 10px;
            backface-visibility: hidden;
        }
        .card-front {
            color: blueviolet;
            background-color: blueviolet;
        }
        .card-back {
            background-color: white;
            color: white;
            transform: rotateY(180deg);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a style="float: left;" href="index.php"><b>home</b></a>
        <a class="active" style="float: right;" href="pairs.php"><b>memory</b></a>
        <a style="float: right;" href="leaderboard.php"><b>leaderboard</b></a>
        <?php
        echo "<img src=\"".$_SESSION["avatar-colour"]."\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\";>";
        echo "<img src=\"".$_SESSION["avatar-eyes"]."\" style=\" float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\";>";
        echo "<img src=\"".$_SESSION["avatar-mouth"]."\" style=\" float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\";>";
        ?>
    </div>
    <div class="user-window">
        <?php
        echo "<h2>".$_SESSION["user"]."</h2>";
        ?>
    </div>
    <div class="timer" hidden="hidden">
        02:00 <br> Mismatches left: 10
    </div>
    <div class="main" id="gamespace">
        <div class="ruleset">
        <h1 style="color: deeppink">THE GAME:</h1>
        <h4>LEVEL 1</h4>
        <p>Find 3 pairs</p>
        <h4>LEVEL 1</h4>
        <p>Find 6 pairs</p>
        <h4>LEVEL 1</h4>
        <p>Find 4 sets of 3</p>
        <h4>LEVEL 1</h4>
        <p>Find 6 sets of 3</p>
        <h4>LEVEL 1</h4>
        <p>Find 3 sets of 4</p>
        <h4>LEVEL 1</h4>
        <p>Find 4 sets of 4</p>
        <form style="align-self: center">
            <h3> Click here to start the Game!!!</h3>
            <input type="button" value="Start Game" onclick=startGame()>
        </form>
        </div>
    </div>
    <div class="points" hidden="hidden"></div>
    <div class="sharelink" style="display: none;">
        <h1></h1>
        <form method="post" action="pairs.php">
            <input type="submit" value="Play Again!"/>
        </form>
        <form method="post" action="leaderboard.php">
            <input type="text" hidden="hidden" name="score" id="totalScorePoster">
            <input type="text" hidden="hidden" name="score1" id="level1ScorePoster">
            <input type="text" hidden="hidden" name="score2" id="level2ScorePoster">
            <input type="text" hidden="hidden" name="score3" id="level3ScorePoster">
            <input type="text" hidden="hidden" name="score4" id="level4ScorePoster">
            <input type="text" hidden="hidden" name="score5" id="level5ScorePoster">
            <input type="text" hidden="hidden" name="score6" id="level6ScorePoster">
            <input type="submit" value="SHARE SCORE!"/>
        </form>
    </div>
</body>

<script>
    let gameLevel = 0
    let gameover = false;
    let pairs = [];
    let pointsPerLevel = [-1, -1, -1, -1, -1, -1];
    let pointsMultiplier = 0;
    let usedFaces = [];
    let timerCode;
    let pointsDecrementerCode = null;
    let tries = 10;
    let points = 0;
    let timer = 119;
    let viewedCard1 = null;
    let viewedCard2 = null;
    let viewedCard3 = null;

    const eyes = ["<img id=\"suit\" src=\"emoji%20assets/eyes/closed.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/eyes/laughing.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/eyes/long.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/eyes/normal.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/eyes/rolling.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/eyes/winking.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">"];
    const mouths = ["<img id=\"suit\" src=\"emoji%20assets/mouth/open.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/mouth/sad.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/mouth/smiling.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/mouth/straight.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/mouth/surprise.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/mouth/teeth.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">"];
    const skins = ["<img id=\"suit\" src=\"emoji%20assets/skin/green.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/skin/red.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">",
        "<img id=\"suit\" src=\"emoji%20assets/skin/yellow.png\" style=\"float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);width: 43px\">"];

    function startGame (){
        document.getElementById("gamespace").innerHTML= "";
        document.body.children[2].removeAttribute("hidden");
        document.body.children[4].removeAttribute("hidden");
        startTimer(timer);
        playGame(gameLevel);
    }

    function Card(number, image, matchNumber){
        this.number = number;
        this.pairnumber = (Math.ceil((number+1)/matchNumber));
        this.image = image;
        this.paired = new AbortController();
    }



    function pointsMultiplierDecrement() {
        if (pointsDecrementerCode !== null){
            clearInterval(pointsDecrementerCode);
        }
        let timer = 120;
        pointsDecrementerCode = setInterval((function (){
            timer -= 1;
            if (timer > 110){
                pointsMultiplier = 2000;
            } else if (timer > 100){
                pointsMultiplier = 1500;
            } else if (timer > 80){
                pointsMultiplier = 1000;
            } else if (timer > 60){
                pointsMultiplier = 750;
            } else if (timer > 40){
                pointsMultiplier = 500;
            } else if (timer > 20) {
                pointsMultiplier = 250;
            } else if (timer > 0) {
                pointsMultiplier = 100;
            } else {
                pointsMultiplier = 0;
            }
            }), 1000);
        }

    function playGame() {
        if (gameLevel === 0){
            pointsPerLevel[gameLevel] += 1;
            makeCards(6, 2);
            pointsMultiplier = 0;
            addPoints();
            pointsMultiplierDecrement(pointsMultiplier);
            for(let i=0;i<pairs.length;i++){
                document.body.children[3].children[i].firstChild.addEventListener("click", function(){flipCard(i);}, {signal: pairs[i].paired.signal});
                document.body.children[3].children[i].children[0].children[1].innerHTML = pairs[i].image;
            }
        } else if (gameLevel === 1){
            pointsPerLevel[gameLevel] += 1;
            makeCards(10, 2);
            pointsMultiplier = 0;
            addPoints();
            pointsMultiplierDecrement(pointsMultiplier);
            for(let i=0;i<pairs.length;i++){
                document.body.children[3].children[i].firstChild.addEventListener("click", function(){flipCard(i);}, {signal: pairs[i].paired.signal});
                document.body.children[3].children[i].children[0].children[1].innerHTML = pairs[i].image;
            }
        } else if (gameLevel === 2){
            pointsPerLevel[gameLevel] += 1;
            makeCards(12, 3);
            pointsMultiplier = 0;
            addPoints();
            pointsMultiplierDecrement(pointsMultiplier);
            for(let i=0;i<pairs.length;i++){
                document.body.children[3].children[i].firstChild.addEventListener("click", function(){flipCard(i);}, {signal: pairs[i].paired.signal});
                document.body.children[3].children[i].children[0].children[1].innerHTML = pairs[i].image;
            }
        } else if (gameLevel === 3){
            pointsPerLevel[gameLevel] += 1;
            makeCards(18, 3);
            pointsMultiplier = 0;
            addPoints();
            pointsMultiplierDecrement(pointsMultiplier);
            for(let i=0;i<pairs.length;i++){
                document.body.children[3].children[i].firstChild.addEventListener("click", function(){flipCard(i);}, {signal: pairs[i].paired.signal});
                document.body.children[3].children[i].children[0].children[1].innerHTML = pairs[i].image;
            }

        } else if (gameLevel === 4){
            pointsPerLevel[gameLevel] += 1;
            makeCards(16, 4);
            pointsMultiplier = 0;
            addPoints();
            pointsMultiplierDecrement(pointsMultiplier);
            for(let i=0;i<pairs.length;i++){
                document.body.children[3].children[i].firstChild.addEventListener("click", function(){flipCard(i);}, {signal: pairs[i].paired.signal});
                document.body.children[3].children[i].children[0].children[1].innerHTML = pairs[i].image;
            }
        } else if (gameLevel === 5){
            pointsPerLevel[gameLevel] += 1;
            makeCards(20, 4);
            pointsMultiplier = 0;
            addPoints();
            pointsMultiplierDecrement(pointsMultiplier);
            for(let i=0;i<pairs.length;i++){
                document.body.children[3].children[i].firstChild.addEventListener("click", function(){flipCard(i);}, {signal: pairs[i].paired.signal});
                document.body.children[3].children[i].children[0].children[1].innerHTML = pairs[i].image;
            }

        }

    }

    function loseGame(code) {
        for (let i=0;i<pairs.length;i++) {
                pairs[i].paired.abort();
                document.body.children[3].children[i].children[0].setAttribute("style", "transform: rotateY(180deg);");
            }
        clearInterval(timerCode);
        if (code === 0){
            document.body.children[5].children[0].innerHTML = "Time's up!!";
        } else if (code === 1){
            document.body.children[5].children[0].innerHTML = "Out of Tries!!";
        }
        document.body.children[5].setAttribute("style", "display: block;");
        document.getElementById("totalScorePoster").value = points;
        for (let i=1;i<7;i++){
            document.getElementById("level" + i + "ScorePoster").value = pointsPerLevel[i-1];
        }
    }

    function winGame() {
        if (gameLevel === 5){
            document.body.children[5].children[0].innerHTML = "You Win!!";
            document.body.children[5].removeAttribute("style");
            document.body.children[5].setAttribute("style", "display: block;");
            clearInterval(timerCode);
            document.getElementById("totalScorePoster").value = points;
            for (let i=1;i<7;i++){
                document.getElementById("level" + i + "ScorePoster").value = pointsPerLevel[i-1];
            }
            return true;
        } else {
            gameLevel += 1;
            tries += 5;
            document.body.children[3].innerHTML = ""

        }

    }

    function checkIfWon() {
        for (let i=0;i<pairs.length;i++){
            if (document.body.children[3].children[i].children[0].children[1].getAttribute("style") !== "background-color: lightgreen;"){
                return false;
            }
        }
        return true;
    }

    function startTimer(duration) {
        let timer = duration, minutes, seconds;
        timerCode = setInterval(function () {
            minutes = parseInt((timer / 60).toString(), 10);
            seconds = parseInt((timer % 60).toString(), 10);
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10){
                seconds = "0" + seconds;
            }
            document.body.children[2].innerHTML = minutes + ":" + seconds + "<br> Mismatches left: " + tries ;
            if (--timer < 0) {
                timer = 0;
            }
            if (timer === 0 && !checkIfWon()){
                gameover = true;
                loseGame(0);
            }
        }, 1000);
    }


    function addPoints(){
        points += pointsMultiplier;
        tries += 1;
        pointsPerLevel[gameLevel] += pointsMultiplier;
        document.body.children[4].textContent = "POINTS: " + points;
        let highscore = window.sessionStorage.getItem("high-score");
        if (points > highscore){
            window.sessionStorage.setItem("high-score", points);
            document.body.children[3].setAttribute("style", "background-color: #FFD700;");
        }
    }

    function subtractPoints() {
        if (pointsMultiplier !== 0){
            points -= 60000 / pointsMultiplier * 5;
            pointsPerLevel[gameLevel] -= 60000 / pointsMultiplier * 5;
        }
        tries -= 1;
        document.body.children[4].textContent = "POINTS: " + points;
    }




    function resetCards (cards) {
        if (!gameover) {
            window.setTimeout(function () {
            }, 2000);
            for (let i = 0; i < cards.length; i++) {
                document.body.children[3].children[cards[i]].children[0].removeAttribute("style");
            }
        }
    }

    function flipCard(i) {
        if (document.body.children[3].children[i].children[0].getAttribute("style") === "transform: rotateY(180deg);") {
            // CARD IS FLIPPED AND WANTS TO BE RESET
            if (viewedCard1 !== null){
                resetCards(viewedCard3.number);
                viewedCard1 = null;
                if (viewedCard2 !== null){
                    resetCards(viewedCard2.number);
                    viewedCard2 = null;
                    if (viewedCard3 !== null){
                        resetCards(viewedCard3.number);
                        viewedCard3 = null;
                    }
                }

            }
            document.body.children[3].children[i].children[0].removeAttribute("style");
        } else {
            // CARD IS FLIPPED
            document.body.children[3].children[i].children[0].setAttribute("style", "transform: rotateY(180deg);");
            if (viewedCard1 === null) {
                // FLIPPED CARD IS THE FIRST CARD
                viewedCard1 = pairs[i];
            } else if (pairs[i].pairnumber === viewedCard1.pairnumber
                && pairs[i].number !== viewedCard1.number
                && viewedCard2 === null) {
                // FLIPPED CARD IS THE SECOND CARD AND MATCHES
                viewedCard2 = pairs[i];
                if (-1 < gameLevel
                    && gameLevel < 2) {
                    for (let j = 0; j < pairs.length; j++) {
                        if (viewedCard1 !== null
                            && pairs[j].pairnumber === viewedCard1.pairnumber) {
                            pairs[j].paired.abort();
                            document.body.children[3].children[j].children[0].children[1].setAttribute("style", "background-color: lightgreen;");
                            addPoints();
                            if (checkIfWon()) {
                                viewedCard1 = viewedCard2 = null;
                                winGame();
                                if (gameLevel === true) {
                                    gameover = true;
                                } else {
                                    playGame(gameLevel);
                                }

                            }
                        }
                    }
                    viewedCard1 = viewedCard2 = null;
                }


            } else if (viewedCard2 !== null
                && pairs[i].pairnumber === viewedCard1.pairnumber
                && pairs[i].number !== viewedCard1.number
                && pairs[i].number !== viewedCard2.number
                && viewedCard3 === null) {
                // FLIPPED CARD IS THE SECOND CARD AND MATCHES
                viewedCard3 = pairs[i];
                if (1 < gameLevel
                    && gameLevel < 4) {
                    for (let j = 0; j < pairs.length; j++) {
                        if (pairs[j].pairnumber === viewedCard1.pairnumber) {
                            pairs[j].paired.abort();
                            document.body.children[3].children[j].children[0].children[1].setAttribute("style", "background-color: lightgreen;");
                            addPoints();
                            if (checkIfWon()) {
                                viewedCard1 = viewedCard2 = viewedCard3 = null;
                                winGame();
                                if (gameLevel === true) {
                                    gameover = true;
                                } else {
                                    playGame(gameLevel);
                                }

                            }
                        }
                    }
                    viewedCard1 = viewedCard2 = viewedCard3 = null;
                }
            } else if (viewedCard3 !== null
                && pairs[i].pairnumber === viewedCard1.pairnumber
                && pairs[i].number !== viewedCard1.number
                && pairs[i].number !== viewedCard2.number
                && pairs[i].number !== viewedCard3.number) {
                if (3 < gameLevel
                    && gameLevel < 6) {
                    for (let j = 0; j < pairs.length; j++) {
                        if (pairs[j].pairnumber === viewedCard1.pairnumber) {
                            pairs[j].paired.abort();
                            document.body.children[3].children[j].children[0].children[1].setAttribute("style", "background-color: lightgreen;");
                            addPoints();
                            if (checkIfWon()) {
                                winGame();
                                if (gameLevel === true) {
                                    gameover = true;
                                } else {
                                    playGame(gameLevel);
                                }

                            }
                        }
                    }
                    viewedCard1 = viewedCard2 = viewedCard3 = null;
                }
            } else {
                subtractPoints();
                if (tries === 0) {
                    gameover = true;
                    loseGame(1);
                }
                if (!gameover) {
                    document.body.children[3].children[i].children[0].setAttribute("style", "transform: rotateY(180deg);");
                    if (viewedCard2 === null) {
                        for (let j=0;j<pairs.length;j++) {
                            if (viewedCard1.number === pairs[j].number) {
                                setTimeout(function () {
                                    resetCards([j,i]);
                                }, 1000);
                            }
                        }
                    } else if (viewedCard3 === null) {
                        for (let j = 0; j < pairs.length; j++) {
                            if (viewedCard1.number === pairs[j].number) {
                                for (let k=0;k<pairs.length;k++) {
                                    if (viewedCard2.number === pairs[k].number) {
                                        setTimeout(function () {
                                            resetCards([k,j,i]);
                                        }, 1000);
                                        break;
                                    }
                                }
                                break;
                            }

                        }
                    } else if (viewedCard3 !== null) {
                        for (let j=0;j<pairs.length;j++) {
                            if (viewedCard1.number === pairs[j].number) {
                                for (let k=0;k<pairs.length;k++) {
                                    if (viewedCard2.number === pairs[k].number) {
                                        for (let l=0;l<pairs.length;l++) {
                                            if (viewedCard3.number === pairs[l].number) {
                                                setTimeout(function () {
                                                    resetCards([l,k,j,i]);
                                                }, 1000);
                                                break;
                                            }
                                        }
                                        break;
                                    }
                                }
                                break;
                            }
                        }
                    }
                    viewedCard1 = viewedCard2 = viewedCard3 = null;
                }
            }
        }
    }


    function makeRandomFace(){
        const skinNumber = Math.floor(Math.random() * skins.length);
        let faceString = skins[skinNumber];
        const eyeNumber = Math.floor(Math.random() * eyes.length);
        faceString = faceString.concat(eyes[eyeNumber]);
        const mouthNumber = Math.floor(Math.random() * mouths.length);
        faceString = faceString.concat(mouths[mouthNumber]);
        return faceString;
    }

    function makeCards(cardCount, matchNumber) {
        console.log("MAKING CARDS");
        let cardFront = document.createElement("div");
        cardFront.className = "card-front";
        let cardBack = document.createElement("div");
        cardBack.className = "card-back";
        let cardContainer = document.createElement("div");
        cardContainer.className = "card-inner";
        pairs = [];
        for (let i=0;i<cardCount;i+=matchNumber){
            let faceString = "";
            do {
                faceString = makeRandomFace();
            } while (usedFaces.includes(faceString));
            usedFaces.push(faceString);
            for (let j=0;j<matchNumber;j++) {
                pairs[i + j] = (new Card(i + j, faceString, matchNumber));
            }
        }

        pairs.sort(() => Math.random() - 0.5);

        for (let i=0;i<pairs.length;i++){
            let currentCard = document.createElement("div");
            currentCard.className = "card";
            currentCard.id= "card".concat(i.toString());
            document.body.children[3].appendChild(currentCard);
            document.body.children[3].children[i].appendChild(cardContainer.cloneNode());
            document.body.children[3].children[i].children[0].appendChild(cardFront.cloneNode());
            document.body.children[3].children[i].children[0].appendChild(cardBack.cloneNode());
        }
    }

</script>
</html>