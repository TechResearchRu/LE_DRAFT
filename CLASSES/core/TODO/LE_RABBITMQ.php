<?php

/**
 * TEST BETA, need refactoring
 */
class LE_RABBITMQ
{
	protected $connection,$channel,$exchange;

	function __construct($inp=[])
	{
		if (!isset($inp['host'])) $inp['host']='127.0.0.1';
		$this->connection = $this->connect($inp['host'],$inp['login'],$inp['pass']);
		$this->channel = new AMQPChannel($this->connection);
		$this->exchange = new AMQPExchange($this->channel);
	}


	function connect($host,$login,$pass)
	{
		$connection = new AMQPConnection();
		$connection->setHost($host);
		$connection->setLogin($login);
		$connection->setPassword($pass);
		$connection->connect();
		return $connection;
	}

	function get_q($q)
	{
		$queue = new AMQPQueue($this->channel);
		$queue->setName($q);
		$queue->setFlags(AMQP_NOPARAM);
		$queue->declareQueue();
		return $queue;
	}

	function send_message($m,$q)
	{
		$qu = $this->get_q($q);
		
		$this->exchange->publish($m, $q);
	}

	function get_message($q,&$f)
	{
		$this->get_q($q)->consume($f);
	}


}