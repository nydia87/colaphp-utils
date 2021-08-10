<?php
/**
 * @contact  nydia87 <349196713@qq.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 */
use Colaphp\Utils\Config;
use Colaphp\Utils\Debug;
use Colaphp\Utils\Env;

if (! function_exists('class_basename')) {
	/**
	 * 获取类名(不包含命名空间).
	 *
	 * @param object|string $class
	 * @return string
	 */
	function class_basename($class)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return basename(str_replace('\\', '/', $class));
	}
}

if (! function_exists('config')) {
	/**
	 * 获取和设置配置参数.
	 * @param array|string $name 参数名
	 * @param mixed $value 参数值
	 * @return mixed
	 */
	function config($name = '', $value = null)
	{
		if (is_null($value) && is_string($name)) {
			return Config::get($name);
		}

		return Config::set($name, $value);
	}
}

if (! function_exists('dump')) {
	/**
	 * 浏览器友好的变量输出.
	 * @param mixed $var 变量
	 * @param bool $echo 是否输出 默认为true 如果为false 则返回输出字符串
	 * @param string $label 标签 默认为空
	 * @return string|void
	 */
	function dump($var, $echo = true, $label = null)
	{
		return Debug::dump($var, $echo, $label);
	}
}

if (! function_exists('env')) {
	/**
	 * 获取环境变量值
	 * @param string $name 环境变量名（支持二级 .号分割）
	 * @param string $default 默认值
	 * @return mixed
	 */
	function env($name = null, $default = null)
	{
		return Env::get($name, $default);
	}
}

if (! function_exists('parse_name')) {
	/**
	 * 字符串命名风格转换
	 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
	 * @param string $name 字符串
	 * @param int $type 转换类型
	 * @param bool $ucfirst 首字母是否大写（驼峰规则）
	 * @return string
	 */
	function parse_name($name, $type = 0, $ucfirst = true)
	{
		if ($type) {
			$name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
				return strtoupper($match[1]);
			}, $name);

			return $ucfirst ? ucfirst($name) : lcfirst($name);
		}

		return strtolower(trim(preg_replace('/[A-Z]/', '_\\0', $name), '_'));
	}
}

if (! function_exists('redirect')) {
	/**
	 * @param mixed $url 重定向地址
	 * @param mixed $time
	 * @param mixed $msg
	 */
	function redirect($url, $time = 0, $msg = '')
	{
		//多行URL地址支持
		$url = str_replace(["\n", "\r"], '', $url);
		if (empty($msg)) {
			$msg = "系统将在{$time}秒之后自动跳转到{$url}！";
		}
		if (! headers_sent()) {
			// redirect
			if ($time === 0) {
				header('Location: ' . $url);
			} else {
				header("refresh:{$time};url={$url}");
				echo $msg;
			}

			exit();
		}
		$str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
		if ($time != 0) {
			$str .= $msg;
		}

		exit($str);
	}
}

if (! function_exists('xml_encode')) {
	/**
	 * XML编码
	 *
	 * @param array $data
	 * @param string $encoding
	 * @param string $root
	 */
	function xml_encode($data = [], $encoding = 'utf-8', $root = 'colaphp')
	{
		$xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
		$xml .= '<' . $root . '>';
		$xml .= data_to_xml($data);
		$xml .= '</' . $root . '>';

		return $xml;
	}
}

if (! function_exists('data_to_xml')) {
	/**
	 * XML编码 data.
	 *
	 * @param array $data
	 */
	function data_to_xml($data = [])
	{
		$xml = '';
		foreach ($data as $key => $val) {
			is_numeric($key) && $key = "item id=\"{$key}\"";
			$xml .= "<{$key}>";
			$xml .= (is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
			$key = current(explode(' ', $key));
			$xml .= "</{$key}>";
		}

		return $xml;
	}
}

if (! function_exists('cola_return')) {
	/**
	 * 返回Array结构.
	 *
	 * @param string $msg
	 * @param int $status
	 * @param array $data
	 */
	function cola_return($msg = '', $status = -1, $data = [])
	{
		$return = ['status' => $status, 'msg' => $msg];
		if (! empty($data)) {
			$return['data'] = $data;
		}

		return $return;
	}
}

if (! function_exists('cola_return_http')) {
	/**
	 * 返回数据到客户端.
	 *
	 * @param array $result
	 * @param string $type
	 */
	function cola_return_http($result = [], $type = 'JSON')
	{
		$type = strtoupper($type);

		switch ($type) {
			case 'XML':// 返回xml格式数据
				header('Content-Type:text/xml; charset=utf-8');
				
				exit(xml_encode($result));

				break;
			case 'EVAL':
				header('Content-Type:text/html; charset=utf-8');

				exit($result);

				break;
			case 'JSON'://JSON
			default:
				header('Content-Type:text/html; charset=utf-8');

				exit(json_encode($result));
		}

		exit;
	}
}
