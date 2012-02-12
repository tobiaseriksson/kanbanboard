<h3>Import Tasks</h3>
					<form id="importtaskstosprint" name="importtaskstosprint" action="">
					<input type="hidden" id="importtaskstosprint_projectid" name="importtaskstosprint_projectid" value="<?php echo $projectid; ?>" />					
					<table>					
					<tr><td></td></tr>
						<tr>
						  <td class="settingsleftside">To Sprint :</td>
						  <td class="settingsrightside"><select name="importtaskstosprint_sprintid" id="importtaskstosprint_sprintid">
							<?php						
							foreach ($sprints as $sprint) {		
							
								echo ' <option value="'.$sprint['id'].'">'.$sprint['name'].'</option>';
														
							}						
							?>							
							</select>
							</td></tr>
						 <tr>
							<td class="settingsleftside">Text :</td>
						<td class="settingsrightside"><textarea name="importtaskstosprint_text" id="importtaskstosprint_text" cols="40" rows="20">
heading;description;priority;estimate;color;added;workpackage
where
heading is text 
description is text
priority an integer 0=low 100=high
estimate an integer 
color an integer, 1=yellow,2=green,3=red
added is a date(YYYY-MM-DD)
workpackage is the name of the workpackage
						</textarea>
							</td></tr>
						<tr><td colspan=2></td></tr>
						<tr><td></td><td class="settingsleftside"><input type="submit"  value="Import"  /></td></tr>
					</table>
					</form>
					