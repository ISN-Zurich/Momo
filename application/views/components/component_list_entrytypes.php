<?php 

/** 
 * Copyright 2013, ETH Zürich
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License. 
 */

?>

<!-- begin: component_list_entrytypes -->
<script>
	//
	// deletes the indicated entry
	//
	function deleteEntry(entryId) {
		document.location.href='/manageentrytypes/deletetype/' + entryId;
	}

	//
	// edit the indicated entry
	//
	function editEntry(entryId) {
		document.location.href='/manageentrytypes/displayedittypeform/' + entryId;
	}

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		
		<?php
			// issue appropriate JS notification if there are no requests available
			if ( $component_types_active->count() == 0 ) :
		?>

			displayAlert("alertBox", "No entry types on record.", "alert-info");
					
		<?php endif; ?>
	});
	
</script>

<div class="row">
	<div class="offset2 span8">
	
		<div class="momo_panel">
		
			<div class="momo_panel_header">
				<h4><?php echo $component_title ?></h4>
			</div>
	
	    	<div class="well momo_panel_body">  
	    		<?php 	
	    			// load alert box widget
	    			$this->load->view("widgets/widget_alert_box.php");
	    		?>
	    		
	    		<?php if ( $component_types_active->count() != 0 ) : ?>  
	    		
				    <table class="table table-striped table-condensed">
				      	<thead>
						    <tr>
							    <th>Type</th>
							    <th>Enabled</th>
							    <th>In Use</th>
							    <th></th>
						    </tr>
				   		</thead>
		
				   		<tbody>
					    	<?php 
								//
								// render the iterator contents
								foreach ($component_types_active as $curType) :	
							
							?>		
							    <tr>
								    <td width="30%"><?php echo $curType->getType(); ?></td>
								    <td width="20%"><?php print($curType->getEnabled() ? "yes" : "no");  ?></td>
								    <td width="35%"><?php print($curType->isInUse() ? "yes" : "no");  ?></td>
								    <td width="15%">
								    
								    	<a class="btn btn-mini" href="javascript:editEntry(<?php echo $curType->getId(); ?>);">edit</a>
								    	
								    	<?php 
								    		// the "delete" control is only available for entry types that are not in use.
								    		// otherwise, we'd wind up with timetracker entries that have no context/meaning
								    		if ( ! $curType->isInUse() ) :
								    	?>
								    		<a class="btn btn-mini" href="javascript:displayConfirmDialog('alertBox', 'Are you sure you want to delete the entry type <strong><?php echo $curType->getType(); ?></strong> ?', 'javascript:deleteEntry(<?php echo $curType->getId(); ?>);', 'delete')">delete</a>
								    	
								    	<?php endif; ?>
								    </td>
							    </tr>
							    
							<?php endforeach; ?>
		
						</tbody>
				    </table>
				    
				<?php endif; ?>
				
			    <a class="btn btn-mini" href="/manageentrytypes/displaynewtypeform">new</a>
			</div> 	 
			
		</div>	
			
    </div>

</div>
<!-- end: component_list_entrytypes -->
