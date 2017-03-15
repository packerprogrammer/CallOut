<?php

    //configuration
    require ("includes/config.php");
    
    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // validate submission
        if (empty($_POST["firstname"])or empty($_POST["lastname"]))
        {
            apologize("You must provide a name.");
        }
        /* To Do: 
         * do various other submission checks like validate phone number format
         * 
         */
    
   
                
    $myCrew = [
        "id" => $_POST["id"],
        "firstname" => $_POST["firstname"],
        "lastname" => $_POST["lastname"],
        "phone" => $_POST["phone"],
        "role" => $_POST["role"]
    ];
    
    // if the id is null then add the crew member, otherwise edit the crew member based on id
    if (empty($_POST["id"])) {
        // add the crew member
        $result = addCrewMember($myCrew);
       
    }
    else {
        // edit the crew member
        $result = editCrewMember($myCrew);
        
    }
    
    // redirect to home
    redirect("index.php");
//    //require_once("templates/header.php");
//    $sortby = "z_order";
//    $crewquery = "";
//    $reasonquery = "";
//            
//    $crews = getCrewMembers(["query" => $crewquery,"sortby" => $sortby]);
//    $reasons = getReasonCodes(["query" => $reasonquery]);
//    render("printcrews.php", ["title" => "Search","crews" => $crews,"reasons" => $reasons]);
    }
    else {
        render("addCrew-form.php", ["title" => "Add Crew"]);
    }