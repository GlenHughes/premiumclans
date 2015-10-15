<?php

namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;

class AlbumTable {
	
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getAlbum($id) {
		$id = (int) $id;
		$rowSet = $this->tableGateway->select(array('id' => $id));
		$row = $rowSet->current();
		if (!$row) {
			throw new \Exception('Unable to find '.$id);
		}
		return $row;
	}

	public function saveAlbum (Album $album) {
		$data = [
			'artist' => $album->artist,
			'title' => $album->title
		];

		$id = (int) $album->id;
		if ($id === 0) {
			$this->tableGateway->insert($data);
		}
		else {
			if ($this->getAlbum($id)) {
				$this->tableGateway->update($data);
			}
			else {
				throw new \Exception('Album does not exists!');
			}
		}
	}

	public function deleteAlbum($id) {
		$this->tableGateway->delete(array('id' => (int) $id));
	}

}