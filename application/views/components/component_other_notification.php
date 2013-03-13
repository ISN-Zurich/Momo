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
	
// preprocess according to call mode
switch ( $component_mode ) {
	
	case "info":
		$cssTypeClass = "alert-info";
	break;
		
	case "success":
		$cssTypeClass = "alert-success";
	break;

	case "error":
		$cssTypeClass = "alert-error";	
	break;
		
	default:
		$cssTypeClass = "alert-info";		
}

?>

<!-- begin: component_notification -->
<div class="row">
	<div class="offset3 span6">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header momo_top_spacer100">
				<h4><?php echo $component_title ?></h4>
			</div>
			<div class="well momo_panel_body">
				<div class="alert <?php echo $cssTypeClass; ?>" style="min-height: 50px;">
					<?php echo $component_message; ?>
			 	</div>
			 	
			 	<?php 
			 		// if a component target was specified, we issue an action button
			 		if ( $component_target !== null ) : 
			 	?>
			 	
			 		<a class="btn btn-mini" href="<?php echo $component_target; ?>">ok</a>
			 		
			 	<?php endif; ?>
			 			
			</div>
			
		</div>
		
    </div>
</div>
<!-- end: component_notification -->


