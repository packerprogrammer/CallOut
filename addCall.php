<html>
    <head>
        <meta http-equiv="refresh" content="2;url=../callout" />
    </head>
    <body>
        <h1>Logging phone call. Please wait...</h1>
    </body>
</html>

<?php

//configuration -addfffddddddddd
require ("includes/config.php");

if (isset($_GET["rdoResponse"]))
{
    $response = $_GET["rdoResponse"];
}
if (isset($_GET["reason"]))
{
    $reason = $_GET["reason"];
}
else
{
    $reason = 0;
}
if (isset($_GET["crewid"]))
{
    $ID = $_GET["crewid"];
}

$result = addCall($ID,$response,$reason);

?>
