<?php
/**
 * @contact  nydia87 <349196713@qq.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 */
use Colaphp\Utils\Config;
use Colaphp\Utils\Debug;
use Colaphp\Utils\Env;
use Colaphp\Utils\Verify;

//包路径
define('COLAPHP_UTILS_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

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

if (! function_exists('mk_dir')) {
	/**
	 * 循环创建目录.
	 *
	 * @param string $dir
	 * @param int $mode
	 */
	function mk_dir($dir = '', $mode = 0777)
	{
		if (is_dir($dir) || @mkdir($dir, $mode)) {
			return true;
		}
		if (! mk_dir(dirname($dir), $mode)) {
			return false;
		}
		return @mkdir($dir, $mode);
	}
}

if (! function_exists('regex')) {
	/**
	 * 使用正则验证数据.
	 *
	 * @param string $value 字段值
	 * @param string $rule 验证规则 正则规则或者预定义正则名
	 */
	function regex($value, $rule)
	{
		$regexs = [
			'alphaDash' => '/^[A-Za-z0-9\-\_]+$/', //字母和数字，下划线_及破折号-
			'chs' => '/^[\x{4e00}-\x{9fa5}]+$/u', //汉字
			'chsAlpha' => '/^[\x{4e00}-\x{9fa5}a-zA-Z]+$/u', //汉字、字母
			'chsAlphaNum' => '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u', //汉字、字母和数字
			'chsDash' => '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_\-]+$/u', //汉字、字母、数字和下划线_及破折号-
			'mobile' => '/^1[3-9][0-9]\d{8}$/',
			'idCard' => '/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}$)/',
			'zip' => '/\d{6}/',
		];

		if (isset($regexs[$rule])) {
			$rule = $regexs[$rule];
		}

		if (strpos($rule, '/') !== 0 && ! preg_match('/\/[imsU]{0,4}$/', $rule)) {
			// 不是正则表达式则两端补上/
			$rule = '/^' . $rule . '$/';
		}

		return is_scalar($value) && preg_match($rule, (string) $value) === 1;
	}
}

if (! function_exists('cola_verify')) {
	/**
	 * 生成图片验证码
	 *
	 * @param string $id
	 * @param array $config
	 */
	function cola_verify($id = '', $config = [])
	{
		$verfiy = new Verify($config);
		$verfiy->entry($id);
	}
}

if (! function_exists('cola_verify_check')) {
	/**
	 * 核对验证码
	 *
	 * @param string $code
	 * @param string $id
	 */
	function cola_verify_check($code = '', $id = '')
	{
		$verfiy = new Verify();
		return $verfiy->check($code, $id);
	}
}


if (! function_exists('get_client_ip')) {
	/**
	 * 获取客户端IP地址
	 *
	 * @return void
	 */
	function get_client_ip() {
		static $ip = NULL;
		if ($ip !== NULL) return $ip;
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos =  array_search('unknown',$arr);
			if(false !== $pos) unset($arr[$pos]);
			$ip   =  trim($arr[0]);
		}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		// IP地址合法验证
		$ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
		return $ip;
	}
}