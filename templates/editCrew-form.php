
<form action="editCrew.php" method="post">
    <fieldset>
        <div class="control-group">
            
            <input autofocus name="firstname" placeholder=<?php echo $firstname?> type="text"/>
        </div>
        <div class="control-group">
            
            <input name="lastname" placeholder="Last Name" type="text"/>
        </div>
        <div class="control-group">
            
            <input name="phone" placeholder="Phone Number" type="tel"/>
        </div>
        <div class="control-group">
            <select name = "role" required>
                <option value = 1>Lineman</option>
                <option value = 2>Apprentice</option>
                <option value = 3>Groundman</option>
            </select>
            
        </div>
        <div class="control-group">
           
            <input name="crew" placeholder="Future Use, Leave Blank" type="text"/>
        </div>
        <div class="control-group">
            <button type="submit" class="btn" name="editcrew">Edit Crew Member</button>
        </div>
    </fieldset>
</form>
<?php


