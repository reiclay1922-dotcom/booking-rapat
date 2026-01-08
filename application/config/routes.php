<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth/login';
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

$route['customer/booking'] = 'customer/booking';
$route['customer/submit']  = 'customer/submit';
$route['customer/history'] = 'customer/history';

$route['verifikator'] = 'verifikator/index';
$route['verifikator/approve/(:num)'] = 'verifikator/approve/$1';
$route['verifikator/reject/(:num)']  = 'verifikator/reject/$1';
$route['verifikator/live'] = 'verifikator/live';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
