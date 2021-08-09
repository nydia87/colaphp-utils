<?php
/**
 * @contact  nydia87 <349196713@qq.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Colaphp\Utils;

class Debug
{
	/**
	 * 浏览器友好的变量输出.
	 * @param mixed $var 变量
	 * @param bool $echo 是否输出 默认为true 如果为false 则返回输出字符串
	 * @param string $label 标签 默认为空
	 * @param int $flags htmlspecialchars flags
	 * @return string|void
	 */
	public static function dump($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE)
	{
		$label = ($label === null) ? '' : rtrim($label) . ':';

		ob_start();
		var_dump($var);

		$output = ob_get_clean();
		$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);

		if (PHP_SAPI == 'cli') {
			$output = PHP_EOL . $label . $output . PHP_EOL;
		} else {
			if (! extension_loaded('xdebug')) {
				$output = htmlspecialchars($output, $flags);
			}
			$output = '<pre>' . $label . $output . '</pre>';
		}
		if ($echo) {
			echo $output;

			return;
		}

		return $output;
	}
}
