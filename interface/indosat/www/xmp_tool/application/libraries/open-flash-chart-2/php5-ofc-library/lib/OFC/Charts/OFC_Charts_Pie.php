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

class OFC_Charts_Pie_Value
{
	function OFC_Charts_Pie_Value( $value, $text='', $on_click='')
    {
		$this->value = $value;
		
		if($text != ''){
			$this->label = $text;
		}
		
		if($on_click != ''){
			$this->{'on-click'} = $on_click;
		}
	}
}

class OFC_Charts_Pie extends OFC_Charts_Base
{
	
	public $type 	= 'pie';
	public $alpha	= 0.6;
	public $border	= 2;
	public $values	= array();
	public $colours = array();
	
	function OFC_Charts_Pie()
    {
        parent::OFC_Charts_Base();

//		$this->type		= 'pie';
//		$this->colours  = array("#d01f3c","#356aa0","#C79810");
//		$this->alpha	= 0.6;
//		$this->border	= 2;
//		$this->values	= array(2,3,new OFC_Charts_Pie_Value(6.5, 'hello (6.5)'));
	}

	// boolean
	function set_animate( $v )
    {
		$this->animate = $v;
	}

	// real
	function set_start_angle( $angle )
    {
		$this->{'start-angle'} = $angle;
	}
	
	
	function set_values($values, $label='', $on_click=''){
		if(is_array($values)){
			$this->values = $values;
		}
		else{
			if($label != ''){
				array_push($this->values, new OFC_Charts_Pie_Value($values, $label, $on_click));
			}
			else{
				array_push($this->values, $values);
			}
		}
	}
	
	function set_colours( $color ){
		$this->colours = $color;
	}
	
	function set_tooltip( $t ){
		$this->tip = $t;
	}
	
	function set_no_labels()
	{
		$this->{'no-labels'} = true;
	}
	
	function gradient_fill()
	{
		$this->{'gradient-fill'} = true;
	}
}