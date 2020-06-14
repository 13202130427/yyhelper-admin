<?php

$server = new Swoole\Websocket\Server("0.0.0.0",9501);
//设置参数
$server->set(array(
    'worker_num' => 4,
   'daemonize' => true  //后台运行
));
//事件回调绑定，监听网络事件

//监听连接进入事件
$server->on('Connect', function ($server, $fd) {
    echo "Client: true.\n";
});


//监听连接关闭事件
$server->on('Close', function ($server, $fd) {
    echo "Client: Close.\n";
});

//客户端发送消息过来
$server->on('message',function ($server,$frame){
    //开启redis
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $data =json_decode($frame->data,true);
    var_dump($data);
    //心跳机制
    if($data['type'] == 'rec'){
        $server->push($frame->fd,'pong');
    }
    if($data['type'] == 'bind'){
        $redis->set("uid_".$data['id'], $frame->fd);
    }
    if($data['type'] == 'broadcast'){
        //广播消息
        var_dump('开始广播');
        $server->push($data['fd'],$data['content']);
    }
    if($data['type'] == 'unbind'){
        $redis->del("uid_".$data['id']);
    }

});


//启动服务
$server->start();
