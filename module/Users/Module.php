<?php

namespace Users;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Users\Model\User;
use Users\Model\UsersTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface {

	public function getAutoloaderConfig()
	{
		
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Users\Model\UsersTable' =>  function($sm) {
                     $tableGateway = $sm->get('UsersTableGateway');
                     $table = new UsersTable($tableGateway);
                     return $table;
                 },
                 'UsersTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new User());
                     return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                 },
             ),
         );
     }

}