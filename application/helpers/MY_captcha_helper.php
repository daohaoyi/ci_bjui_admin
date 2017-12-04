<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter CAPTCHA Helper
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/captcha_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('create_captcha'))
{
	/**
	 * Create CAPTCHA
	 *
	 * @param	array	$data		data for the CAPTCHA
	 * @param	string	$img_path	path to create the image in
	 * @param	string	$img_url	URL to the CAPTCHA image folder
	 * @param	string	$font_path	server path to font
	 * @return	string
	 */
	function create_captcha($data = '')
	{
		$defaults = array(
			'word'		=> '',
			'img_width'	=> '130',
			'img_height'	=> '30',
			'expiration'	=> 7200,
			'word_length'	=> 4,
			'font_size'	=> 38,
			'img_id'	=> '',
			'pool'		=> 'abcdefghkmnprstuvwyzABCDEFGHKLMNPRSTUVWYZ23456789',
			'colors'	=> array(
				'background'	=> array(237, 247, 255),
				'text'		=> array(rand(0, 156),rand(0, 156),rand(0, 156)),
				'grid'		=> array(255,182,182)
			)
		);

		foreach ($defaults as $key => $val)
		{
			if ( ! is_array($data) && empty($$key))
			{
				$$key = $val;
			}
			else
			{
				$$key = isset($data[$key]) ? $data[$key] : $val;
			}
		}

		// -----------------------------------
		// Do we have a "word" yet?
		// -----------------------------------

		if (empty($word))
		{
			$word = '';
			for ($i = 0, $mt_rand_max = strlen($pool) - 1; $i < $word_length; $i++)
			{
				$word .= $pool[mt_rand(0, $mt_rand_max)];
			}
		}
		elseif ( ! is_string($word))
		{
			$word = (string) $word;
		}

		// -----------------------------------
		// Determine angle and position
		// -----------------------------------
		$length	= strlen($word);
		$angle	= ($length >= 6) ? mt_rand(-($length-6), ($length-6)) : 0;
		$x_axis	= mt_rand(6, (360/$length)-16);
		$y_axis = ($angle >= 0) ? mt_rand($img_height, $img_width) : mt_rand(6, $img_height);

		// Create image
		// PHP.net recommends imagecreatetruecolor(), but it isn't always available
		$im = function_exists('imagecreatetruecolor')
			? imagecreatetruecolor($img_width, $img_height)
			: imagecreate($img_width, $img_height);

		// -----------------------------------
		//  Assign colors
		// ----------------------------------

		is_array($colors) OR $colors = $defaults['colors'];

		foreach (array_keys($defaults['colors']) as $key)
		{
			// Check for a possible missing value
			is_array($colors[$key]) OR $colors[$key] = $defaults['colors'][$key];
			$colors[$key] = imagecolorallocate($im, $colors[$key][0], $colors[$key][1], $colors[$key][2]);
		}

		// Create the rectangle
		ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $colors['background']);

		// -----------------------------------
		//  Create the spiral pattern
		// -----------------------------------
		$theta		= 1;
		$thetac		= 7;
		$radius		= 16;
		$circles	= 20;
		$points		= 32;

		for ($i = 0, $cp = ($circles * $points) - 1; $i < $cp; $i++)
		{
			$theta += $thetac;
			$rad = $radius * ($i / $points);
			$x = ($rad * cos($theta)) + $x_axis;
			$y = ($rad * sin($theta)) + $y_axis;
			$theta += $thetac;
			$rad1 = $radius * (($i + 1) / $points);
			$x1 = ($rad1 * cos($theta)) + $x_axis;
			$y1 = ($rad1 * sin($theta)) + $y_axis;
			imageline($im, $x, $y, $x1, $y1, $colors['grid']);
			$theta -= $thetac;
		}
		
		// -----------------------------------
		//  Write the text
		// -----------------------------------

		($font_size > 5) && $font_size = 5;
		$x = mt_rand(0, $img_width / ($length / 1.5));
		$y = 0;

		for ($i = 0; $i < $length; $i++)
		{
			$y = mt_rand(0 , $img_height / 2);
			imagestring($im, $font_size, $x, $y, $word[$i], $colors['text']);
			$x += ($font_size * 5);
		}

		// -----------------------------------
		//  Generate the image
		// -----------------------------------

		if (function_exists('imagejpeg'))
		{
			header("Content-Type:image/jpeg");
			imagejpeg($im);
		}
		elseif (function_exists('imagepng'))
		{
			header("Content-Type:image/png");
			imagejpng($im);
		}
		else
		{
			return FALSE;
		}

		ImageDestroy($im);
		return $word;
	}
}
