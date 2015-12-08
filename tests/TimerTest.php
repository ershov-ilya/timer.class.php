<?php
class TimerTest extends PHPUnit_Framework_TestCase
{
    public function testSimpleTimerAccuracy()
    {
		// Init
        $timer=new Timer();

        // Process
		$timer->start();
		usleep(100000);
		$timer->stop();
		$value=$timer();
		
        // Assert
        $this->assertEquals(true, ($value<0.15 && $value>0.05));
    }
}