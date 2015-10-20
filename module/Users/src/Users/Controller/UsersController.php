<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Model\Users;
use Users\Form;

class UsersController extends AbstractActionController {

	protected $usersTable;

	public function indexAction() {
		return new ViewModel([
			'users' => $this->getUsersTable()->fetchAll()
		]);
	}

	public function addAction() {
		$form = new UserForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$user = new User();
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$user->exchangeArray($form->getData());
				$this->getUsersTable()->saveUser($user);

				return $this->redirect()->toRoute('users');
			}
		}

		return ['form' => $form];

	}

	public function editAction() {
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('users', ['action' => 'add']);
		}

		try {
			$user = $this->getUsersTable()->getUser($id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('users', ['action' => 'index']);
		}

		$form = new UserForm();
		$form->bind($user);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getUsersTable()->saveUser($user);

				return $this->redirect()->toRoute('users');
			}
		}

		return [
			'id' => $id,
			'form' => $form
		];
	}

	public function deleteAction() {
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('users');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');

			if ($del === 'Yes') {
				$id = (int) $request->getPost('id');
				$this->getUsersTable()->deleteUser($id);
			}

			return $this->redirect()->toRoute('users');
		}

		return [
			'id' => $id,
			'user' => $this->getUsersTable()->getUser($id)
		];
	}

	public function getUsersTable() {
		if (!$this->usersTable) {
			$sm = $this->getServiceLocator();
			$this->usersTable = $sm->get('Users\Model\UsersTable');
		}
		return $this->usersTable;
	}

}