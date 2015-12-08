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

require_once('dist/timer.class.php');
$timer=new Timer(array(
    'debug'=>true
));
$timer->start('one');
usleep(100000);
$timer->stopAll();
$timer->start();
usleep(100000);
$timer->stopAll();
print $timer;
exit;

$timer->start('file:read');
$timer->start('mysql:sql:query:response:parsing',true);
$timer->start('postgres:sql:query:response:parsing');
usleep(300000);
print_r($timer->data());
$timer->stop('query');
$timer->start('file:read');
usleep(500000);
$timer->stopTree('mysql');
print_r($timer->data());
print $timer;

