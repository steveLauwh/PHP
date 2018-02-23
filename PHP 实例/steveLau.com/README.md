## 基于 PHP 的 RESTful API 实例

RESTful(Representational State Transfer)：本质是一种软件架构风格，核心是面向资源。

解决的问题：降低开发的复杂性、提高系统的可伸缩性。

### RESTful 设计概念和准则

* 网络上的所有事物都可以被抽象为资源
* 每一个资源都有唯一的资源标识，对资源的操作不会改变这些标识
* 所有的操作都是无状态的

所谓“资源”，就是网络上的一个实体，或者说是网络上的一个具体信息。

### RESTful 应用场景

对资源型服务接口来说很合适，同时特别适合对于效率要求很高，但对安全要求不高的场景。

### 如何设计 RESTful API

* 资源路径(URI)：名词，使用复数
* HTTP 动词
* 过滤信息
* 状态码
* 错误处理
* 返回结果

### 本实例使用的工具

* Navicat for MySQL
* MySQL Workbench
* MySQL Server 5.7
* UPUPW-Apache PHP5.6
* DHC Client - 插件

### 运行

将 steveLau.com 文件夹所有内容拷贝到 UPUPW_AP5.6/vhosts 目录下，启动服务器，通过 DHC Client 来验证 RESTful API。

### 参考

* [HTTP](https://github.com/steveLauwh/TCP-IP/tree/master/HTTP)
* [理解RESTful架构](http://www.ruanyifeng.com/blog/2011/09/restful)
* [RESTful API 设计指南](http://www.ruanyifeng.com/blog/2014/05/restful_api)
