<?php
/*
Version: 000000-dev
Text Domain: s2member-domains
Plugin Name: s2 Domains
Plugin URI: http://www.websharks-inc.com
Author URI: http://www.websharks-inc.com
Author: WebSharks, Inc.
Description: Registration access control based email@domain.
*/
if(!defined('WPINC'))
	exit('Do NOT access this file directly.');

if(require(dirname(__FILE__).'/wp-php53.php'))
	require_once dirname(__FILE__).'/s2-domains.inc.php';
else wp_php53_notice('s2 Domains');
