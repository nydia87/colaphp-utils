# colaphp-util

## 概述

工具包

## 使用

### 1、配置

```php
public function load(){} //加载文件
public function set(){} //设置
public function get(){} //获取
public function remove(){} //移除
function config() //助手函数
```

### 2、Debug调试

```php
public static dump() //友好的变量输出.
function dump() //助手函数
```

### 3、Env环境变量、支持加载ini、env文件

```php
public static load() //加载文件.
public static set() //设置.
public static get() //获取.
function env() //助手函数
```

```php
$config = [
    'time_format' => 'c',
    //单独记录true | string
    'single'      => false,
    'file_size'   => 2097152,
    'path'        => __DIR__,
    //独立日志的类型warning|error
    'apart_level' => [],
    'json'        => false,
];
$Log = new \Colaphp\Utils\Log();
$Log->init($config);
$Log->notice('hello');
$Log->save();
```

## 更新说明

|    版本   |    更新日期   |    说明              |
|:---------|:------------:|:---------------------|
| v1.2.3   |  2026.06.20  | 格式化代码            |
| v1.0.0   |  2021.07.30  | 基本功能              |