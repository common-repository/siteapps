<?php

/*
Plugin Name: SiteApps
Plugin URI: http://siteapps.com/?utm_source=wordpress&utm_medium=plugin&utm_campaign=plugin_page
Description: SiteApps is the optimization command center for the SMB website.  SiteApps is designed for you - the business owner - to update, enhance and optimize the most valuable asset of your digital presence.  This plugin automatically installs SiteApps on your WordPress site.
Author: Leandro Lages, Phillip Klien, Rafael Mauro, Marcelio Leal, Gabriel Sapo (SiteApps Team 2014)
Version: 4.9
Requires at least: 2.8
Author URI: http://siteapps.com/
License: GPL2
*/

/*  Copyright 2014 - SiteApps

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//definir constantes com os diretorios e usar
define('SITEAPPS_CLASS_DIR', dirname(__FILE__) . "/classes/");
define('SITEAPPS_VIEW_DIR', dirname(__FILE__) . "/views/");
define('SITEAPPS_IMAGES_DIR', dirname(__FILE__) . "/images/");
define('SITEAPPS_PLUGIN_NAME', 'siteapps');
define('SITEAPPS_VERSION', '4.9');

require_once(SITEAPPS_CLASS_DIR . 'SiteAppsPlugin.php');

$siteAppsPlugin = new SiteAppsPlugin(dirname(__FILE__));
