# Multi timer
with tree-structured dependences

### Simpe code example
```
require_once('timer.class.php');
$timer=new Timer();

// Default timer (unnamed)
$timer->start();
usleep(200000);
$timer->stop();
print $timer();

// Named timer
$timer->start('mysql');
usleep(300000);
print $timer('mysql'); // Get value before timer stop - works OK too
$timer->stop('mysql');

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
print $timer('gap'); // returns ~ 0.6 (sec)

print_r($timer->data()); // Results array
print $timer; // Print report
```

This class can create timers with dependencies, counters will be stored in a tree structure.
Start and stop timers can be any number of times, in the end it will show the sum of time gaps.
__invoke function returns current time gaps sum, with NO stop it.

So here are advanced methods:
```
$timer->start('mysql.sql.query.response.parsing', true); // Second parameter in "true" says to start all parent-timers
$timer->stopTree('mysql'); // Stop timers tree "mysql"
```

### Advanced code example
```
require_once('timer.class.php');
$timer=new Timer(array(
'debug'=>true
));
$timer->start('mysql.sql.query.response.parsing',true);
$timer->start('postgres.sql.query.response.parsing');
usleep(200000);
print_r($timer->data());
print $timer;
$timer->start('file.read');
$timer->stopTree('mysql');
usleep(300000);
print_r($timer->data());
```

The class is configurable via config array, which can be passed as parameter of class constructor
```
$config=array(
    'query_delimiter'   =>  '.',
    'output_delimiter'  =>  '=',
    'add_children_time' => true,
    'debug'             => false
);
$timer=new Timer($config);
```

### PHPUnit test
`phpunit --bootstrap dist/timer.class.php tests\TimerTest.php`
