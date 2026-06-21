<?php
/**
 * @author: nydia87 <349196713@qq.com>
 * @description:
 */

namespace Colaphp\Utils;

class Env
{
	/**
	 * 环境变量数据.
	 *
	 * @var array
	 */
	protected static $data = [];

	/**
	 * 读取环境变量定义文件.
	 *
	 * @param string $file 环境变量定义文件
	 */
	public static function load($file)
	{
		$env = parse_ini_file($file, true);
		self::set($env);
	}

	/**
	 * 设置环境变量值
	 *
	 * @param array|string $env   环境变量
	 * @param mixed        $value 值
	 */
	public static function set($env, $value = null)
	{
		if (is_array($env)) {
			$env = array_change_key_case($env, CASE_UPPER);

			foreach ($env as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $k => $v) {
						self::$data[$key . '_' . strtoupper($k)] = $v;
					}
				} else {
					self::$data[$key] = $val;
				}
			}
		} else {
			$name = strtoupper(str_replace('.', '_', $env));

			self::$data[$name] = $value;
		}
	}

	/**
	 * 获取环境变量值
	 *
	 * @param string $name       环境变量名
	 * @param mixed  $default    默认值
	 * @param mixed  $php_prefix
	 *
	 * @return mixed
	 */
	public static function get($name = null, $default = null, $php_prefix = true)
	{
		if (is_null($name)) {
			return self::$data;
		}

		$name = strtoupper(str_replace('.', '_', $name));

		if (isset(self::$data[$name])) {
			return self::$data[$name];
		}

		return self::getEnv($name, $default, $php_prefix);
	}

	protected static function getEnv($name, $default = null, $php_prefix = true)
	{
		if ($php_prefix) {
			$name = 'PHP_' . $name;
		}

		$result = getenv($name);

		if (false === $result) {
			return $default;
		}

		if ('false' === $result) {
			$result = false;
		} elseif ('true' === $result) {
			$result = true;
		}

		if (! isset(self::$data[$name])) {
			self::$data[$name] = $result;
		}

		return $result;
	}
}
