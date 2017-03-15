

<?php
    
    //configuration
    require ("includes/config.php");
    //require_once("templates/header.php");
    
            
    $calls = getCalls();
    
    render("printcalllog.php", ["title" => "Reports","call_log" => $calls]);
    

?>

