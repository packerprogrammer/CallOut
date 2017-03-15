<?php

    //configuration
    require ("includes/config.php");
    
    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // validate submission
        if (empty($_POST["crewid"]))
        {
            apologize("You must provide an id.");
        }
                
    $crewid = $_POST["crewid"];
    
    // add the crew memeber
    $result = deleteCrewMember($crewid);
    
    //require_once("templates/header.php");
    $sortby = "z_order";
    $crewquery = "";
    $reasonquery = "";
            
    $crews = getCrewMembers(["query" => $crewquery,"sortby" => $sortby]);
    $reasons = getReasonCodes(["query" => $reasonquery]);
    render("printcrews.php", ["title" => "Search","crews" => $crews,"reasons" => $reasons]);
    }
    else {
        // render("deleteCrew-form.php", ["title" => "Add Crew"]);
    }