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
defined( 'DEBUG') or define('DEBUG' , false) ;

if( DEBUG ){
    error_reporting(E_ERROR | E_WARNING ) ;
    ini_set( "display_errors" , 1 ) ;
}

require_once('timer.class.php');
$timer=new Timer(array(
    'debug'=>true
));
$timer->start('mysql:sql:query:response:parsing',true);
$timer->start('postgres:sql:query:response:parsing');
sleep(1);
print_r($timer->data());
print $timer;
$timer->start('file:read');
sleep(1);
$timer->stopTree('mysql');
print_r($timer->data());

