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
        /* To Do: 
         * do various other submission checks like validate phone number format
         * 
         */
    
   
                
    $crewid = $_POST["crewid"];
   
    // if the id is null then add the crew member, otherwise edit the crew member based on id
    $result = PlaceOnDNC($crewid);
    
    // get all the crews on the dnc list
    //$result = getDNC();
    // show dnc list 
    //render("dnc-form.php", ["title" => "DNC","crews" => $crews]);
        
    }
    
   

    else {
        $crews = getDNC();
        render("dnc-form.php", ["title" => "DNC","crews" => $crews]);
    }