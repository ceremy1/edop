<?php
$tasks = array(

        array(
                'classname' => 'block_nisan_rozet\task\deloldlog',
                'blocking' => 0,
                'minute' => '00',
                'hour' => '03',
                'day' => '*',
                'dayofweek' => '*',
                'month' => '*'
        ),
        array(
                'classname' => 'block_nisan_rozet\task\sendmessage',
                'blocking' => 0,
                'minute' => '*/10',
                'hour' => '*',
                'day' => '*',
                'dayofweek' => '*',
                'month' => '*'
        ), array(
                'classname' => 'block_nisan_rozet\task\rozetbot',
                'blocking' => 0,
                'minute' => '*/30',
                'hour' => '*',
                'day' => '*',
                'dayofweek' => '*',
                'month' => '*'
        )
);
?>