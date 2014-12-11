<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Configure;

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html ng-app="MyApp">
<head>
	<?= $this->Html->charset() ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?= $cakeDescription ?>:
		<?= $this->fetch('title') ?>
	</title>
	<?= $this->Html->meta('icon') ?>

	<?= $this->Html->css('base.css') ?>
	<?= $this->Html->css('cake.css') ?>

	<?= $this->fetch('meta') ?>
	<?= $this->fetch('css') ?>
	<?= $this->fetch('script') ?>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>
	<?= $this->Html->script('satellizer-opauth') ?>
	<script>
		angular.module('MyApp', ['satellizer'])
			.config(function($authProvider) {


				// Opauth 2.0 strategy:
				$authProvider.opauth({
					name: 'github-opauth',
					url:'login/github', // code gets POST'ed here
					authorizationEndpoint:'login/github', // Initial popup opens here
				});

				// Opauth 1.0 strategy (not implemented in UsersController::opauth yet):
				$authProvider.opauth({
					name: 'twitter-opauth',
					url:'login/twitter', // code gets POST'ed here
					authorizationEndpoint:'login/twitter', // Initial popup opens here
					popupOptions: { width: 2020, height: 318 }
				});

				// Satellizer default connectors:
				$authProvider.github({
					clientId: '<?= Configure::read('Opauth.Strategy.GitHub.client_id'); ?>',
					name: 'github',
					url: '/login/github',
					redirectUri: window.location.protocol + '//' + window.location.host + '/login/github/callback',
					scope: [],
					scopeDelimiter: ' ',
					type: '2.0',
					popupOptions: { width: 1020, height: 618 }
				});
				$authProvider.twitter({
					url: '/login/twitter',
					type: '1.0',
					popupOptions: { width: 495, height: 645 }
				});
				$authProvider.facebook({
					url: '/login/facebook',
					clientId: '<?= Configure::read('Opauth.Strategy.Facebook.app_id'); ?>',
					authorizationEndpoint: 'https://www.facebook.com/dialog/oauth',
					redirectUri: window.location.protocol + '//' + window.location.host + '/login/facebook/callback',
					scope: 'email',
					scopeDelimiter: ',',
					requiredUrlParams: ['display', 'scope'],
					display: 'popup',
					type: '2.0',
					popupOptions: { width: 481, height: 269 }
				});
			});
		angular.module('MyApp')
			.controller('LoginCtrl', function($scope, $auth) {

		        $scope.isAuthenticated = function() {
		            return $auth.isAuthenticated();
		        };

		        $scope.logout = function() {
					$auth.logout();
		        };

				$scope.authenticate = function(provider) {
					$auth.authenticate(provider);
				};

			});
	</script>
</head>
<body ng-controller="LoginCtrl">
	<header>
		<div class="header-title">
			<span><?= $this->fetch('title') ?></span>
		</div>
		<div class="header-help">
			<span><a target="_blank" href="http://book.cakephp.org/3.0/">Documentation</a></span>
			<span><a target="_blank" href="http://api.cakephp.org/3.0/">API</a></span>
		</div>
	</header>
	<div id="container">
		<h1>Opauth test app</h1>

		Current status is:

		<div ng-if="isAuthenticated()">
			AUTHENTICATED! :D

			<br><br>
			You can <button ng-click="logout()">Log out now.</button>
		</div>
		<div ng-if="!isAuthenticated()">not AUTHENTICATED! :(</div>

		<span><?= $this->Html->link('Home', '/');?></span>
		<ul>
			<li><?= $this->Html->link('Twitter', ['controller' => 'Users', 'action' => 'opauth', 'twitter']);?></li>
			<li><?= $this->Html->link('Facebook', ['controller' => 'Users', 'action' => 'opauth', 'facebook']);?></li>
			<li><?= $this->Html->link('Github', ['controller' => 'Users', 'action' => 'opauth', 'github']);?></li>
			<li><button ng-click="authenticate('twitter')">Sign in with Twitter</button></li>
			<li><button ng-click="authenticate('facebook')">Sign in with Facebook</button></li>
			<li><button ng-click="authenticate('github')">Sign in with GitHub</button></li>
			<li><button ng-click="authenticate('github-opauth')">Sign in with github-opauth TESTING</button></li>
			<li><button ng-click="authenticate('twitter-opauth')">Sign in with twitter-opauth TESTING</button></li>
		</ul>
		<div id="content">
			<?= $this->Flash->render() ?>

			<div class="row">
				<?= $this->fetch('content') ?>
			</div>
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
