<?php

    /***********************************************************************
     * functions.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Helper functions.
     **********************************************************************/

    require_once("constants.php");

    /**
     * Apologizes to user with message.
     */
    function apologize($message)
    {
        //render("apology.php", ["message" => $message]);
        require("../templates/apology.php");
        exit;
    }

    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
    function dump($variable)
    {
        require("../templates/dump.php");
        exit;
    }

    /**
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */
    function logout()
    {
        // unset any session variables
        $_SESSION = array();

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }

    /**
     * Returns a stock by symbol (case-insensitively) else false if not found.
     */
    function lookup($symbol)
    {
        // reject symbols that start with ^
        if (preg_match("/^\^/", $symbol))
        {
            return false;
        }

        // reject symbols that contain commas
        if (preg_match("/,/", $symbol))
        {
            return false;
        }

        // open connection to Yahoo
        $handle = @fopen("http://download.finance.yahoo.com/d/quotes.csv?f=snl1&s=$symbol", "r");
        if ($handle === false)
        {
            // trigger (big, orange) error
            trigger_error("Could not connect to Yahoo!", E_USER_ERROR);
            exit;
        }

        // download first line of CSV file
        $data = fgetcsv($handle);
        if ($data === false || count($data) == 1)
        {
            return false;
        }

        // close connection to Yahoo
        fclose($handle);

        // ensure symbol was found
        if ($data[2] === "0.00")
        {
            return false;
        }

        // return stock as an associative array
        return [
            "symbol" => $data[0],
            "name" => $data[1],
            "price" => $data[2],
        ];
    }

    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
    function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);

        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

    /**
     * Renders template, passing in values.
     */
    function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("templates/$template"))
        {
            // extract variables into local scope
            extract($values);

            // render header
            require("templates/header.php");
            
            // render menu
            // require("templates/menu.php");          

            // render template
            require("templates/$template");

            // render footer
            require("templates/footer.php");
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }
        
     /*
     *   Adds a record to the tranaction log in sql database
     */
     function addLog($id,$type,$symbol,$shares,$price)
     {
         $result = query("INSERT INTO translog (id,type,symbol,shares,price) VALUES (?,?,?,?,?)",$id,$type,$symbol,$shares,$price);
         if ($result === false)
         {
             apologize("Failed to insert log values");
         }
     } 
