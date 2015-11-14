<?php
/**
 * Created by PhpStorm.
 * Author: ershov-ilya
 * Website: http://ershov.pw/
 * Date: 06.02.2015
 * Time: 10:26
 */

class Timer
{
    private $start_time;
    private $log;

    public function __construct(){
        $this->start_time=microtime(true);
    }

    public function mark($description)
    {
        $time=microtime(true) - $this->start_time;
        $element=array(
            'desc' => $description,
            'time' => $time
        );
        $this->log[] = $element;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function toString()
    {
        $output="Метки времени:\n";
        $i=1;
        foreach($this->log as $el)
        {
            $output.=$i.") ".$el['desc'].":\t".$el['time']."\n";
            $i++;
        }
        return $output;
    }

    public function show()
    {
        print $this->toString();
    }
}