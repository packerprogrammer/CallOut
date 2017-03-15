<script type="text/javascript" src="includes/DataTables/jQuery-2.2.3/jquery-2.2.3.js"></script>
<link rel="stylesheet" type="text/css" href="includes/DataTables/datatables.min.css"/>
<script type="text/javascript" src="includes/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="includes/DataTables/Buttons-1.2.1/js/dataTables.buttons.min.js"></script>
<?php
    if (!empty($name))
    {
        print("<h3>Hello, {$name}</h3>");
    }
    if (empty($dir))
    {
	$dir = "ASC";
    }	
    
    	
?>
<script language='javascript'>
$(document).ready(function() {
    var t = $('#call_log').DataTable( {
        "columns": [
            { "visible": false },
            null,
            null,
            null,
            null,
            null
        ],
        "order": [[ 5, 'desc' ]],
        "columnDefs": [
                {className: "dt-body-center", "targets": [5]}]
        
    });
    var counter = 1;
    
    
    $('#call_log tbody').on( 'click', 'tr', function () {
        if ($(this).hasClass('selected') ) {
            $(this).removeClass('selectd');                
        }
        else {
            t.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        var d = t.row( this ).data();
        console.log( d );
        console.log( d[0]);
    });
    
    
       
} );
</script>

<div id="calls"> 
 
<table id="call_log" class="display" cellspacing="0" width="100%">

    <thead>
    
        <tr>
            <th>ID</th>    
            <th>First Name</th>
            <th>Last Name</th>
            <th>Response</th>
            <th>Reason</th>
            <th>Call Time</th>
        </tr>
   
    </thead>

    <tbody>
    <?php
        $count = 0;
        
        foreach ($call_log as $call) 
        {
            
            
	    print("<tr>");
            print("<td>{$call["id"]}</td>");
            print("<td>{$call["firstname"]}</td>");
            print("<td>{$call["lastname"]}</td>");
            print("<td>{$call["response"]}");
            print("<td>{$call["reason"]}</td>");
            print("<td>{$call["call_time"]}</td>");	    		
	    print("</tr>");
	    
            $count = $count + 1;
        }
	
       
    ?>
    </tbody>
</table>


</div>

<form action="./addCall.php" method="get">
    <fieldset>
        
        <div id="response">

        </div>
        <div id="reason">
    
        </div>
    </fieldset>
</form>



<?php
	//print("{$count} rows returned");
?>
