<?php
class Tbs_Auth_Identity_Twitter extends Tbs_Auth_Identity_Generic
{
	protected $_api;

	public function __construct($token,$options)
	{
		$this->_api = new Tbs_Resource_Twitter($token,$options);
		$this->_name = 'twitter';
		$this->_id = $this->_api->getId();
	}

	public function getApi()
	{
		return $this->_api;
	}
}
