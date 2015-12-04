# Multi timer
with tree-structured dependences

### Simpe code example
```
require_once('timer.class.php');
$timer=new Timer();
$timer->start('mysql');
sleep(1);
print $timer('mysql');
$timer->stop('mysql');
print_r($timer->data()); // Массив с результатами
print $timer; // Вывести отчёт
```

This class can create timers with dependencies, counters will be stored in a tree structure.
Start and stop timers can be any number of times, in the end it will show the sum of time gaps.
__invoke function returns current time gaps sum, with NO stop it.

So here are advanced methods:
```
$timer->start('mysql:sql:query:response:parsing', true); // Второй параметр true говорит, что нужно стартануть все таймеры-родители
$timer->stopTree('mysql'); // Остановить всё дерево mysql
```

### Advanced code example
```
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
```

The class is configurable via config array, which can be passed as parameter of class constructor
```
$config=array(
    'query_delimiter'   =>  ':',
    'output_delimiter'  =>  '=',
    'add_children_time' => true,
    'debug'             => false
);
$timer=new Timer($config);
```
