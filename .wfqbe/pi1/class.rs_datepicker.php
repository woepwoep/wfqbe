<?php

class rs_datepicker {

	function showDate($value, $name, $id)	{

		$fieldValue = $this->pibase->piVars[$name]!='' ? $this->pibase->piVars[$name] :
			($this->pibase->piVars[$value['field']]!='' ? $this->pibase->piVars[$value['field']] : '');

		return '<input id="'.$id.'" class="date" type="text" name="tx_wfqbe_pi1['.$name.']" value="'.
			$this->charToEntity($fieldValue).'" />';
	}

	function showTime($value, $name, $id)	{

		$fieldValue = $this->pibase->piVars[$name]!='' ? $this->pibase->piVars[$name] :
			($this->pibase->piVars[$value['field']]!='' ? $this->pibase->piVars[$value['field']] : '');

		return '<input id="'.$id.'" class="time" type="text" name="tx_wfqbe_pi1['.$name.']" value="'.
			$this->charToEntity($fieldValue).'" />';
		
	}
	
	
	function date2unixtime($date)	{

		// dateFormat is d-m-Y
		$dateArray = explode('-', $date);

		$day	= $dateArray[0];
		$month	= $dateArray[1];
		$year	= $dateArray[2];

		return mktime(0, 0, 0, $month, $day, $year);
	}
	
	function time2unixtime($date) {

		// timeFormat is H:M
		$timeArray = explode(':', $date);

		$hour = $timeArray[0];
		$minute = $timeArray[1];
		
		return mktime($hour, $minute, 0, 1, 1, 1970);
	}
	
	function unixtime2date($timestamp) {

		// dateFormat is d-m-Y
		$dateFormat = 'd-m-Y';

		return date($dateFormat,$timestamp);
	}

	function unixtime2time($timestamp) {

		// timeFormat is H:i
		$timeFormat = 'H:i';

		return date($timeFormat,$timestamp);
	}
}

?>
