<?php
/*--------------------------------------------------------------------------------------------
 * string_utils.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/

namespace workbench\utils {
	
	class StringUtils {
		/*------------------------------------------------------------------------------------------
		 * str_replace_first
		 *
		 *
		 -------------------------------------------------------------------------------------------*/
		public static function str_replace_first($from, $to, $subject)
		{
			$from = '/'.preg_quote($from, '/').'/';
		
			return preg_replace($from, $to, $subject, 1);
		}
		
		/*------------------------------------------------------------------------------------------
		 * str_concat_at($subject,$start_string)
		 *
		 *
		 -------------------------------------------------------------------------------------------*/
		public static function str_concat_at($subject, $start_string)
		{
			$from = strpos($subject,$start_string);
		
			if($from != false )
				$subject = substr($subject, 0, $from);
		
				return $subject;
		}
		
		/*------------------------------------------------------------------------------------------
		 * str_truncate_left($subject,length)
		 *
		 *
		 -------------------------------------------------------------------------------------------*/
		public static function str_truncate_at($subject, $length)
		{
			if(strlen($subject)>$length) {
		
				return "... " . substr($subject,strlen($subject)-$length);
		
			}
		
			return $subject;
		}
	} //close class
} // close namespace
?>