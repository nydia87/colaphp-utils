# colaphp-util

版本^1.0
# Config配置、支持加载PHP文件
版本^1.1
# Log日志、File文件
版本^1.2
# Upload上传、Image图片、Verify验证码、Base64加密

```php
public function load(){} //加载文件
public function set(){} //设置
public function get(){} //获取
public function remove(){} //移除
function config() //助手函数
```

# Debug调试

```php
public static dump() //友好的变量输出.
function dump() //助手函数
```

# Env环境变量、支持加载ini、env文件

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