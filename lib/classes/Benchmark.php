<?php 
/**
 * Benchmark
 *
 * An simple class form bench-marking scripts and monitoring memory usage.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package PhpUtils
 * @author  Russell Japheth <japheth.russell.i@gmail.com>
 * @copyright   Russell Japheth (http://github.com/RussellJapheth)
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link    http://about.me/russelljapheth
 * @version   Version 1.0.0
 * @filesource Benchmark.php
 */

namespace RussellJapheth\PhpUtils;

/**
 * The Benchmark class
 *
 *
 * @package     PhpUtils
 * @subpackage  Benchmark
 * @category    Library
 * @author      Russell Japheth <japheth.russell.i@gmail.com>
 * @link        http://about.me/russelljapheth
 * @todo
 *1. Fix the monitor() method
 */
class Benchmark
{
    /**
    *
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @var float $time_start This is the time in microseconds at the start of benchmarking
    */
    protected $time_start = 0;

    /**
    *
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @var float $time_end This is the time in microseconds at the end of benchmarking
    */
    protected $time_end = 0;

    /**
    *
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @var float $mem_start This is the amount of memory used at the start of benchmarking
    */
    protected $mem_start = 0;

    /**
    *
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @var float $mem_end This is the amount of memory used at the end of benchmarking
    */
    protected $mem_end = 0;

    /**
    *
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @var string $desc This is a short description for the summary
    */
    protected $desc = '';

    /**
    * Start benchmarking
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @param string $desc This is a short description for the summary.
    * @return null
    */
    public function start($desc='')
    {
        $this->time_start = microtime(true);
        $this->mem_start = memory_get_peak_usage(true);
        $this->desc = $desc;
    }

    /**
    * Finish benchmarking
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @return null
    */
    public function end()
    {
        $this->time_end = microtime(true);
        $this->mem_end = memory_get_peak_usage(true);
    }

    /**
    * Returns a summary of the results, both time and memory
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @return string
    */
    public function summary()
    {
        //Fixed all files in 0.001 seconds, 6.000 MB memory used

        return 'Search completed in '.round(($this->time_end - $this->time_start), 3).' seconds, '.$this->mem_diff().' MB memory used.';
    }

    /**
    * Reset results
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @return null
    */
    public function reset()
    {
        $this->time_end = $this->time_start = 0;
        $this->mem_end = $this->mem_start = 0;
        $this->desc = '';
    }

    /**
    * Return the amount of time it took the script to finish executing in seconds
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @return float
    */
    public function time_diff()
    {
        return round(($this->time_end - $this->time_start), 3);
    }

    /**
    * Return the memory used by the script in megabytes
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @return float
    */
    public function mem_diff()
    {
        return round(((($this->mem_end - $this->mem_start) /1024) /1024), 3);
    }

    /**
    * Monitor the execution of an anonymous function
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @param string $desc This is a short description for the summary.
    * @param function $function This is an anonymous function to be monitored.
    * @return null
    */
    public function monitor($desc = '', $function)
    {
        $this->start($desc);

        $function();

        $this->end($desc);
    }

    /**
    * Log the summary to a file
    *
    * @package PhpUtils
    * @subpackage Benchmark
    * @param string $logfile This is a relative or absolute path to the log file
    * @param boolean $append Should the file be appended to or rewritten (Default: true).
    * @param string $newline This is the newline character to use (Default: \r\n for windows support).
    * @return null
    */
    public function log($logfile, $append= true, $newline = "\r\n")
    {
        if ($append == true) {
            $mode = "a+";
        } else {
            $mode = "w+";
        }

        $fh =  fopen($logfile, $mode);
        fwrite($fh, $this->summary().$newline);
        return fclose($fh);
    }
}
