# kjBot [![CodeFactor](https://www.codefactor.io/repository/github/kj415j45/kjbot/badge/master)](https://www.codefactor.io/repository/github/kj415j45/kjbot/overview/master)

kjBot 是一个简单易用的酷Q机器人框架，使用户可以专注于模块的开发而不必关注消息处理的细节。  
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
|--module/ #在这里开始编写你的模块吧 :)
    |......
|--config.ini.example #配置文件样例，本地部署时请复制为 config.ini 并根据实际情况调整
|--build.sh #进行环境配置
|--run.sh #一键部署（大概 :v
```

## 上手

打开 `module/` 文件夹，里面是 kjBot 运行时的全部源码，可以作为参考来编写新模块或者直接带走（
其他内容可以在 [wiki](https://github.com/kj415j45/kjBot/wiki) 查看（大概有

## LICENSE

kjBot 框架及 SDK 均为 MIT 协议。但是一切模块均为 AGPL 协议，如果您希望闭源开发，请注意不要使用 `module/` 文件夹下的任何文件，谢谢 :)

## 捐赠

如果您喜欢本框架，请随意捐赠
![Alipay](https://user-images.githubusercontent.com/18349191/41342369-a703b31a-6f2e-11e8-98eb-cd39f5642ea3.jpg)
![WeChat](https://user-images.githubusercontent.com/18349191/41342554-1aeada6a-6f2f-11e8-9b51-cab50c182648.png)
