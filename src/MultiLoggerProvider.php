<?php
/**
 * Multi-logger
 *
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  multilogger
 */

namespace anlutro\MultiLogger;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider that replaces the default Illuminate\Log\LogServiceProvider.
 *
 * The Log:: facade as well as $app['log'] will work as before, but obviously
 * without the Laravel-specific methods and features.
 */
class MultiLoggerProvider
{
	public function register()
	{
		$this->app->instance('log', new MultiLogger);
	}
}
