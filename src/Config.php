<?php
/**
 * @contact  nydia87 <349196713@qq.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Colaphp\Utils;

class Config
{
	/**
	 * 配置参数.
	 * @var array
	 */
	protected static $config = [];

	/**
	 * 配置前缀
	 * @var string
	 */
	protected static $prefix = 'colaphp:config';

	/**
	 * @param string $file 配置文件名
	 * @param mixed $name
	 * @return mixed
	 */
	public static function load($file, $name = '')
	{
		if (is_file($file)) {
			$type = pathinfo($file, PATHINFO_EXTENSION);
			if ($type == 'php') {
				return self::set(include $file, $name);
			}
		}

		return self::$config;
	}

	/**
	 * 获取配置参数 为空则获取所有配置.
	 * @param string $name 配置参数名（支持多级配置 .号分割）
	 * @param mixed $default 默认值
	 * @return mixed
	 */
	public static function get($name = null, $default = null)
	{
		if ($name && strpos($name, '.') === false) {
			$name = self::$prefix . '.' . $name;
		}

		// 无参数时获取所有
		if (empty($name)) {
			return self::$config;
		}

		$name = explode('.', $name);
		$name[0] = strtolower($name[0]);
		$config = self::$config;

		// 按.拆分成多维数组进行判断
		foreach ($name as $val) {
			if (isset($config[$val])) {
				$config = $config[$val];
			} else {
				return $default;
			}
		}

		return $config;
	}

	/**
	 * 设置配置参数 name为数组则为批量设置.
	 * @param array|string $name 配置参数名（支持三级配置 .号分割）
	 * @param mixed $value 配置值
	 * @return mixed
	 */
	public static function set($name, $value = null)
	{
		if (is_string($name)) {
			if (strpos($name, '.') === false) {
				$name = self::$prefix . '.' . $name;
			}

			$name = explode('.', $name, 3);

			if (count($name) == 2) {
				self::$config[strtolower($name[0])][$name[1]] = $value;
			} else {
				self::$config[strtolower($name[0])][$name[1]][$name[2]] = $value;
			}

			return $value;
		}
		if (is_array($name)) {
			// 批量设置
			if (! empty($value)) {
				if (isset(self::$config[$value])) {
					$result = array_merge(self::$config[$value], $name);
				} else {
					$result = $name;
				}

				self::$config[$value] = $result;
			} else {
				$result = self::$config = array_merge(self::$config, $name);
			}
		} else {
			// 为空直接返回 已有配置
			$result = self::$config;
		}

		return $result;
	}

	/**
	 * 移除配置.
	 * @param string $name 配置参数名（支持三级配置 .号分割）
	 */
	public static function remove($name)
	{
		if (strpos($name, '.') === false) {
			$name = self::$prefix . '.' . $name;
		}
		$name = explode('.', $name, 3);
		if (count($name) == 2) {
			unset(self::$config[strtolower($name[0])][$name[1]]);
		} else {
			unset(self::$config[strtolower($name[0])][$name[1]][$name[2]]);
		}
	}
}
