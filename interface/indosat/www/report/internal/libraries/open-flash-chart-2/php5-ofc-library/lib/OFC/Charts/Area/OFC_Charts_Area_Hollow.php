<?php
/**
 * PHP Integration of Open Flash Chart
 * Copyright (C) 2008 John Glazebrook <open-flash-chart@teethgrinder.co.uk>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once dirname(__FILE__) . '/../OFC_Charts_Area.php';

class OFC_Charts_Area_Hollow_Style
{
	function __construct(){
		$this->type = 'hollow';
		$this->{'dot-size'} = 2;
	}
}

class OFC_Charts_Area_Hollow_Dot_Style
{
	function __construct(){
		$this->type = 'hollow-dot';
		$this->{'dot-size'} = 2;
		$this->tip = '#key#<br>Total:#val#';
	}
}

class OFC_Charts_Area_Hollow extends OFC_Charts_Area
{
	
	public $values = array();
	
	function OFC_Charts_Area_Hollow()
    {
        parent::OFC_Charts_Area();
		$this->{'dot-style'}  = new OFC_Charts_Area_Hollow_Dot_Style;
		$this->{'fill-alpha'} = 0.35;		
	}

	function set_width( $w )
    {
		$this->width     = $w;
	}

	function set_colour( $colour )
    {
		$this->colour = $colour;
	}
	
	function set_fill( $fill ){
		$this->fill = $fill;
	}

	function set_values( $v ) {		
		$this->values = (is_array($v)) ? $v : (array)$v;
	}

	function set_dot_size( $size )
    {
		$this->{'dot-size'} = $size;
	}

	function set_key( $text, $font_size )
    {
		$this->text      	 = $text;
		$this->{'font-size'} = $font_size;
	}
}

