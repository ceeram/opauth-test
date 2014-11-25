<?php
namespace App\Controller;

use Cake\Core\Configure;
use Opauth\Opauth\Opauth;

class UsersController extends AppController {

	public function opauth() {
		$config = Configure::read('Opauth');
		$Opauth = new Opauth($config);
		$data = $Opauth->run();
		$this->set('user', $data);
	}
}
