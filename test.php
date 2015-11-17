<?php
 /**
 * Created by PhpStorm.
 * Author:   ershov-ilya
 * GitHub:   https://github.com/ershov-ilya/
 * About me: http://about.me/ershov.ilya (EN)
 * Website:  http://ershov.pw/ (RU)
 * Date: 17.11.2015
 * Time: 11:01
 */


header( 'Content-Type: text/plain; charset=utf-8' ) ;
//define('DEBUG' , true) ;
defined( 'DEBUG') or define('DEBUG' , false) ;

if( DEBUG ){
    error_reporting(E_ERROR | E_WARNING ) ;
    ini_set( "display_errors" , 1 ) ;
}

require_once('timer.class.php');
$timer=new Timer();
$timer->start('db:query');
$timer->start('db:parse');
sleep(1);
$timer->start('db:query');
sleep(1);
$timer->stop('db:query');
$timer->stop('db:undefined');
$timer->start('db:query:parsing');
sleep(1);
$timer->stop('db:query');
$timer->stopAll();
$timer->start('db:undefined');
sleep(1.5);
print $timer;
sleep(1.5);
$timer->stopAll();

//print_r($timer->data());
print $timer;
print_r($timer->data());

