<?php

namespace Users\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersTable {
	
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getUser($id) {
		$id = (int) $id;
		$rowSet = $this->tableGateway->select(array('id' => $id));
		$row = $rowSet->current();
		if (!$row) {
			throw new \Exception('Unable to find '.$id);
		}
		return $row;
	}

	public function saveUser (User $user) {
		$data = [
			'artist' => $user->artist,
			'title' => $user->title
		];

		$id = (int) $user->id;
		if ($id === 0) {
			$this->tableGateway->insert($data);
		}
		else {
			if ($this->getUser($id)) {
				$this->tableGateway->update($data);
			}
			else {
				throw new \Exception('User does not exists!');
			}
		}
	}

	public function deleteUser($id) {
		$this->tableGateway->delete(array('id' => (int) $id));
	}

}