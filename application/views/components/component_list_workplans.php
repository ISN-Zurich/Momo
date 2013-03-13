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

<script>
	//
	// deletes the indicated entry
	//
	function deleteEntry(entryId) {
		document.location.href='/manageworkplans/deleteplan/' + entryId;
	}

	//
	// edit the indicated entry
	//
	function editEntry(entryId) {
		document.location.href='/manageworkplans/displayeditplanform/' + entryId;
	}

	// 
	// setup that needs to trigger on document "ready"
	//
	$(function() {
		
		<?php
			// issue appropriate JS notification if there are no user records available
			if ( $component_workplans_all->count() == 0 ) :
		?>

			displayAlert("alertBox", "No workplans on record.", "alert-info");
					
		<?php endif; ?>
	});
	
</script>

<!-- begin: component_entryworkplanslist -->
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
	    		
	    		<?php if ( $component_workplans_all->count() != 0 ) : ?>
	    		
				    <table class="table table-striped table-condensed">
				      	<thead>
						    <tr>
							    <th>Workplan</th>
							    <th>In Use</th>
							    <th>Active</th>
							    <th></th>
						    </tr>
				   		</thead>
		
				   		<tbody>
					    	<?php 
								//
								// render the iterator contents
								foreach ( $component_workplans_all as $curPlan ) :	
							
							?>		
							    <tr>
								    <td width="20%"><?php echo $curPlan->getYear(); ?></td>
								    <td width="15%"><?php print($curPlan->isInUse() ? "yes" : "no"); ?></td>
								    <td width="40%"><?php print($curPlan->isActive() ? "yes" : "no"); ?></td>
								    <td width="20%">
								    
								    	<?php 
								    		// the "edit" control is only available as long as the plan is not "active".
								    		// a plan is "active", once a given workplan's parametrization is applied to the user accounts
								    		if ( ! $curPlan->isActive() ) :
								    	?>
								    	
								    		<a class="btn btn-mini" href="javascript:editEntry(<?php echo $curPlan->getId(); ?>);">edit</a>
								    	
								    	<?php endif; ?>
							
								    	<?php 
								    		// the "delete" control is only available for entry types that are not in use.
								    		// otherwise, we'd wind up with timetracker entries that have no context/meaning
								    		if ( ! $curPlan->isInUse() ) :
								    	?>
								    		<a class="btn btn-mini" href="javascript:displayConfirmDialog('alertBox', 'Are you sure you want to delete the workplan for the year <strong><?php echo $curPlan->getYear(); ?></strong> ?', 'javascript:deleteEntry(<?php echo $curPlan->getId(); ?>);', 'delete')">delete</a>
								    	
								    	<?php endif; ?>
								    </td>
							    </tr>
							    
							<?php endforeach; ?>
		
						</tbody>
				    </table>
				
				<?php endif; ?>    
				    
			    <a class="btn btn-mini" href="/manageworkplans/displaynewplanform">new</a>
			</div> 		
		</div>
			 
    </div>
</div>

<!-- end: component_entryworkplanslist -->
