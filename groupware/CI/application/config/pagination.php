<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['suffix']      = $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '';
$config['first_url']   = '1'.($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '');
$config['per_page']    = PAGING_PER_PAGE;