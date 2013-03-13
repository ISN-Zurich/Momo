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

<!-- begin: dialog_login -->
<div class="row">
	<div class="offset4 span4">
	
		<div class="momo_panel">
	
			<div class="momo_panel_header momo_top_spacer100">
				<h4><?php echo $component_title; ?></h4>
			</div>
	    	<form class="well momo_panel_body" style="margin-bottom: 0px;"  name="form" action="/security/authenticate" method="post">  
				<input name="login" type="text" class="span3" placeholder="username" tabindex="1" />  
			 	<input name="password" type="password" class="span3" placeholder="password" tabindex="2" />
			 	<button class="btn btn-small" style="margin-top: 15px; display: block;" tabindex="3">login</button>
			</form>
			<a style="float: right; font-size: 11px;" href="/manageusers/displayrecoverpasswordform">recover password</a>
		
		  </div>	 	 
    </div>
</div>
<!-- end: dialog_login -->


