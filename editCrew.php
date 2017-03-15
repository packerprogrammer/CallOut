<?php

    //configuration
    require ("includes/config.php");
    echo 'soup de soup';
    // if form was submitted
    // check to see if the form was submitted from the editCrew-form.php
    // if so then the editcrew button should have been set
    if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["editcrew"])) {
        // validate submission
        if (empty($_POST["firstname"])or empty($_POST["lastname"]))
        {
            apologize("You must provide a name.");
        }
        /* To Do: 
         * do various other submission checks like validate phone number format
         * 
         */
        
    
        echo "howdy";
        // if so, then process the data from the form and update the database
        $myCrew = [
        "firstname" => $_POST["firstname"],
        "lastname" => $_POST["lastname"],
        "phone" => $_POST["phone"],
        "role" => $_POST["role"]
        ];

        // add the crew memeber
        $result = updateCrewMember($myCrew);

        //require_once("templates/header.php");
        $sortby = "z_order";
        $crewquery = "";
        $reasonquery = "";

        $crews = getCrewMembers(["query" => $crewquery,"sortby" => $sortby]);
        $reasons = getReasonCodes(["query" => $reasonquery]);
        render("editCrew-form.php", ["title" => "Search","crews" => $crews,"reasons" => $reasons]);

    }
    else {
        // it must have been submitted from the printcrew.php template, so we need to 
        // render the editCrew-form.php and pass it the values from the table.
        
        // bring the post data in and give it a name
        //$id = $_POST[0];
        //$firstname = $_POST[1];
        //$lastname = $_POST[2];
        //$phone = $_POST[3];
        //$role = $_POST[4];
        //echo $_POST[];
        echo "hello world";
        //render("editCrew-form.php", ["title" => "Edit Crew","id" => $id,"firstname" => $firstname, "lastname" => $lastname,
        //    "phone" => $phone, "role" => $role]);
    }
    