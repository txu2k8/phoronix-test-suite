<?php

/*
	Phoronix Test Suite
	URLs: http://www.phoronix.com, http://www.phoronix-test-suite.com/
	Copyright (C) 2008 - 2010, Phoronix Media
	Copyright (C) 2008 - 2010, Michael Larabel
	pts-functions_basic.php: Basic functions for the Phoronix Test Suite

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

function pts_add_trailing_slash($path)
{
	return $path . (substr($path, -1) == '/' ? null : '/'); 
}
function pts_first_element_in_array($array)
{
	// Using this helper function will avoid a PHP E_STRICT warning if just using the code directly from the output of a function/object
	return reset($array);
}
function pts_last_element_in_array($array)
{
	// Using this helper function will avoid a PHP E_STRICT warning if just using the code directly from the output of a function/object
	return end($array);
}
function pts_mkdir($dir, $mode = 0777, $recursive = false)
{
	return !is_dir($dir) && mkdir($dir, $mode, $recursive);
}
function pts_glob($pattern, $flags = 0)
{
	$r = glob($pattern, $flags);
	return is_array($r) ? $r : array();
}
function pts_rmdir($dir)
{
	return is_dir($dir) && rmdir($dir);
}
function pts_unlink($file)
{
	return is_file($file) && unlink($file);
}
function pts_array_push(&$array, $to_push)
{
	return !in_array($to_push, $array) && array_push($array, $to_push);
}
function pts_empty($var)
{
	return trim($var) == null;
}
function pts_file_get_contents($filename, $flags = 0, $context = null)
{
	return trim(file_get_contents($filename, $flags, $context));
}
function pts_version_comparable($old, $new)
{
	// Checks if there's a major version difference between two strings, if so returns false.
	// If the same or only a minor difference, returns true.

	$old = explode('.', pts_strings::keep_in_string($old, TYPE_CHAR_NUMERIC | TYPE_CHAR_DECIMAL));
	$new = explode('.', pts_strings::keep_in_string($new, TYPE_CHAR_NUMERIC | TYPE_CHAR_DECIMAL));
	$compare = true;

	if(count($old) >= 2 && count($new) >= 2)
	{
		if($old[0] != $new[0] || $old[1] != $new[1])
		{
			$compare = false;
		}
	}

	return $compare;	
}
function pts_array_with_key_to_2d($array)
{
	$array_2d = array();

	foreach($array as $key => $value)
	{
		array_push($array_2d, array($key, $value));
	}

	return $array_2d;
}
function pts_extract_identifier_from_path($path)
{
	return substr(($d = dirname($path)), strrpos($d, "/") + 1);
}
function pts_to_array($var)
{
	return !is_array($var) ? array($var) : $var;
}

function pts_exec($exec, $extra_vars = null)
{
	// Same as shell_exec() but with the PTS env variables added in
	return shell_exec(pts_variables_export_string($extra_vars) . $exec);
}
function pts_remove($object, $ignore_files = null, $remove_root_directory = false)
{
	if(is_dir($object))
	{
		$object = pts_add_trailing_slash($object);
	}

	foreach(pts_glob($object . "*") as $to_remove)
	{
		if(is_file($to_remove))
		{
			if(is_array($ignore_files) && in_array(basename($to_remove), $ignore_files))
			{
				continue; // Don't remove the file
			}
			else
			{
				@unlink($to_remove);
			}
		}
		else if(is_dir($to_remove))
		{
			pts_remove($to_remove, $ignore_files, true);
		}
	}

	if($remove_root_directory && is_dir($object) && count(pts_glob($object . "/*")) == 0)
	{
		@rmdir($object);
	}
}
function pts_copy($from, $to)
{
	// Copies a file
	if(!is_file($to) || md5_file($from) != md5_file($to))
	{
		copy($from, $to);
	}
}
function pts_rename($from, $to)
{
	return rename($from, $to);
}
function pts_symlink($from, $to)
{
	return @symlink($from, $to);
}
function pts_move($from, $to)
{
	return rename($from, $to);
}

?>
