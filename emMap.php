<?php
/**
 * @version		$Id: emMap.php rev.0.1 2019.02.01 nu7 $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die;

/**
 * Plugin for displaying embed maps for Google Maps. Can be used for replace map created by System - Google Maps plugin ({mosmap})
 * It works without API key.
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.loadmodule
 * @since       1.5
 */
class PlgContentemMap extends JPlugin
{
	public $width = 400; //px
	public $height = 200; //px
	public $lat; //latitude
	public $lon; //longitude
	public $address; //default address
	public $zoom = 8; //map zoom
	public $tag = 'mosmap';
	public $customstyles;

	/**
	 * Plugin for displaying embed maps for Google Maps. Can be used for replace map created by System - Google Maps plugin ({mosmap})
	 * It works without API key.
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   object   &$article  The article object.  Note $article->text is also available
	 * @param   mixed    &$params   The article params
	 * @param   integer  $page      The 'page' number
	 *
	 * @return  mixed   true if there is an error. Void otherwise.
	 *
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return true;
		}
		// Simple performance check to determine whether bot should process further
		if (strpos($article->text, $this->tag) === false)
		{
			return true;
		}

		// Set variables from user config
		if($this->params->get('tag')){
			$this->tag = $this->params->get('tag');
		}
		if($this->params->get('width')){
			$this->width = $this->params->get('width');
		}
		if($this->params->get('height')){
			$this->height = $this->params->get('height');
		}
		if($this->params->get('latitude')){
			$this->lat = $this->params->get('latitude');
		}
		if($this->params->get('longitude')){
			$this->lon = $this->params->get('longitude');
		}
		if($this->params->get('address')){
			$this->address = $this->params->get('address');
		}
		if($this->params->get('zoom')){
			$this->zoom = $this->params->get('zoom');
		}
		if($this->params->get('customstyles')){
			$this->customstyles = $this->params->get('customstyles');
		}

		// Expression to search for (positions)
		$regex = '/{'.$this->tag.'\s(.*?)}/i';
	
		// Find all instances of plugin and put in $matches for loadposition
		// $matches[0] is full pattern match, $matches[1] have the plugin options
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
		
		// No matches, skip this
		if ($matches)
		{
			foreach ($matches as $match)
			{
				// explode all plugin options like: width, heiht, zoom, lat, long
				$options = explode('|', $match[1]);

				//build clean array with options | $key=>$value 
				$remove_chars_arr = ["'","\""];
				$par = [];
				foreach($options as $item){
					$_option = explode('=', $item);
					if(!empty($_option[0]) && !empty($_option[1])){
						$par[trim($_option[0])] = str_replace($remove_chars_arr, '', trim($_option[1])); 
					}
				}

				// Replace tag (eg.{mosmap}) by embed map	
				 $article->text = str_replace($matches[0], $this->getMap($par), $article->text);
			}
		}
	}
	
	private function getMap($par){
		$width = (!empty($par['width'])) ? $par['width'] : $this->width;
		$height = (!empty($par['height'])) ? $par['height'] : $this->height;
		$lat = (!empty($par['centerlat'])) ? $par['centerlat'] : $this->lat;
		$lon = (!empty($par['centerlon'])) ? $par['centerlon'] : $this->lon;
		$address = (!empty($par['address'])) ? $par['address'] : $this->address;
		$zoom = (!empty($par['zoom'])) ? $par['zoom'] : $this->zoom;
		$map_place = (!empty($lat) && !empty($lon)) ? $lat.','.$lon : $address;

		$map_html = '<div class="map"><div class="map_canvas">
			<iframe width="'.$width.'" height="'.$height.'" id="map_canvas" 
			src="https://maps.google.com/maps?q='.$map_place.'&t=&z='.$zoom.'&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
			</div>
			</div>'.
			'<style>' . $this->customstyles . '</style>';
		
		return $map_html;
	}
} 