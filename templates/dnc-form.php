<div id="dnc"> 
 
<table id="dnc" class="display" cellspacing="0" width="100%">

    <thead>
    
        <tr>
            <th>ID</th>    
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone Number</th>
            <th>Role</th>
        </tr>
   
    </thead>

    <tbody>
    <?php
        $count = 0;
        
        foreach ($crews as $crew) 
        {
            
            $area = $crew["area_code"];
            $first = substr($crew["phone_number"],0,3);
            $phone = " " . $first . "-" . substr($crew["phone_number"],3,4);
            
	    print("<tr>");
            print("<td>{$crew["id"]}</td>");
            print("<td>{$crew["firstname"]}</td>");
            print("<td>{$crew["lastname"]}</td>");
            print("<td>({$crew["area_code"]})");
            print("{$phone}</td>");
            print("<td>{$crew["role"]}</td>");	    		
	    print("</tr>");
	    
            $count = $count + 1;
        }
	
       
    ?>
    </tbody>
</table>
