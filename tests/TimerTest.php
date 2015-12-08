<?php
class TimerTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleTimerAccuracy()
    {
		// Init
        $timer=new Timer();

        // Process
		$timer->start();
		usleep(50000);
		$timer->stop();
		$value=$timer();
		
        // Assert
        $this->assertEquals(true, ($value>0.045 && $value<0.55));
    }
	
    public function testStartParents()
    {
		// Init
        $timer=new Timer();

        // Process
		$timer->start('mysql.sql.query.response.parsing',true);
		usleep(50000);
		$value=$timer('mysql');
		
        // Assert
        $this->assertEquals(true, ($value>0.045 && $value<0.55));
    }
	
    public function testStopChildren()
    {
		// Init
        $timer=new Timer();

        // Process
		$timer->start('mysql.sql.query.response.parsing',true);
		usleep(50000);
		$timer->stop('mysql.sql');
		usleep(50000);
		$value=$timer('mysql.sql.query.response.parsing');
		
        // Assert
        $this->assertEquals(true, ($value>0.045 && $value<0.55));
    }
	
	
}