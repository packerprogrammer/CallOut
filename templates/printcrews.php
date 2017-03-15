<script type="text/javascript" src="includes/DataTables/jQuery-2.2.3/jquery-2.2.3.js"></script>
<link rel="stylesheet" type="text/css" href="includes/DataTables/datatables.min.css"/>
<script type="text/javascript" src="includes/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="includes/DataTables/Buttons-1.2.1/js/dataTables.buttons.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">


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
    var editor;
$(document).ready(function() {
       
    var t = $('#crews').DataTable( {
        //"ajax": "../includes/modifycrew.php"
        dom: 'Bfrtip',
        "columns": [
            { "visible": false },
            null,
            null,
            null,
            null,
            null
        ],
        "select": true,
        
        "order": [[ 5, 'asc' ]],
        "columnDefs": [
                {className: "dt-body-center", "targets": [5]}],
        "ordering": false,
        "buttons": [
            {
                text: 'Add',
                action: function ( e, dt, node, config ) {
                    document.getElementById('id').value = null;
                    document.getElementById('firstname').value = "";              
                    document.getElementById('lastname').value = "";              
                    document.getElementById('phone').value = "";              
                    document.getElementById('role').value=1;
                    document.getElementById('submitcrew').innerHTML = "Add Crew Member"
                    showpopup();
                }
            },
            {
                text: "Edit",
                action: function ( e, dt, node, config ) {
                    //alert( 'Edit Button');
                    var crewcall = t.row('.selected').data();
                    document.getElementById('id').value = crewcall[0];
                    document.getElementById('firstname').value = crewcall[1];              
                    document.getElementById('lastname').value = crewcall[2];
                    document.getElementById('phone').value = crewcall[3];
                    document.getElementById('role').value=1;
                    document.getElementById('submitcrew').innerHTML = "Edit Crew Member"
                    showpopup();
                }
            },
            {
                text: "Delete",
                action: function ( e, dt, node, config ) {
                    var crewcall = t.row('.selected').data();
                    $.ajax({
                        url: '/callout/deleteCrew.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            crewid: crewcall[0]
                        }
                    });
                    t.row('.selected').remove().draw( false );
                    location.reload();
                }
            },
            {
                text: "No Not Call",
                action: function ( e, dt, node, config ) {
                     // grab the data from the selected row
                    window.alert("howdy");
                    var crewcall = t.row('.selected').data();
                    // place that person on the dnc list by id number
                    $.ajax({
                        url: '/callout/doNotCall.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            crewid: crewcall[0]
                        }
                    });
                    // remove from the table
                    // t.row('.selected').remove().draw( false );
                    // reload the page
                    location.reload();
                    
                }
            }
            
        ]
    });
    var counter = 1;
    
