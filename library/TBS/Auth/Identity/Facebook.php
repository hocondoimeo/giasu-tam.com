<?php
class Tbs_Auth_Identity_Facebook extends Tbs_Auth_Identity_Generic
{
	protected $_api;

	public function __construct($token)
	{
		$this->_api = new Tbs_Resource_Facebook($token);
		$this->_name = 'facebook';
		$this->_id = $this->_api->getId();
	}

	public function getApi()
	{
		return $this->_api;
	}
}
