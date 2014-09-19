<?php
/**
 * Multi-logger
 *
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  multilogger
 */

namespace anlutro\MultiLogger;

use Closure;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * Multi-logger class.
 *
 * Keeps track of multiple channels - instances of Psr\Log\LoggerInterface - and
 * makes it easy to log to each of them.
 *
 * Log to a specic channel by chaining the to() method:
 *
 *   $logger->to('my_channel')->debug('my message');
 *
 * Log to the default channel by omitting the to() call:
 *
 *   $logger->debug('my message');
 */
class MultiLogger extends AbstractLogger implements LoggerInterface
{
	/**
	 * The name of the default channel to use.
	 *
	 * @var string
	 */
	protected $defaultChannel;

	/**
	 * The defined channels.
	 *
	 * @var array
	 */
	protected $channels = [];

	/**
	 * Create a new MultiLogger instance.
	 *
	 * @param string $defaultChannel
	 */
	public function __construct($defaultChannel = 'default')
	{
		$this->defaultChannel = $defaultChannel;
	}

	/**
	 * Set a channel instance.
	 *
	 * @param string          $channel
	 * @param LoggerInterface $logger
	 */
	public function setChannel($channel, LoggerInterface $logger)
	{
		$this->channels[$channel] = $logger;
	}

	/**
	 * Define a deferred channel. The closure will be invoked and the return
	 * value passed to setChannel. The return value must implement
	 * Psr\Log\LoggerInterface.
	 *
	 * @param string  $channel
	 * @param Closure $callback
	 */
	public function setDeferredChannel($channel, Closure $callback)
	{
		$this->channels[$channel] = $logger;
	}

	/**
	 * Log to a specific channel.
	 *
	 * @param  string $channel
	 *
	 * @return \Psr\Log\LoggerInterface
	 *
	 * @throws \InvalidArgumentException If the channel is not defined.
	 */
	public function to($channel)
	{
		if (!isset($this->channels[$channel])) {
			throw new \InvalidArgumentException("Undefined channel: $channel");
		}

		if ($this->channels[$channel] instanceof Closure) {
			$this->setChannel($channel, $this->channels[$channel]());
		}

		return $this->channels[$channel];
	}

	/**
	 * {@inheritdoc}
	 */
	public function log($level, $message, array $context = array())
	{
		$this->to($this->defaultChannel)
			->log($level, $message, $context);
	}
}
