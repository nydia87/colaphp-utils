<?php
/**
 * @contact  nydia87 <349196713@qq.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Colaphp\Utils;

/**
 * 日志操作.
 */
class Log
{
	const EMERGENCY = 'emergency';

	const ALERT = 'alert';

	const CRITICAL = 'critical';

	const ERROR = 'error';

	const WARNING = 'warning';

	const NOTICE = 'notice';

	const INFO = 'info';

	const DEBUG = 'debug';

	const SQL = 'sql';

	/**
	 * 日志信息.
	 * @var array
	 */
	protected $log = [];

	/**
	 * 配置参数.
	 * @var array
	 */
	protected $config = [];

	/**
	 * 日志写入驱动.
	 * @var object
	 */
	protected $driver;

	/**
	 * 日志授权key.
	 * @var string
	 */
	protected $key;

	/**
	 * 日志初始化.
	 * @param array $config
	 * @return $this
	 */
	public function init($config = [])
	{
		$this->config = $config;
		$this->driver = new File($config);

		return $this;
	}

	/**
	 * 获取日志信息.
	 * @param string $type 信息类型
	 * @return array
	 */
	public function getLog($type = '')
	{
		return $type ? $this->log[$type] : $this->log;
	}

	/**
	 * 记录日志信息.
	 * @param mixed $msg 日志信息
	 * @param string $type 日志级别
	 * @param array $context 替换内容
	 * @return $this
	 */
	public function record($msg, $type = 'info', array $context = [])
	{
		if (is_string($msg) && ! empty($context)) {
			$replace = [];
			foreach ($context as $key => $val) {
				$replace['{' . $key . '}'] = $val;
			}

			$msg = strtr($msg, $replace);
		}

		if (PHP_SAPI == 'cli') {
			// 命令行日志实时写入
			$this->write($msg, $type, true);
		} else {
			$this->log[$type][] = $msg;
		}

		return $this;
	}

	/**
	 * 清空日志信息.
	 * @return $this
	 */
	public function clear()
	{
		$this->log = [];

		return $this;
	}

	/**
	 * 保存调试信息.
	 * @return bool
	 */
	public function save()
	{
		if (empty($this->log)) {
			return true;
		}

		$log = [];

		foreach ($this->log as $level => $info) {
			if ($level == 'debug') {
				continue;
			}

			if (empty($this->config['level']) || in_array($level, $this->config['level'])) {
				$log[$level] = $info;
			}
		}

		$result = $this->driver->save($log, true);

		if ($result) {
			$this->log = [];
		}

		return $result;
	}

	/**
	 * 实时写入日志信息.
	 * @param mixed $msg 调试信息
	 * @param string $type 日志级别
	 * @param bool $force 是否强制写入
	 * @return bool
	 */
	public function write($msg, $type = 'info', $force = false)
	{
		// 封装日志信息
		if (empty($this->config['level'])) {
			$force = true;
		}

		if ($force === true || in_array($type, $this->config['level'])) {
			$log[$type][] = $msg;
		} else {
			return false;
		}

		// 写入日志
		return $this->driver->save($log, false);
	}

	/**
	 * 记录日志信息.
	 * @param string $level 日志级别
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function log($level, $message, array $context = [])
	{
		$this->record($message, $level, $context);
	}

	/**
	 * 记录emergency信息.
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function emergency($message, array $context = [])
	{
		$this->log(__FUNCTION__, $message, $context);
	}

	/**
	 * 记录警报信息.
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function alert($message, array $context = [])
	{
		$this->log(__FUNCTION__, $message, $context);
	}

	/**
	 * 记录紧急情况.
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function critical($message, array $context = [])
	{
		$this->log(__FUNCTION__, $message, $context);
	}

	/**
	 * 记录错误信息.
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function error($message, array $context = [])
	{
		$this->log(__FUNCTION__, $message, $context);
	}

	/**
	 * 记录warning信息.
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function warning($message, array $context = [])
	{
		$this->log(__FUNCTION__, $message, $context);
	}

	/**
	 * 记录notice信息.
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function notice($message, array $context = [])
	{
		$this->log(__FUNCTION__, $message, $context);
	}

	/**
	 * 记录一般信息.
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function info($message, array $context = [])
	{
		$this->log(__FUNCTION__, $message, $context);
	}

	/**
	 * 记录调试信息.
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function debug($message, array $context = [])
	{
		$this->log(__FUNCTION__, $message, $context);
	}

	/**
	 * 记录sql信息.
	 * @param mixed $message 日志信息
	 * @param array $context 替换内容
	 */
	public function sql($message, array $context = [])
	{
		$this->log(__FUNCTION__, $message, $context);
	}
}
