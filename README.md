# kjBot [![CodeFactor](https://www.codefactor.io/repository/github/kj415j45/kjbot/badge/master)](https://www.codefactor.io/repository/github/kj415j45/kjbot/overview/master)

kjBot 是一个简单易用的酷Q机器人框架。  
因为面向对象太难了（其实就是我不会），所以框架本身其实是面向过程编写的，面向对象只是工具而已。

~~不懂什么 PSR-4~~

## 框架结构

```
/
|--Open Source Licenses/ #存放开源许可证
|--SDK/ #kjBot\SDK 存放处
|--public/
    |--tools/ #各类开放函数的文件
    |--index.php #入口文件
    |--init.php #初始化用
    |......
|--vendor/ #如果你用composer的话应该知道这玩意是干嘛的（反正我不会
|--storage/ 
    |--data/ #数据文件夹
        |--error.log #如果出现异常未捕获则会在此存放日志
        |......
    |--cache/ #缓存文件夹
|--middleWare/ #中间件，用于处理非命令
    |--Chain.php #中间件链，用于调整中间件顺序以及启用状态
    |--......
|--module/ #在这里开始编写你的模块吧 :)
    |--......
|--config.ini.example #配置文件样例，本地部署时请复制为 config.ini 并根据实际情况调整
|--build.sh #进行环境配置
|--run.sh #一键部署（大概 :v
```

## 上手

### 入门

`public/init.php` 中存在一个全局变量区供编写模块的程序员使用，约定本框架产生的全局变量均为大写字母开头。  
`public/tools/` 下的全部文件将为框架扩展各类方法，请仔细阅读。

### 编写第一个模块

假定该模块为 `hello/main.php` ，向 bot 发送 `!hello` 即可触发该模块。
```php
<?php

global $Queue; //从全局变量区中导入 $Queue 数组，该数组提供消息队列的功能

if(!fromGroup()){ //如果消息不是来自群聊
    $Queue[]= sendBack('Hello, world!'); //向消息队列尾插入一句 'Hello, world!'，在哪收到就发到哪，此处只会在私聊中发送
}else{
    leave(); //从模块中退出，不再执行下面的语句
}

?>
```

### 编写更多模块

参考 `module/` 文件夹下的其他模块

## LICENSE

kjBot 框架及 SDK 均为 MIT 协议。但是一切模块均为 AGPL 协议，如果您希望闭源开发，请注意不要使用 `module/` 文件夹下的任何文件，谢谢 :)

## 捐赠

如果您喜欢本框架，请随意捐赠
![Alipay](https://user-images.githubusercontent.com/18349191/41342369-a703b31a-6f2e-11e8-98eb-cd39f5642ea3.jpg)
![WeChat](https://user-images.githubusercontent.com/18349191/41342554-1aeada6a-6f2f-11e8-9b51-cab50c182648.png)
