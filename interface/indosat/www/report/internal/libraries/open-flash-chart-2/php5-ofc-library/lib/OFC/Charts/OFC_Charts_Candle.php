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

require_once dirname(__FILE__) . '/OFC_Charts_Base.php';

class OFC_Charts_Candle_Value
{
	function OFC_Charts_Candle_Value($high, $top, $bottom, $low)
	{
		$this->high 	= $high;
		$this->top 		= $top;
		$this->bottom	= $bottom;
		$this->low		= $low;
	}
}


class OFC_Charts_Candle extends OFC_Charts_Base
{
	
	public $value = array(); 
	
	function OFC_Charts_Candle()
    {
        parent::OFC_Charts_Base();

		$this->type = 'candle';
		$this->tip  = "#x_label#<br>High: #high#<br>Open: #open#<br>Close: #close#<br>Low: #low#";
	}

	function set_values( $high, $top, $bottom, $low )
    {
		$this->values[] = new OFC_Charts_Candle_Value($high, $top, $bottom, $low);
	}	

	function set_colour( $colour )
    {
		$this->colour = $colour;
	}
	
	function set_negative_colour( $colour )
    {
		$this->{'negative-colour'} = $colour;
	}
	
	function set_tooltip( $tip_format='' )
	{
		$this->tip = $tip_format;
	}
}