/*
    *   takes the id of the current session and returns the users
    *   portfolio as an associative array
    */
    function getCrewMembers($search = [])
    {
	$str=null;
	extract($search);
	$ordr = $search["sortby"];
	if (empty($search["query"])) 
	{
		//apologize("bla");
        	// get the portfolio data
        	$result = query("SELECT a.id, a.firstname, a.lastname, b.desc, a.area_code, a.phone_number, a.z_order, a.standby, a.dnc FROM callout.crew_member a join crew_type b where a.crew_type = b.id and a.dnc = 0 order by z_order");
        	if ($result === false)
        	{
            	    apologize("Could not get portfolio data");
        	}   
        }
	else
	{
	$str = $search["query"];
	
        if ($result === false)
        {
            apologize("Could not get portfolio data");
        }   
	}
        
        // add the result to an associate array
        foreach ($result as $row)
        {
            {
                $results[] = [
                    "id" => $row["id"],
                    "firstname" => $row["firstname"],
                    "lastname"  => $row["lastname"],
		    "role"  => $row["desc"],
                    "area_code" => $row["area_code"],
                    "phone_number" => $row["phone_number"],
                    "z_order" => $row["z_order"],
                    "standby" => $row["standby"]
                ];
            }
        }
        
        
        // return the associate array containing the portfolio and users cash
        return $results;
     }
     
    function addCall($id, $response, $reason) 
    {
        $result = query("INSERT into call_log (crewid,response,reason) VALUES (?,?,?)",$id,$response,$reason);
        if ($result === false)
            {
                apologize("Could not add the call log.");
            }   
        // reorder the crew_memebers, move the provided crew member to the bottom and shift all other up 1    
        $result = query("CALL sp_reordercrews(?)",$id);
       
    }
     
     function getReasonCodes($search = [])
    {
	$str=null;
	extract($search);
	//$ordr = $search["sortby"];
	if (empty($search["query"])) 
	{
		//apologize("bla");
        	// get the portfolio data
        	$result = query("SELECT * FROM reason_codes where id <> 0 order by id");
        	if ($result === false)
        	{
            	    apologize("Could not get reason codes");
        	}   
        }
	else
	{
	$str = $search["query"];
	
	// get the portfolio
        if ($result === false)
        {
            apologize("Could not get portfolio data");
        }   
	}
        // add the result to an associate array
        foreach ($result as $row)
        {
            //$stock = lookup($row["symbol"]);
            //if ($stock !== false)
            {
                $results[] = [
                    "id" => $row["id"],
                    "reason"  => $row["reason"]
                ];
            }
        }
        
        
        // return the associate array containing the portfolio and users cash
        return $results;
     }
    
    function getCalls()
    {
	$str=null;
	//apologize("bla");
       	// get the portfolio data
       	$result = query("SELECT L.idcall_log, C.firstname, C.lastname, L.response, R.reason, L.call_time 
            FROM call_log L
                left join crew_member C on C.id = L.crewid
                left join reason_codes R on L.reason = R.id
            order by L.call_time desc");
        
       	if ($result === false)
       	{
            apologize("Could not get call data");
        }   
        
	
        // add the result to an associate array
        foreach ($result as $row)
        {
            {
                if ($row["response"] == 0)
                {    
                    $response = 'no';
                }
                else
                {
                    $response = 'yes';
                }
                $results[] = [
                    "id" => $row["idcall_log"],
                    "firstname" => $row["firstname"],
                    "lastname"  => $row["lastname"],
		    "response"  => $response,
                    "reason" => $row["reason"],
                    "call_time" => $row["call_time"]    
                ];
            }
        }
        
        
        // return the associate array containing the portfolio and users cash
        return $results;
     }
    function addCrewMember($crewmember) {
        extract($crewmember);
         
        $areacode = substr($phone,1,3);
        $phone = substr($phone,6,3) . substr($phone, 10,4);
        //echo $areacode;
        $result = query("insert into callout.crew_member (firstname,lastname,area_code,phone_number,crew_type) VALUES (?,?,?,?,?)",$firstname,$lastname,$areacode,$phone,$role);
        return $result;
     }
    function deleteCrewMember($crewid) {
        // reorder the crew_memebers, move the provided crew member to the bottom and shift all other up 1    
        // this is done before the delete so that there are no gaps in the callout order
        $result = query("CALL sp_reordercrews(?)",$crewid);
        $result = query("delete from callout.crew_member where id = ?",$crewid);
        return $result;
     }
    function editCrewMember($crewmember) {
         extract($crewmember);
         //echo $firstname;
        //echo $lastname;
         //echo $phone;
         //echo $role;
         
        $areacode = substr($phone,1,3);
        $phone =   substr($phone,6,3) .  substr($phone, 10,4);
         
        $result = query("update crew_member SET firstname = ?, lastname = ?, area_code = ?, phone_number = ?, crew_type = ? WHERE id=?",$firstname,$lastname,$areacode,$phone,$role,$id);
    }
    function PlaceOnDNC($crewid) {
        // reorder the crew_memebers, move the provided crew member to the bottom and shift all other up 1    
        // this is done before the UPDATE so that there are no gaps in the callout order 
        $result = query("CALL sp_reordercrews(?)",$crewid);
        $result = query("UPDATE crew_member SET dnc = 1 WHERE id=?",$crewid);
    }
    function getDNC() {
        $results = null;
        $result = query("SELECT a.id, a.firstname, a.lastname, b.desc, a.area_code, a.phone_number, a.z_order, a.standby, a.dnc FROM callout.crew_member a join crew_type b where a.crew_type = b.id and a.dnc = 1 order by z_order");
            if ($result === false)
            {
                apologize("Noone is on the Do Not Call list.");
            }
         // add the result to an associate array
        foreach ($result as $row)
        {
            {
                $results[] = [
                    "id" => $row["id"],
                    "firstname" => $row["firstname"],
                    "lastname"  => $row["lastname"],
		    "role"  => $row["desc"],
                    "area_code" => $row["area_code"],
                    "phone_number" => $row["phone_number"]
                     ];
            }
        }    
        return $results;    
            
    }
?>
