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
            'query_delimiter'   =>  '.',
            'output_delimiter'  =>  '=',
            'add_children_time' => true,
            'debug'             => false
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

    public function start($name='unnamed', $parents=false){
        if(empty($name)) return false;
        if($parents) return $this->startParents($name);
        if(isset($this->state[$name]) && $this->state[$name]) {
            if($this->config['debug']) print "Double start $name - ignore\n";
            return false;
        }
        $name=trim($name,$this->config['query_delimiter']);
        if($this->config['debug']) print "Start $name\n";
        $this->state[$name]=true;

        $ptr =& $this->getNode($name, true);

        $ptr['_start']=microtime(true);
        $ptr['_state']='ON';
        return true;
    }

    private function startParents($name){
        $name=trim($name,$this->config['query_delimiter']);
        $arr=explode($this->config['query_delimiter'], $name);
        $url=array();
        $path='';
        foreach($arr as $node){
            $url[]=$node;
            $path=implode($this->config['query_delimiter'],$url);
            $this->start($path);
        }
        return true;
    }

    public function stop($name='unnamed'){
        $stoptime=microtime(true);
        if(!isset($this->state[$name]) || !$this->state[$name]) {
            if($this->config['debug']) print "Double stop $name - ignore\n";
            return false;
        }
        if($this->config['debug']) print "Stop $name\n";
        $this->state[$name]=false;

        $ptr =& $this->getNode($name);

        $time=$stoptime-$ptr['_start'];
        $ptr['_time'][]=$time;
        $ptr['_state']='OFF';
        unset($ptr['_start']);
        return true;
    }

    public function stopTree($name){
        $name=trim($name,$this->config['query_delimiter']);
        foreach($this->state as $timer => $state){
            if($this->config['debug']) print "Check $timer\n";
            if(strpos($timer, $name)>-1){
                if($this->config['debug']) print "Match $timer\n";
                $this->stop($timer);
            }
        }
        return true;
    }

    public function stopAll(){
        if($this->config['debug']) print "Stop all\n";
        foreach($this->state as $k=>$v){
            if($v===true) $this->stop($k);
        }
    }
    public function __toString(){
        $output="";
        if($this->config['debug']) $output="Debug mode\n";
        foreach($this->state as $name => $status){
            $output.="$name".$this->config['output_delimiter'].$this($name)."\n";
        }
        return $output;
    }

    public function __invoke($name='unnamed'){
        $name=trim($name,$this->config['query_delimiter']);
        $time=microtime(true);

        $ptr =& $this->getNode($name);

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

    private function &getNode($name, $create=false){
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
                    $this->data[$node]['_state']='OFF';
                    $this->data[$node]['_time']=array();
                }
                $ptr =& $this->data[$node];
            }else{
                if($create && !isset($ptr[$node])) {
                    $ptr[$node]=array();
                    $ptr[$node]['_name']=implode($this->config['query_delimiter'],$url);
                    $ptr[$node]['_state']='OFF';;
                    $ptr[$node]['_time']=array();
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
