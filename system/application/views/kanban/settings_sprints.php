
<h3>Sprints</h3>

<form id="newsprint" name="newsprint" action="">
					<input type="hidden" id="newsprint_projectid" name="newsprint_projectid" value="<?php echo $projectid; ?>" />
					<table >
                      <tr>
                        <td><h4>Add New Sprint :</h4></td>
                      </tr>
                      <tr>
                        <td class="settingsleftside">Name:</td>
                        <td class="settingsrightside"><input id="newsprint_name" name="newsprint_name" value="any name" /></td>
                      </tr>
                      <tr>
                        <td class="settingsleftside">Start:</td>
                        <td class="settingsrightside"><input id="newsprint_startdate"  name="newsprint_startdate" value="2010-11-01" /></td>
                      </tr>
                      <tr>
                        <td class="settingsleftside">End:</td>
                        <td class="settingsrightside"><input id="newsprint_enddate"  name="newsprint_enddate" value="2010-12-01" /></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td class="settingsleftside"><input type="submit"  value="Add"  /></td>
                      </tr>
                    </table>
					</form>
					<br>
					<hr>
					<form id="editsprint" name="editsprint" action="">
					<input type="hidden" id="editsprint_projectid" name="editsprint_projectid" value="<?php echo $projectid; ?>" />
					<table >					
						<tr><td><h4>Edit Sprint :</h4></td></tr>
						<tr><td class="settingsleftside">Select : </td><td class="settingsrightside"><select name="editsprint_sprintid" id="editsprint_sprintid" onChange="fillInSprintDetails(this.selectedIndex)">
							<option value="0">Select Sprint</option>
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
						</td></tr>
						<tr><td class="settingsleftside">Name:</td><td class="settingsrightside"><input id="editsprint_name" name="editsprint_name" value="any name" /></td></tr>
						<tr><td class="settingsleftside">Start:</td><td class="settingsrightside"><input id="editsprint_startdate" name="editsprint_startdate" value="2010-11-01" /></td></tr>
						<tr><td class="settingsleftside">End:</td><td class="settingsrightside"><input id="editsprint_enddate" name="editsprint_enddate" value="2010-12-01" /></td></tr>
						<tr><td class="settingsleftside"></td><td class="settingsleftside"><input type="submit"  value="Update"  /></td></tr>
					</table>
					</form>
					<br>
					<hr>
					
					<form id="deletesprint" name="deletesprint" action="">
					<input type="hidden" id="deletesprint_projectid" name="deletesprint_projectid" value="<?php echo $projectid; ?>" />					
					<table>					
					<tr><td><h4>Delete Sprint :</h4></td></tr>
						<tr>
						  <td class="settingsleftside">Select:</td>
						  <td class="settingsrightside"><select name="deletesprint_sprintid" id="deletesprint_sprintid">
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
							</td></tr>
						<tr><td colspan=2>Note! All Tasks for this sprint will be deleted !!!.</td></tr>
						<tr><td></td><td class="settingsleftside"><input type="submit"  value="Delete"  /></td></tr>
					</table>
					</form>
					<br>
					<hr>
					<form id="movetasksbetweensprints" name="movetasksbetweensprints" action="">
					<input type="hidden" id="movetasksbetweensprints_projectid" name="movetasksbetweensprints_projectid" value="<?php echo $projectid; ?>" />					
					<input type="hidden" id="movetasksbetweensprints_lastgroupid" name="movetasksbetweensprints_lastgroupid" value="<?php echo $groups[count($groups)-1]['id']; ?>" />
					<table>					
					<tr><td><h4>Move Tasks Between Sprints :</h4></td></tr>
						<tr>
						  <td class="settingsleftside">From :</td>
						  <td class="settingsrightside"><select name="movetasksbetweensprints_from" id="movetasksbetweensprints_from">
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
							</td></tr>
						 <tr>
							<td class="settingsleftside">To :</td>
							<td class="settingsrightside"><select name="movetasksbetweensprints_to" id="movetasksbetweensprints_to">
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
							</td></tr>
						<tr><td colspan=2></td></tr>
						<tr><td></td><td class="settingsleftside"><input type="submit"  value="Move"  /></td></tr>
					</table>
					</form>
					<br>
					<hr>
					<table>					
						<tr><td class="settingsrightside"><h4>Select Sprint  :</h4></td></tr>
					  <tr><td></td><td class="settingsrightside">
					  	<table class="sprintlist">
							<tr><td>Name</td><td>Start</td><td>End</td></tr>
							<?php						
							foreach ($sprints as $sprint) {									
								echo '<tr><td><a href="/kanban/sprint/'.$projectid.'/'.$sprint['id'].'">'.$sprint['name'].'</a> </td><td> '.$sprint['startdate'].' </td><td> '.$sprint['enddate']."</td></tr>\n";
							}						
							?>
						</table>
						</td>
						</tr>
					</table>
					<br>
					
