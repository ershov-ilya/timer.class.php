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

// Default timer (unnamed)
$timer->start();
usleep(500000);
$timer->stop();
print 'Unnamed time result: '.$timer().PHP_EOL;

$timer->start('one');
usleep(100000);
$timer->stop('one');
print $timer;

// Sum of gaps
$timer->start('gap');
usleep(100000);
$timer->stop('gap');
$timer->start('gap');
usleep(200000);
$timer->stop('gap');
$timer->start('gap');
usleep(300000);
$timer->stop('gap');
// Get sum of all gaps
print 'Get sum of all gaps: '.$timer('gap').PHP_EOL; // returns ~ 0.6 (sec)

$timer->start('file');
$timer->start('mysql.sql.query.response.parsing',true);
$timer->start('postgres.sql.query.response.parsing');
usleep(300000);
$timer->stopTree('response');
usleep(200000);
$timer->start('file.read');
usleep(400000);
$timer->stopTree('mysql');
print $timer;

