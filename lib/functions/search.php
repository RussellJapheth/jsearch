<?php 
/**
*This function searches for items in a directory. It can search recursively and it also supports regular expressions
***/
function search($search, $dir = __DIR__, $recursive = true, $regexp = false, $output = false, $case_sensitive = false)
{
    if ($output) {
        ob_start();
        echo colorize("Search started....", "PAINT_BLUE")."\r\n";
        echo colorize("Searching for \"$search\" in \"".realpath($dir)."\"", "NOTE")."\r\n";
        ob_flush();
        flush();
    }
    $search = rtrim($search, "/");
    $search = rtrim($search, "\\");
    $res = array();
    if (file_exists($dir) && is_dir($dir)) {
        $dircon = @scandir($dir);
        $GLOBALS['file_count'] = 0;
        if (!empty($dircon)) {
            foreach ($dircon as $diritem) {
                if ($diritem != '.' && $diritem != '..') {
                    $GLOBALS['file_count']++;
                    if (!$regexp) {
                        if (like($search, $diritem, $case_sensitive)) {
                            $res[] = realpath($dir.'/'.$diritem);
                        }
                    } else {
                        if (preg_match($search, $diritem) == 1) {
                            $res[] = realpath($dir.'/'.$diritem);
                        }
                    }

                    if ($recursive) {
                        if (is_dir($dir.'/'.$diritem)) {
                            if ($output) {
                                echo colorize("Searching subdirectory \"".realpath($dir.'/'.$diritem)."\"", "PAINT_YELLOW")."\r\n";
                                ob_flush();
                                flush();
                            }
                            $res = array_merge_recursive($res, search($search, $dir.'/'.$diritem, true, false, false));
                        }
                    }
                }
            }
        }
    } else {
        return false;
    }
    if ($output) {
        echo "\r\n".colorize("Search finished:", "SUCCESS")." \r\n".colorize("Found ".count($res)." match(es)", "NOTE")." \r\n";
        ob_flush();
        flush();
        if (count($res) > 0) {
            $i = 1;
            foreach ($res as $match) {
                echo $i.": ".get_search_type(realpath($match))." ".str_ireplace($search, colorize($search, "WARNING"), realpath($match))." \r\n";
                $i++;
                ob_flush();
                flush();
            }
        }
    } else {
        return $res;
    }
    return $res;
}
