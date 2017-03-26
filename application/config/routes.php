<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
$route['api/test/send_mail'] = 'api/test/send_mail';
$route['api/User/check'] = 'api/User/check';



/*
| -------------------------------------------------------------------------
| USER ROUTE
| -------------------------------------------------------------------------
*/
//register_post
$route['api/user/register'] = 'api/User/register';
//confirm
$route['api/user/confirm/(:any)'] = 'api/User/confirm/$1'; //$1 = token
//delete
$route['api/user/delete/(:any)'] = 'api/User/delete/$1'; //$1 = token
//get_user_detail
$route['api/user/select_user/(:any)'] = 'api/User/select_user/$1'; //$1 = token

//login_post
$route['api/user/login'] = 'api/user/login';


/*
| -------------------------------------------------------------------------
| SELECT ROUTE
| -------------------------------------------------------------------------
*/
//restaurant
$route['api/user/restaurant/(:any)/(:any)'] = 'api/User/restaurant/$1/$2'; //$1 = ID_restaurant		$2 = token
$route['api/user/all_restaurant/(:any)'] = 'api/User/all_restaurant/$1'; //$1 = token

/*
| -------------------------------------------------------------------------
| Tambahan
| -------------------------------------------------------------------------
*/
//like
$route['api/user/like/(:any)/(:any)'] = 'api/User/like/$1/$2'; //$1 = ID_restaurant		$2 = token
//favorite
$route['api/user/fav/(:any)/(:any)'] = 'api/User/fav/$1/$2'; //$1 = ID_restaurant		$2 = token
//feedback_post
$route['api/user/feedback'] = 'api/User/feedback';
//forgot password
$route['api/user/forgot/(:any)'] = 'api/User/forgot/$1'; //$1 = token
//new password
$route['api/User/new_pass/'] = 'api/User/new_pass/'; //$1 = token
//change password
$route['api/User/change_pass/(:any)'] = 'api/User/change_pass/$1'; //$1 = token
//get favorite
$route['api/User/select_favorite/(:any)'] = 'api/User/select_favorite/$1'; //$1 = token
//search
$route['api/User/search/(:any)'] = 'api/User/search/$1'; //$1 = categories
//select user
$route['api/User/select_user/(:any)'] = 'api/User/select_user/$1'; //$1 = token
//edit photo user
$route['api/User/edit_photo/(:any)'] = 'api/User/edit_photo/$1'; //$1 = token