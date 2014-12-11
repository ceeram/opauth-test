<?php
namespace App\Controller;

use Cake\Core\Configure;
use Opauth\Opauth\Opauth;

class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

	public function opauth() {
		$config = Configure::read('Opauth');
		$Opauth = new Opauth($config);

		// NB: Only works with OAuth 2.0 atm.
		// Comment out 'return popup.pollPopup();' in satellizer-opauth.js
		// to stop window from closing automatically after this method is completed.

		// Satellizer, step 2: POST'ed CODE back to us:
		if ($this->request->is('post') && !empty($this->request->data['code'])) {

			$code = $this->request->data['code'];

			// $data = $Opauth->run($code); // @todo Ceeram

			// Custom code for handeling login based on $data goes here.
			// End result: return a token to Satellizer.

			// .. for now, let's just pretend we've logged in as a user and returned a token:
			$this->RequestHandler->renderAs($this, 'json');
			$this->set('token','replace-with-real-token');
			$this->set('_serialize', ['token']);

		// Satellizer, step 1: Initial request from Satellizer (in popup)
		} elseif (empty($this->request->query['code'])) {
			$data = $Opauth->run();
			$this->set('user', $data);

		// Non-satellizer use:
		} else {
			$data = 'We got nothing...';
			$this->set('user', $data);
		}


	}
}
