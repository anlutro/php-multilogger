<?php
namespace anlutro\MultiLogger\Tests;

use PHPUnit_Framework_TestCase;
use Mockery as m;

use Psr\Log\LogLevel;
use anlutro\MultiLogger\MultiLogger;

class MultiLoggerTest extends PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	public function makeMulti()
	{
		return new MultiLogger;
	}

	public function getMockLogger()
	{
		return m::mock('Psr\Log\LoggerInterface');
	}

	/** @test */
	public function logsToDefaultChannel()
	{
		$log = $this->makeMulti();
		$log->setChannel('default', $mock = $this->getMockLogger());
		$mock->shouldReceive('log')->with(LogLevel::DEBUG, 'message', [])->once();
		$log->debug('message');
	}

	/** @test */
	public function logsToSpecificChannel()
	{
		$log = $this->makeMulti();
		$log->setChannel('default', $mock = $this->getMockLogger());
		$mock->shouldReceive('log')->never();
		$mock->shouldReceive('debug')->never();
		$log->setChannel('specific', $mock = $this->getMockLogger());
		$mock->shouldReceive('debug')->with('message')->once();
		$log->to('specific')->debug('message');
	}
}
