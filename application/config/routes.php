<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
/*
$route['pages/(:any)'] = 'pages/view/$1';
$route['pages'] = 'pages';
$route['users/test'] = 'users/test';
$route['users/(:any)'] = 'users/$1';
$route['default_controller'] = 'pages/view';
*/
$route['default_controller'] = "wisecamera_pages/redirect";
$route['pages/view/(:any)'] = "wisecamera_pages/view/$1";
$route['projects/(:any)'] = "wisecamera_projects/$1";
$route['log/(:any)'] = "wisecamera_log/$1";
$route['proxy/(:any)/(:any)/(:any)'] = "wisecamera_proxy/$1/$2/$3";
$route['proxy/(:any)'] = "wisecamera_proxy/$1";
$route['schedules/(:any)'] = "wisecamera_schedules/$1";
$route['email/(:any)'] = "wisecamera_email/$1";
$route['users/(:any)'] = "wisecamera_users/$1";
$route['export/(:any)'] = "wisecamera_export/$1";
$route['export/(:any)/(:any)'] = "wisecamera_export/$1/$2";
$route['404_override'] = '';
/* End of file routes.php */
/* Location: ./application/config/routes.php */
