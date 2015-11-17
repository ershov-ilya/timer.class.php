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
    private $result;
    private $data;
    private $config;

    public function __construct($props=array()){
        $config=array(
            'query_delimiter'   =>  ':',
            'output_delimiter'  =>  '=',
            'add_children_time'  => true
        );
        $this->config=$config=array_merge($config, $props);
        $this->state=array();
        $this->result=array();
        $this->data=array();
    }
    public function __destruct()
    {
        $this->stopAll();
    }

    private function parse($name){
        return explode($this->config['query_delimiter'], $name);
    }

    public function start($name){
        if(empty($name)) return false;
        if(isset($this->state[$name]) && $this->state[$name]) {
            if(DEBUG) print "Double start $name - ignore\n";
            return false;
        }
        if(DEBUG) print "Start $name\n";
        $this->state[$name]=true;

        $ptr =& $this->getNode($name);

        $ptr['_start']=microtime(true);
        return true;
    }

    public function stop($name){
        $stoptime=microtime(true);
        if(!isset($this->state[$name]) || !$this->state[$name]) {
            if(DEBUG) print "Double stop $name - ignore\n";
            return false;
        }
        if(DEBUG) print "Stop $name\n";
        $this->state[$name]=false;

        $ptr =& $this->getNode($name, false);

        if(!isset($ptr['_time'])) $ptr['_time']=array();
        $time=$stoptime-$ptr['_start'];
        $ptr['_time'][]=$time;
        unset($ptr['_start']);
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
        foreach($this->state as $name => $status){
            $output.="$name".$this->config['output_delimiter'].$this($name)."\n";
        }
        return $output;
    }

    public function __invoke($name){
        $name=trim($name,$this->config['query_delimiter']);
        $time=microtime(true);

//        if(DEBUG) print "Invoke method $name\n";
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
        if(isset($ptr['_time'])) {
            foreach ($ptr['_time'] as $time) {
                $total += $time;
            }
        }
        if(isset($this->state[$name]) && $this->state[$name] && isset($ptr['_start'])) $total+=$time-$ptr['_start'];
        $this->result[$name]=$total;
        return $total;
    }

    private function &getNode($name, $create=true){
        $name=trim($name,$this->config['query_delimiter']);
        $path=$this->parse($name);
        $ptr=null;
        $level=0;
        $url=array();
        foreach($path as $node){
            $url[]=$node;
            if($level===0) {
                if($create && !isset($this->data[$node])) {
                    $this->data[$node]=array();
                    $this->data[$node]['_name']=$node;
                }
                $ptr =& $this->data[$node];
            }else{
                if($create && !isset($ptr[$node])) {
                    $ptr[$node]=array();
                    $ptr[$node]['_name']=implode($this->config['query_delimiter'],$url);
                }
                $ptr =& $ptr[$node];
            }
            $level++;
        }
        return $ptr;
    }

    public function data(){
        return $this->data;
    }

    public function result(){
        return $this->result;
    }
}
