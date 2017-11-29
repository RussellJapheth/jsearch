#!/usr/bin/env php
<?php
//disable error reporting
// error_reporting(0);
//disable execution time limit
ini_set('max_execution_time', 0);

//composer's autoloader
require __DIR__.'/vendor/autoload.php';

//handle cli arguments an options
$strict = in_array('--strict', $_SERVER['argv']);
$arguments = new \cli\Arguments(compact('strict'));

//flags
$arguments->addFlag(array('help', 'h'), 'Show this help screen');
$arguments->addFlag(array('version', 'v'), "Display the program's version ");
$arguments->addFlag(array('result_first', 'r'), "Display matches as soon as they are found ");

//options
$arguments->addOption(array('search', 's'), array(
    'default'     => false,
    'description' => 'The text to search'));

$arguments->addOption(array('directory', 'd'), array(
    'default'     => false,
    'description' => 'The directory to search. (default: getcwd() ) '));

//parse the arguments and options
$arguments->parse();

//check if the help argument is set
if ($arguments['help']) {
    echo $arguments->getHelpScreen();
    echo "\r\n";
    echo "\r\n";
    \cli\out("NB: if called without any arguments, The program runs interactively.");
    echo "\r\n";
    \cli\out("The directory option can be ignored if you want to search the current directory.");
    echo "\r\n";
    echo "\r\n";
    // echo $arguments->asJSON();
    // echo "\r\n";
    exit(1);
}

//check if the version argument is set
if ($arguments['version']) {
    echo "Version ".file_get_contents(__DIR__.'/VERSION');
    echo "\r\n";
    exit(1);
}
$search_text = null;

//check if the search text has been entered
if ($arguments['search']) {
    $search_text = trim($arguments['search']);
}
if (iconv_strlen($search_text) < 1) {
    do {
        echo colorize("Please enter text to search:", "PAINT_GREEN");
        $handle = fopen("php://stdin", "rb");
        $search_text = trim(fgets($handle));
        fclose($handle);
    } while (iconv_strlen($search_text) < 1);
}

$search_dir = null;
//check if the search directory has been entered
if ($arguments['directory']) {
    $search_dir = trim($arguments['directory']);
}
if (!file_exists($search_dir)) {
    do {
        echo colorize("Please enter a directory to be searched:", "PAINT_GREEN");
        $handle = fopen("php://stdin", "rb");
        $search_dir = trim(fgets($handle));
        if ($search_dir == "") {
            echo colorize("Searching current directory...", "PAINT_RED")."\r\n";
            $search_dir = getcwd();
        }
        fclose($handle);
    } while (!file_exists($search_dir));
}
//check if the result first flag is set
if ($arguments['result_first']) {
    //setup benchmarking
    $benchmark = new RussellJapheth\PhpUtils\Benchmark();
    $benchmark->monitor('search', function () {
        //perform the search
        $res =  rf_search($GLOBALS['search_text'], $GLOBALS['search_dir'], true, false, true);
        echo "\r\n".colorize("Search finished:", "SUCCESS")." \r\n".colorize("Found ".count($res)." match(es)", "NOTE")." \r\n";
    });
    echo colorize($benchmark->summary(), 'SUCCESS')."\r\n\r\n";

    exit(1);
}
//setup benchmarking
$benchmark = new RussellJapheth\PhpUtils\Benchmark();
$benchmark->monitor('search', function () {
    //perform the search
    $res =  search($GLOBALS['search_text'], $GLOBALS['search_dir'], true, false, true);
    echo "\r\n".colorize("Search finished:", "SUCCESS")." \r\n".colorize("Found ".count($res)." match(es)", "NOTE")." \r\n";
});

//display search stats and benchmarking results
echo "\r\n".colorize('Searched '.$GLOBALS['file_count'].' item(s)', 'NOTE')."\r\n\r\n";
echo colorize($benchmark->summary(), 'SUCCESS')."\r\n\r\n";
