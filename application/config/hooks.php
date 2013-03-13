<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/


/*
 * the setExceptionHandler hook sets up application wide exception handling
 * */

$hook['pre_system'][] = array(
                   'class'    => 'ExceptionHandler',
                   'function' => 'setExceptionHandler',
                   'filename' => 'ExceptionHandler.php',
                   'filepath' => 'hooks'
                  );

/*
 * the initautoloader hook sets up class autoloading
 * */
$hook['pre_system'][] = array(
                                'class'    => 'Autoloader',
                                'function' => 'initautoloader',
                                'filename' => 'Autoloader.php',
                                'filepath' => 'hooks'
                              );
                              
                              
/*
 * the initautoloader hook sets up class autoloading
 * */
$hook['pre_system'][] = array(
                                'class'    => 'CIRuntimeParamSetter',
                                'function' => 'set',
                                'filename' => 'CIRuntimeParamSetter.php',
                                'filepath' => 'hooks'
                              );
                                
/*
 * the phpruntimeparamsetter hook is the place to set php runtime params
 * it is run at "post_controller_constructor" as we need a CI instance in order
 * to access momo config values.
 * */
$hook['pre_controller'][] = array(
                   'class'    => 'PhpRuntimeParamSetter',
                   'function' => 'set',
                   'filename' => 'PhpRuntimeParamSetter.php',
                   'filepath' => 'hooks'
                  );                                

                  
/*
 * the authenticator hook prevents unathenticated system access
 * */
$hook['post_controller_constructor'][] = array(
	                                'class'    => 'Authenticator',
	                                'function' => 'checkAuthenticatedAndEnabled',
	                                'filename' => 'Authenticator.php',
	                                'filepath' => 'hooks'
	                                );

	                                
/* End of file hooks.php */
/* Location: ./application/config/hooks.php */