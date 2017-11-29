<?php 
function get_search_type($path)
{
    if (file_exists($path)) {
        if (is_link($path)) {
            return colorize('LINK', "PAINT_BLUE");
        }
        if (is_file($path)) {
            return colorize('FILE', "PAINT_BLUE");
        } elseif (is_dir($path)) {
            return colorize('DIRECTORY', "PAINT_BLUE");
        }
    }
}


/**
* Color the output
*/
function colorize($text, $status)
{
    $out = "";
    switch ($status) {
  case "SUCCESS":
   $out = "[42m"; //Green background
   break;
  case "FAILURE":
   $out = "[41m"; //Red background
   break;
  case "WARNING":
   $out = "[43m"; //Yellow background
   break;
  case "NOTE":
   $out = "[44m"; //Blue background
   break;
  case "PAINT_GREEN":
   $out = "[32m"; //Blue background
   break;
  case "PAINT_RED":
   $out = "[31m"; //Blue background
   break;
  case "PAINT_YELLOW":
   $out = "[33m"; //Blue background
   break;
  case "PAINT_BLUE":
   $out = "[36m"; //Blue background
   break;
  default:
   throw new Exception("Invalid status: " . $status);
 }
    return chr(27) . "$out" . "$text" . chr(27) . "[0m";
}

function like($needle, $haystack, $case_sensitive)
{
    if ($case_sensitive) {
        return (strpos($haystack, $needle) !== false);
    } else {
        return (stripos($haystack, $needle) !== false);
    }
}
