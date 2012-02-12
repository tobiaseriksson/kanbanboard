<h3>Project</h3>
Here you can delete and rename projects

 				<form id="editproject" name="editproject" action="">
                  <table >
                    <tr>
                      <td colspan="2"  class="settingsrightside"><h4>Edit Project Name :</h4></td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">Select:</td>
                      <td class="settingsrightside"><select name="editproject_id" id="editproject_id">
                          <?php						
							foreach ($projects as $proj) {		
							
								echo ' <option value="'.$proj['id'].'">'.$proj['name'].'</option>';
														
							}						
							?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">New name:</td>
                      <td class="settingsrightside"><input name="editproject_name" id="editproject_name" value="any name" /></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td class="settingsleftside"><input type="submit"  value="Update"  /></td>
                    </tr>
                  </table>
                </form>
                <br>
                <hr>
                <form id="deleteproject" name="deleteproject" action="">
                  <table >
                    <tr>
                      <td colspan="2"  class="settingsrightside"><h4>Delete Project :</h4></td>
                    </tr>
                    <tr>
                      <td class="settingsleftside">Select:</td>
                      <td class="settingsrightside"><select name="deleteproject_id" id="deleteproject_id">
                        <?php						
							foreach ($projects as $proj) {		
							
								echo ' <option value="'.$proj['id'].'">'.$proj['name'].'</option>';
														
							}						
							?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td colspan=2> Note! Everything will be deleted !!!. </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td class="settingsleftside"><input type="submit"  value="Delete"  /></td>
                    </tr>
                  </table>
                </form>
                <br>