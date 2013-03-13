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


/**
 * Alert Box Widget
 * 
 * Allows the display of a variety of user messages.
 * 
 * Load via the CI view mechanism and pass the following parameters in the $data array.
 * 
 * "boxId"	- (optional) the id of the alert box. (defaults to "alertBox")
 * "width"	- (optional) the width of the alert box. (the css attribute is not set if the parameter is not specified)
 */

// process view parameters
$alertBoxId = "alertBox";
if ( isset($id) ) {
	$alertBoxId = $id;
}

$widthStyle = "";
if ( isset($width) ) {
	$widthStyle = "width: " . $width . "px";
}

// set up a global scope flag that ensures single loading of widget javascript
global $__widget_alert_box_jsLoaded;

if ( ! isset($_alertBox_jsLoaded) ) {
	$__widget_alert_box_jsLoaded = false;
}
	
?>


<?php if( ! $__widget_alert_box_jsLoaded ) : ?>

	<script src="/assets/js/momo-alerts.js"></script>
	<?php $_alertBox_jsLoaded = true; ?>
	
<?php endif; ?>


<div id="<?php echo $alertBoxId; ?>" class="alert alert-block" style="display: none; <?php echo $widthStyle; ?>">
	<p class="alertMsg" />
	<div class="controls" style="margin-top: 15px; display: none;">
		<a class="control_1 btn btn-mini" href="#" style="display: none;"></a> <a class="control_2 btn btn-mini" href="#" style="display: none;"></a>
		<span class="spinner" style="display: none;"><img src="/assets/img/ajax-spinner.gif"/></span>
	</div>
</div>
