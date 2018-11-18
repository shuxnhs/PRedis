# PRedis使用指南

## Redis简介

 1. Redis：remote dictionary server远程字典服务器
 2. Redis 是完全开源免费的，遵守BSD协议，是一个高性能的key-value数据库。
 3. Redis 与其他 key - value 缓存产品有以下三个特点：

- Redis支持数据的持久化，可以将内存中的数据保存在磁盘中，重启的时候可以再次加载进行使用。
- Redis不仅仅支持简单的key-value类型的数据，同时还提供list，set，zset，hash等数据结构的存储。
- Redis支持数据的备份，即master-slave模式的数据备份。

## PRedis简介
提供了一个Redis的网站客户端，不用每次在终端输入命令，可以直接使用PRedis直接操作。
```bash
redis-server /myredis/redis.conf 
redis-cli -p6379
```

## PRedis v1.0.1使用指南

> **本地测试环境**
> 系统：deepin15.8
> 软件：redis-5.0.0 
> 下面演示有右边在deepin上Terminal对比
> 下面结果返回的都是数组或者bool

 #### 1. 登录

 - 输入host地址或主机名：127.0.0.1
- 输入Redis配置的端口：  默认是**6379**


<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117115837710.png>
</div>

>输入不能为空或者输错，链接失败返回信息：
>```bash
>Uncaught exception 'PhpRedisException' with message '无法连接redis服务器：拒绝连接' 
>```





 #### 2. 验证是否登录
 如果没有登录，不允许进行数据操作，返回登录界面

<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117140432455.png>
</div>

#### 3. 验证是否连通：ping
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117140539900.png>
</div>


#### 4. 输入格式：option， key， value 
> **注意每个后面跟着“，”和空格“ ”**

#### 5. 查看数据库的容量：dbsize

<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117140931593.png>
</div>

#### 6. key
 - 查看所有的key：keys，*
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117141522162.png>
</div>

 - 设置key：set, key, value
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117141748164.png>
</div>

- 获取key的值:get, key
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117141927112.png>
</div>

- 删除key的值:del, key
<div align="center">
<img src= https://img-blog.csdnimg.cn/20181117142222310.png>
</div>

- 查看键过期时间：ttl, key
 <div align="center">
<img src= https://img-blog.csdnimg.cn/20181117142415135.png>
</div>

>-1表示永不过期，-2表示已过期
>如果过期get与keys *得不到


- 设置键过期时间：expire， key， seconds
 <div align="center">
<img src= https://img-blog.csdnimg.cn/20181117142731793.png>
</div>

>本地设置十秒的过期时间，但是终端显示7秒，原因：
>因为运行开始便开始计时， 等我输入终端已经过去了三秒
>十秒过去后：ttl显示为-2表示已经过期
><div align="center">
><img src= https://img-blog.csdnimg.cn/20181117142938463.png>
></div>


#### 7.string（单值单value）
- 查看字符串长度：strlen， key
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117143430837.png>
</div>

- 在字符串后面添加字符串：append， key， value
<div align="center">
<img src=https://img-blog.csdnimg.cn/2018111714364555.png>
</div>
>注意看终端，一开始是helllostring，添加后变成helllostringhello


- 数字的自增：incr， key
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117144101392.png>
</div>

>注意看终端，一开始是3，添加后变成4


- 数字的自增：dncr， key
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117144258546.png>
</div>

> 注意看终端，一开始是4，添加后变成3

- 设置键过期时间：setex， key， seconds， value
 <div align="center">
<img src= https://img-blog.csdnimg.cn/201811171446441.png>
</div>

>本地设置二十秒的过期时间，但是终端显示十秒，原因：
>因为运行开始便开始计时， 等我输入终端已经过去了十秒
>二十秒过去后：ttl显示为-2表示已经过期
><div align="center">
><img src= https://img-blog.csdnimg.cn/20181117144701928.png>
></div>


- 设置键：setnx， key， value
>set if not exist，如果不存在则设置值返回1，存在返回0

当key不存在返回的情况:1
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117145201954.png>
</div>

当key存在返回的情况：0
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117145313984.png>
</div>


- 设置多个值：mset, key1, value1, key2, value2, key3, value3........


 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117145752502.png>
</div>

- 获得多个值：mget， key1， key2， key3.......
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117145736458.png>
</div>


#### 8.list（单值多value）

- 插入：lpush/rpush， list， value
下面演示全部以lpush为例，rpush与lpush相反，具体请参考redis语法
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117150448121.png>
</div>

- 输出：lrange， list， 0， -1
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117150747356.png>
</div>

- pop弹出一个：lpop/rpop, list
下面演示全部以lpop为例，rpop与lpop相反，具体请参考redis语法
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117151213234.png>
</div>

>为什么弹出与终端不一样？
>因为5在网页已经pop，在终端是pop下一个4

- 取第x个：lindex， list， x

 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117151525422.png>
</div>


- 求list的长度：llen， list

 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117151732185.png>
</div>


- 将list第x位设置成值：lset， list， x， value
>注意列表是从0开始的
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117152843980.png>
</div>


- 值前插：linsert, list, before, value1, value2

 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117153639561.png>
</div>

- 值后插：linsert, list, after, value1, value2
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117153658823.png>
</div>

#### 8.set（单值多集合）
>与list的区别是不能插入重复值
- 插入：sadd, set, value1, value2, value3..........
当插入没有重复
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117155017325.png>
</div>

当插入重复时自动去掉重复
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117155039514.png>
</div>

- 查看集合：smembers, set 

 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117155142631.png>
</div>

- 查看set里面有几个值：scard， set
 <div align="center">
<img src=https://img-blog.csdnimg.cn/2018111715543413.png>
</div>

- 删除set里面的值：srem， set， value
>删除3，下面演示是否存在3
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117155644366.png>
</div>


- 判断集合里面是否存在值：sismember， set， value
当值存在返回1
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117160019151.png>
</div>

当值不存在返回0
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117160038731.png>
</div>

- 从集合中随机返回x个随机数：srandmember, set, x
 <div align="center">
<img src=https://img-blog.csdnimg.cn/20181117160544774.png>
</div>

- 从集合中pop一个：spop， set
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117160858494.png>
</div>


- 从集合1中第x个移到集合2：smove， set01， set02， x
>set04一开始只有7,8,9；从set01移入第一个
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117161151731.png>
</div>

- 求两个几个的交叉并集：
1.交集：sdiff, set01, set02
2.差集：sinter, set01, set02
3.并集：sunion, set01, set02
下面只演示并集
<div align="center">
<img src=https://img-blog.csdnimg.cn/2018111716154063.png>
</div>




#### 9. hash（KV模式不变，但V是一个键值对）
- 为散列设置值：hmset， name， key1， value1， key2， value2， key3， value3......
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117162914940.png>
</div>

- 查询：hmget，name， key1， key2， key3 

<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117163011329.png>
</div>

- 查看所有key：hkeys， name
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117163135222.png>
</div>

- 查看所有value：hvals， name

<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117163150714.png>
</div>

- 查询所有值：hgetall， name
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117163231762.png>
</div>


- 删除某个key：hdel， name， key

<div align="center">
<img src=https://img-blog.csdnimg.cn/2018111716333614.png>
</div>

- 判断是否有某个key：hexists， name， key
存在返回1
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117163458491.png>
</div>
不存在返回0
<div align="center">
<img src=https://img-blog.csdnimg.cn/20181117163531235.png>
</div>



#### 10.zset：参考set











