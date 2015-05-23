<?php
######################################################################
# Absolute URLs																							         #
# Copyright (C) 2013 by Noxidsoft  	   	   	   	   	   	   	   	   	 #
# Homepage   : www.noxidsoft.com		   	   	   	   	   	   		 			 #
# Author     : Noel Dixon    		   	   	   	   	   	   	   	     		 #
# Email      : admin@noxidsoft.com 	   	   	   	   	   	   	 				 #
# Version    : 2.5                        	   	    	   	   	   	 	 #
# License    : http://www.gnu.org/copyleft/gpl.html GNU/GPL          #
######################################################################

// no direct access
defined( '_JEXEC' ) or die;

jimport( 'joomla.plugin.plugin');

class plgSystemAbsoluteURLs extends JPlugin {
	function plgSystemAbsoluteURLs(&$subject, $config) {
		parent::__construct($subject, $config);
		
    $this->_plugin = JPluginHelper::getPlugin( 'system', 'absoluteurls' );
	}
	
	public function onAfterRender() 
	{
		$mainframe = JFactory::getApplication();
		
		$absoluteUrls = $this->params->get('absoluteUrls', 0);
		$secureUrl		= $this->params->get('secureUrl', 0);
		$urlFocus 		= $this->params->get('urlFocus', '-1');
		$excludedDir	= $this->params->get('excludedDir', '-1');
		//$includedDir	= $this->params->get('excludedDir', '-1');
		
		//var_dump($urlFocus == '');
		
		/*
		if ($excludedDir) {
			$excludedDir = explode("\r\n", $excludedDir);
			//print_r($excludedDir);
			foreach($excludedDir as $dir) {
				//echo 'https://' . $_SERVER['SERVER_NAME'] . '/' . $dir;
				echo str_replace('href="' . $dir, 'href="' . $dir . str_replace(JURI::root(true), '/' . $dir, JURI::root()), $mainframe);
			}
		}
		*/
		
		//echo '<br />'.JURI::root();
		
		if ($excludedDir != '-1') {
			$excludedDir = explode("\r\n", $excludedDir);
			//print_r($excludedDir);
			foreach($excludedDir as $dir) {
				//echo 'https://' . $_SERVER['SERVER_NAME'] . '/' . $dir;
				//echo str_replace('href="' . $dir, 'href="' . $dir . str_replace(JURI::root(true), '/' . $dir, JURI::root()), $mainframe);
				echo 'hello';
				$mainframe = str_replace('href="http://' . $dir, '/' . $dir . '/', $mainframe);
				//echo $test;
			}
		}
		
		
		
		// if we just want standard absolutes
		if($absoluteUrls != 0 && $secureUrl == 0) {
			$mainframe = str_replace('href="/', 'href="' . str_replace(JURI::root(true), '', JURI::root()), $mainframe);
			$mainframe = str_replace('action="/', 'action="' . str_replace(JURI::root(true), '', JURI::root()), $mainframe);
			$mainframe = str_replace('src="/', 'src="' . str_replace(JURI::root(true), '', JURI::root()), $mainframe);
			$mainframe = str_replace('mailto="/', 'mailto="' . str_replace(JURI::root(true), '', JURI::root()), $mainframe);
			//$mainframe = str_replace('href="templates/', 'href="' . str_replace(JURI::root(true), '/templates', JURI::root()), $mainframe);
		}
		
		// if we have absolutes and ssl only
		if ($absoluteUrls != 0 && $secureUrl != 0 && $urlFocus == '-1') {
			$mainframe = str_replace('href="/', 'href="https://' . $_SERVER['SERVER_NAME'] . '/', $mainframe);
			$mainframe = str_replace('action="/', 'action="https://' . $_SERVER['SERVER_NAME'] . '/', $mainframe);
			$mainframe = str_replace('src="/', 'src="https://' . $_SERVER['SERVER_NAME'] . '/', $mainframe);
			$mainframe = str_replace('mailto="/', 'mailto="https://' . $_SERVER['SERVER_NAME'] . '/', $mainframe);
			$mainframe = str_replace('http://', 'https://' . $_SERVER['SERVER_NAME'] . '/', $mainframe);
		}
		
		// protect the user
		if ($urlFocus == $_SERVER['SERVER_NAME']) {
			// if we have absolutes and ssl and a URL to focus on, exlude all others
			if ($absoluteUrls != 0 && $secureUrl == 0 && $urlFocus != '-1') {
				$mainframe = str_replace('href="/', 'href="http://' . $urlFocus . '/', $mainframe);
				$mainframe = str_replace('action="/', 'action="http://' . $urlFocus . '/', $mainframe);
				$mainframe = str_replace('src="/', 'src="http://' . $urlFocus . '/', $mainframe);
				$mainframe = str_replace('mailto="/', 'mailto="http://' . $urlFocus . '/', $mainframe);
				$mainframe = str_replace('http://', 'http://' . $urlFocus . '/', $mainframe);
			}
			
			// if we have absolutes and ssl and a URL to focus on, exlude all others
			if ($absoluteUrls != 0 && $secureUrl != 0 && $urlFocus != '-1') {
				$mainframe = str_replace('href="/', 'href="https://' . $urlFocus . '/', $mainframe);
				$mainframe = str_replace('action="/', 'action="https://' . $urlFocus . '/', $mainframe);
				$mainframe = str_replace('src="/', 'src="https://' . $urlFocus . '/', $mainframe);
				$mainframe = str_replace('mailto="/', 'mailto="https://' . $urlFocus . '/', $mainframe);
				$mainframe = str_replace('http://', 'https://' . $urlFocus . '/', $mainframe);
			}
		}
		
		
		
		
		
		
		JResponse::setBody($mainframe);
		return true;
	}
}