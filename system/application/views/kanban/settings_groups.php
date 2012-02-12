
	<h3>Groups</h3>

			<form id="newgroup" name="newgroup" action="">
                <input type="hidden" name="newgroup_projectid" id="newgroup_projectid" value="<?php echo $projectid; ?>" />
                <table >
                  <tr>
                    <td colspan="2"  class="settingsrightside"><h4>Add New Group :</h4></td>
                  </tr>
                  <tr>
                    <td class="settingsleftside">Name:</td>
                    <td><input name="newgroup_name" id="newgroup_name" value="any name" /></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td class="settingsleftside"><input type="submit"  value="Add"  /></td>
                  </tr>
                </table>
              </form>
                <br>
                <hr>
                <form id="editgroup" name="editgroup" action="">
                  <input type="hidden" id="editgroup_projectid" name="editgroup_projectid" value="<?php echo $projectid; ?>" />
                  <table >
                    <tr>
                      <td colspan="2"  class="settingsrightside"><h4>Edit Group Name :</h4></td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">Select:</td>
                      <td class="settingsrightside"><select name="editgroup_groupid" id="editgroup_groupid">
                          <?php						
							foreach ($groups as $group) {		
							
								echo ' <option value="'.$group['id'].'">'.$group['name'].'</option>';
														
							}						
							?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">New name:</td>
                      <td class="settingsrightside"><input name="editgroup_name" id="editgroup_name" value="any name" /></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td class="settingsleftside"><input type="submit"  value="Update"  /></td>
                    </tr>
                  </table>
                </form>
                <br>
                <hr>
                <form id="deletegroup" name="deletegroup" action="">
                  <input type="hidden" id="deletegroup_projectid" name="deletegroup_projectid" value="<?php echo $projectid; ?>" />
                  <input type="hidden" id="deletegroup_firstgroupid" name="deletegroup_firstgroupid" value="<?php echo $groups[0]['id']; ?>" />
                  <table >
                    <tr>
                      <td colspan="2"  class="settingsrightside"><h4>Delete Group :</h4></td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">Select:</td>
                      <td class="settingsrightside"><select name="deletegroup_groupid" id="deletegroup_groupid">
                          <?php						
							foreach ($groups as $group) {		
							
								echo ' <option value="'.$group['id'].'">'.$group['name'].'</option>';
														
							}						
							?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td colspan=2> Note! All Tasks will be moved to 'unassigned'. </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td class="settingsleftside"><input type="submit"  value="Delete"  /></td>
                    </tr>
                  </table>
                </form>
                <br>
                <hr>
                <table>
                  <tr>
                    <td colspan=2><h4> The Group Order :</h4></td>
                  </tr>
                  <tr>
                    <td class="settingsleftside"></td>
                    <td class="settingsrightside"><ul id="sortablegroup" >
                        <?php						
							foreach ($groups as $group) {		
							
								echo ' <li id="sgroup'.$group['id'].'"  class="ui-state-highlight">'.$group['name'].'</li>'."\n";
														
							}						
							?>
                    </ul></td>
                    <td>    
                  </tr>
              </table>