//    var d = document.getElementById("deletecrew");
//    d.onclick = function() {
//        $('#btnDelete').click();
//        return false;
//    }
//    
//    var e = document.getElementById("editcrew");
//    e.onclick = function() {
//        $('#btnEdit').click();
//        return false;
//    }
    
    $('#crews tbody').on( 'click', 'tr', function () {
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
    $('#btnCallSelected').on( 'click', function () {
        var crewcall = t.row('.selected').data();
        var container = document.getElementById("response");
        while (container.hasChildNodes()) {
            container.removeChild(container.lastChild);
        }
        
        // store the id number of the crew for later use
        var txtID = document.createElement('input');
        txtID.id = 'crewid';
        txtID.type = 'hidden';
        txtID.name = 'crewid';
        txtID.value = crewcall[0];
        
        // create a prompt asking the user if the crew member will be coming in
        var labelPrompt = document.createElement('label');
        var strPrompt = '<h3>Please enter the employee&apos;s response below:</h3><h3>Is ' + crewcall[1] + ' coming in?</h3>';
        labelPrompt.innerHTML = strPrompt;
        
        // create a radio button for a yes response
        var radio1 = document.createElement('input');
        radio1.id = 'ResponseYes';
        radio1.type = 'radio';
        radio1.name = 'rdoResponse';
        radio1.className = 'radio3';
        radio1.value = '1';
            
        // create a radio button for a no response    
        var radio2 = document.createElement('input');
        radio2.id = 'ResponseNo';
        radio2.type = 'radio';
        radio2.name = 'rdoResponse';
        radio2.className = 'radio3';
        radio2.value = '0';
        
        // create a label for the yes radio button        
        var label1 = document.createElement('label');
        label1.setAttribute('for', radio1.id);
        label1.className = 'response';
        label1.innerHTML = 'Yes';

        // create a label for the no radio button
        var label2 = document.createElement('label');
        label2.setAttribute('for', radio2.id);
        label2.className = 'response';
        label2.innerHTML = 'No';
            
        // create a line break for looks
        var mybr = document.createElement('br');    
        
        // add all the elements above to the container
        container.appendChild(txtID);
        container.appendChild(mybr);   
        container.appendChild(labelPrompt);
        container.appendChild(radio1);
        container.appendChild(label1);
        mybr = document.createElement('br');
        container.appendChild(mybr);
        container.appendChild(radio2);
        container.appendChild(label2);
        
        // scroll to the bottom
        document.getElementById('bottom').scrollIntoView();
            
    });
    
    $('#response').on( 'click', function () {
        // get the container element to draw the new elements onto
            var container = document.getElementById("reason");
            
            //clear the div
            if (container != null) {
                while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
                }
            }
        if(document.getElementById("ResponseNo").checked) {
                       
            var strPrompt = '<h3>Why Not?</h3>';
            var labelPrompt = document.createElement('label');
            labelPrompt.innerHTML = strPrompt;
            container.appendChild(labelPrompt);
            
            
            
            var dropbox1 = document.createElement('select');
            dropbox1.id = 'ReasonCode';
            dropbox1.type = 'select';
            dropbox1.name = 'reason';
            container.appendChild(dropbox1);
            
            var reasons= <?php echo json_encode($reasons); ?>;
            var i;
            var reason;
            var select = document.getElementById('ReasonCode');
            for (i in reasons) {
                var opt = document.createElement('option');
                opt.value = reasons[i].id;
                opt.innerHTML = reasons[i].id + "-" + reasons[i].reason;
                select.appendChild(opt);
            }
            
            // create some whitespace
            mybr = document.createElement('br');
            container.appendChild(mybr);
            
        };
         // line break for clarity
            var mybr = document.createElement('br');
            container.appendChild(mybr);
            // throw up a submit button
            var btnSubmit = document.createElement('button');
            //btnSubmit.type = 'submit';
            btnSubmit.id = 'submit';
            btnSubmit.innerHTML = 'Submit';
            container.appendChild(btnSubmit);
            document.getElementById('submit').scrollIntoView();
    });
    $('#btnDelete').click( function () {
        var crewcall = t.row('.selected').data();
        $.ajax({
            url: '/callout/deleteCrew.php',
            type: 'POST',
            dataType: 'json',
            data: {
                crewid: crewcall[0]
            }
        });
        t.row('.selected').remove().draw( false );
    } );
    
    $('#btnEdit').click(function () {
        window.alert('you pressed me');
        var crewcall = t.row('.selected').data();
        $.ajax({
            url: '/callout/editCrew.php',
            type: 'POST',
            dataType: 'json',
            data: {
                crewid: crewcall
            }
                
        });
        window.alert('still here');
    });
    $('#show_login').click(function(){
        showpopup();
    });
    $('#close_login').click(function(){
        event.preventDefault();
        hidepopup();
        
    });
    
    $('#phone').mask("(999)-999-9999");
    
    
} );
    function showpopup()
    {
        $("#addcrewform").fadeIn();
        $("#addcrewform").css({"visibility":"visible","display":"block"});
    }

    function hidepopup()
    {
        $("#addcrewform").fadeOut();
        //$("#loginform").css({"visibility":"hidden","display":"none"});
        
    }
</script>
<!-- This form is for adding a new crew member, it will be displayed when the "Add" button is clicked on the datatables table
     The javascript event will make the form appear. It will submit to addCrew.php which will add the crew to the database
     and redirect home -->
 <div id = "addcrewform">
  <form action="addCrew.php" method="post">
      <p>Enter crew member information:</p>
    <fieldset>
        <div class="control-group">
            <input type="hidden" name="id" id="id"/>
        </div>
        <div class="control-group">
            
            <input autofocus id="firstname" name="firstname" placeholder="First Name" type="text"/>
        </div>
        <div class="control-group">
            
            <input id="lastname" name="lastname" placeholder="Last Name" type="text"/>
        </div>
        <div class="control-group">
            
            <input id="phone" name="phone" placeholder="Phone Number" type="tel"/>
        </div>
        <div class="control-group">
            <select id="role" name = "role" required>
                <option value = 1>Lineman</option>
                <option value = 2>Apprentice</option>
                <option value = 3>Groundman</option>
            </select>
            
        </div>
        <div class="control-group">
           
            <input id="crewid" name="crew" placeholder="Future Use, Leave Blank" type="text"/>
        </div>
        <div class="control-group">
            <button id="submitcrew" type="submit" class="btn">Add Crew Member</button>
        </div>
    </fieldset>
   <div>
   <input type = "image" id = "close_login" src = "img/close.png">
   </div>
  </form>
 </div>

<!-- This form is for editing a new crew member, it will be displayed when the "Edit" button is clicked on the datatables table
     The javascript event will make the form appear. It will submit to editCrew.php which will modify the crew in the database
     and redirect home. It's fields will be populated by default with the values from the row selected. -->

<div id="crew"> 
 
<table id="crews" class="display" cellspacing="0" width="100%">

    <thead>
    
        <tr>
            <th>ID</th>    
            <th>First Name</th>
            <th>Last Name</th>
            <th>Phone Number</th>
            <th>Role</th>
            <th>Call Order</th>
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
            $callorder = $crew["z_order"] + 1;
	    print("<tr>");
            print("<td>{$crew["id"]}</td>");
            print("<td>{$crew["firstname"]}</td>");
            print("<td>{$crew["lastname"]}</td>");
            print("<td>({$crew["area_code"]})");
            print("{$phone}</td>");
            print("<td>{$crew["role"]}</td>");
            print("<td>{$callorder}</td>");	    		
	    print("</tr>");
	    
            $count = $count + 1;
        }
	
       
    ?>
    </tbody>
</table>


</div>
<div id="toolbar">
<button id="btnCallSelected">Call Selected</button>
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
