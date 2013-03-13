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


<!DOCTYPE html>

<html lang="en">
	<head>
		<title><?php echo $master_site_name; ?></title>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=1250">   
		<meta name="robots" content="none">  
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="expires" content="-1">

		<script src="/assets/js/third-party/jquery-1.7.2.min.js"></script>
		<script src="/assets/js/third-party/jquery-ui-1.8.21.custom.min.js"></script>
		<script src="/assets/js/third-party/jquery-uncheckable-radio.js"></script>
		<script src="/assets/js/third-party/date.js"></script>
		<script src="/assets/js/third-party/bootstrap.min.js"></script>
		
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="/assets/css/bootstrap-custom.css" rel="stylesheet">
		<link href="/assets/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
		<link href="/assets/css/colorpicker.css" rel="stylesheet">	
		<link href="/assets/css/momo.css?18122012" rel="stylesheet">
		
			
		<style type="text/css">
		    body {
		        padding-top: 0px;
		        padding-bottom: 40px;
		    }
		    
		    .sidebar-nav {
		        padding: 9px 0;
		    }
	    </style>

	</head>
	<body>
		<?php $this->load->view("widgets/widget_navbar.php"); ?>
		<div class="container">
			<div class="momo_stage">
				<?php $this->load->view($component); ?>
	      	</div>
	      	<div class="momo_footer momo_version">
				
				  <?php echo $this->config->item("version", "momo"); ?> 
				/ <?php echo $this->benchmark->elapsed_time(); ?> 
				
				<?php if (		defined('ENVIRONMENT')
							&& (ENVIRONMENT == "development") ) : 
				?>
					/ <?php echo \Propel::getConnection("momo")->getQueryCount(); ?> 
					/ <?php echo $this->benchmark->memory_usage(); ?>
					
				<?php endif; ?>
			</div>
		</div>
	</body>
</html>