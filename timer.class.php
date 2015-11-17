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
    private $state;
    private $data;
    private $config;

    public function __construct($props=array()){
        $config=array(
            'delimiter' =>  ':'
        );
        $this->config=$config=array_merge($config, $props);
        $this->state=array();
        $this->data=array();
    }
    public function __destruct()
    {
        $this->stopAll();
    }

    private function parse($name){
        return explode($this->config['delimiter'], $name);
    }

    public function start($name=0){
        if($this->state[$name]) {
            if(DEBUG) print "Double start $name\n";
            return false;
        }
        if(DEBUG) print "Start $name\n";
        $this->state[$name]=true;

        $path=$this->parse($name);
        $ptr=null;
        $level=0;
        foreach($path as $node){
            if($level===0) {
                if(!isset($this->data[$node])) $this->data[$node]=array();
                $ptr =& $this->data[$node];
            }else{
                if(!isset($ptr[$node])) $ptr[$node]=array();
                $ptr =& $ptr[$node];
            }
            $level++;
        }
        $ptr['start']=microtime(true);
        return true;
    }

    public function stop($name){
        $stoptime=microtime(true);
        if(!$this->state[$name]) {
            if(DEBUG) print "Double stop $name\n";
            return false;
        }
        if(DEBUG) print "Stop $name\n";
        $this->state[$name]=false;

        $path=$this->parse($name);
        $ptr=null;
        $level=0;
        foreach($path as $node){
            if($level===0) {
//                if(!isset($this->data[$node])) $this->data[$node]=array();
                $ptr =& $this->data[$node];
            }else{
//                if(!isset($ptr[$node])) $ptr[$node]=array();
                $ptr =& $ptr[$node];
            }
            $level++;
        }

        if(!isset($ptr['time'])) $ptr['time']=array();
        $time=$stoptime-$ptr['start'];
        $ptr['time'][]=$time;
        unset($ptr['start']);
        return true;
    }

    public function stopAll(){
        if(DEBUG) print "Stop all $name\n";
        foreach($this->state as $k=>$v){
            if($v===true) $this->stop($k);
        }
    }
    public function __toString(){
        $output="";
        if(DEBUG) $output="Debug mode\n";
        return $output;
    }

    public function __invoke($name){
        if(DEBUG) print "Invoke method $name\n";
        $path=$this->parse($name);
        $ptr=null;
        $level=0;
        foreach($path as $node){
            if($level===0) {
//                if(!isset($this->data[$node])) $this->data[$node]=array();
                $ptr =& $this->data[$node];
            }else{
//                if(!isset($ptr[$node])) $ptr[$node]=array();
                $ptr =& $ptr[$node];
            }
            $level++;
        }

        $total=0;
        foreach($ptr['time'] as $time){
            $total+=$time;
        }
        return $time;
    }

    public function data(){
        return $this->data;
    }
}
