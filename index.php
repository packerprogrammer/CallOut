

<?php
    
    //configuration
    require ("includes/config.php");
    //require_once("templates/header.php");
    $sortby = "z_order";
    $crewquery = "";
    $reasonquery = "";
            
    $crews = getCrewMembers(["query" => $crewquery,"sortby" => $sortby]);
    $reasons = getReasonCodes(["query" => $reasonquery]);
    render("printcrews.php", ["title" => "Search","crews" => $crews,"reasons" => $reasons]);
    

?>

