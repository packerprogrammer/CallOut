<!DOCTYPE html>

<html>

    <head>

        <link href="css/bootstrap.css" rel="stylesheet"/>
        <link href="css/bootstrap-responsive.css" rel="stylesheet"/>
        <link href="css/styles.css" rel="stylesheet"/>

        <?php if (isset($title)): ?>
            <title>Crew Call Out: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>Call Out</title>
        <?php endif ?>

        <script src="js/jquery-1.8.2.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/scripts.js"></script>

    </head>

    <body>
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="../callout">Home</a>
            <a href="./reports.php">Reports</a>
            <a href="#">About</a>
        </div>
        <div id="main">
            <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
        </div>

        <script>
            function openNav() {
                document.getElementById("mySidenav").style.width = "250px";
            }

            function closeNav() {
                document.getElementById("mySidenav").style.width = "0";
            }
        </script>

        <div class="container">

            <div id="top">
                <a href="/callout"><img alt="Call Out Logo" src="img/logo2.jpg"/></a>
            </div>
            
        <div id="middle">
           

