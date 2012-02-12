<h3>WorkPackages</h3>
            	<form id="newgworkpackage" name="newgworkpackage" action="">
                <input type="hidden" name="newgworkpackage_projectid" id="newgworkpackage_projectid" value="<?php echo $projectid; ?>" />
                <table >
                  <tr>
                    <td colspan="2" class="settingsrightside"><h4>Add New WorkPackage :</h4></td>
                  </tr>
                  <tr>
                    <td class="settingsleftside">Name:</td>
                    <td><input name="newgworkpackage_name" id="newgworkpackage_name" value="any name" /></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td class="settingsleftside"><input type="submit"  value="Add"  /></td>
                  </tr>
                </table>
              </form>
                <br>
                
                
                
                <hr>
                <form id="editworkpackage" name="editworkpackage" action="">
                  <input type="hidden" id="editworkpackage_projectid" name="editworkpackage_projectid" value="<?php echo $projectid; ?>" />
                  <table >
                    <tr>
                      <td colspan="2"  class="settingsrightside"><h4>Edit Group Name :</h4></td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">Select:</td>
                      <td class="settingsrightside"><select name="editworkpackage_id" id="editworkpackage_id">
                          <?php						
							foreach ($workpackages as $wp) {		
							
								echo ' <option value="'.$wp['id'].'">'.$wp['name'].'</option>';
														
							}						
							?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">New name:</td>
                      <td class="settingsrightside"><input name="editworkpackage_name" id="editworkpackage_name" value="any name" /></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td class="settingsleftside"><input type="submit"  value="Update"  /></td>
                    </tr>
                  </table>
                </form>
                <br>
                
                
                
                
                <hr>
                <form id="deleteworkpackage" name="deleteworkpackage" action="">
                  <input type="hidden" id="deleteworkpackage_projectid" name="deleteworkpackage_projectid" value="<?php echo $projectid; ?>" />
                 <table >
                    <tr>
                      <td colspan="2" class="settingsrightside"><h4>Delete WorkPackage :</h4></td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">Select:</td>
                      <td class="settingsrightside"><select name="deleteworkpackage_id" id="deleteworkpackage_id">
                           <?php						
							foreach ($workpackages as $wp) {		
							
								echo ' <option value="'.$wp['id'].'">'.$wp['name'].'</option>';
														
							}						
							?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td colspan=2> Note! All Tasks will be deleted !. </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td class="settingsleftside"><input type="submit"  value="Delete"  /></td>
                    </tr>
                  </table>
                </form>