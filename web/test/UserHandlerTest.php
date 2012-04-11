<?php
require_once dirname(__FILE__) . '/simpletest/autorun.php';
require_once dirname(__FILE__) . '/../model/SnippetHandler.php';
//require_once dirname(__FILE__) . '/../model/User.php';

class UserHanderTest extends UnitTestCase
{
	
	private $_userHandler;
	private $_userID;

	public function __construct() {
		$this->_userHandler = new UserHandler();	
	}

	function testIfUserCanBeInsertedInDatabase() {
		$this->_userID = $this->_userHandler->addUser('identifier', 'provider', 'name','email');
		$this->assertTrue($this->_userID);
	}

	function testIfInsertedUserExists() {
		$reuslt = $this->_userHandler->doesUserExist('email');
		$this->assertTrue($reuslt);
	}

	function testIfCanGetUserByEmailAndRegenerateApiKey() {
		//Get user
		$user = $this->_userHandler->getUserByEmail('email');
		$this->assertNotNull($user);
		$apiKey = $user->getApiKey();

		//change API-key
		$this->_userHandler->changeApiKey($user->getId());
		$user = $this->_userHandler->getUserByEmail('email');
		$this->assertNotEqual($apiKey, $user->getApiKey());

	}

	function testAllGetFunctionsByDifferentIdentifiers() {
		//by email
		$user = $this->_userHandler->getUserByEmail('email');
		$this->assertNotNull($user);
		
		//By identifier
		$user = $this->_userHandler->getUserByIdentifier('identifier');
		$this->assertNotNull($user);
	}

	function testIfRoleIdIsCorrect() {
		$role = $this->_userHandler->getRoleByID(1);
		$this->assertEqual('member', $role);

		$role = $this->_userHandler->getRoleByID(2);
		$this->assertEqual('admin', $role);

		$role = $this->_userHandler->getRoleByID(3);
		$this->assertEqual('moderator', $role);
	}

	function testIfCanChangeUserRole() {
		$user = $this->_userHandler->getUserByEmail('email');
		$role = $user->getRole();
		$this->assertEqual(1, $role);

		$this->_userHandler->changeUserRole($this->_userID, 2);
		$user = $this->_userHandler->getUserByEmail('email');
		$this->assertEqual($user->getRole(), 2);
	}

	function testIfUserCanBeDeleted() {
		$result = $this->_userHandler->deleteUser($this->_userID);
		$this->assertTrue($result);
	}
}