<?php

namespace Controller;

use \OSW3\Controller\Controller;
use \OSW3\Security\AuthentificationModel as Security;
use \Model\UsersModel;

class SecurityController extends Controller
{
	/**
	 * 
	 */
	public function signupAction()
	{
		if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['signup'])) 
		{
			$security = new Security;
			$users = new UsersModel;
			$uuid = Uuid::uuid4();

			
			// -- Retrieve $_POST data
			
			// init $post & $errors
			$post 		= $_POST['signup'];
			$errors 	= [];

			// retrieve $post data
			$firstname 	= isset($post['firstname']) ? $post['firstname'] : null;
			$lastname 	= isset($post['lastname']) 	? $post['lastname'] : null;
			$email 		= isset($post['email']) 	? $post['email'] : null;
			$password 	= isset($post['password']) 	? $post['password'] : null;
			$uuid		= $uuid->toString();
			

			// -- Check Firstname

			if (empty($firstname)) {
				$message = "Your firstname can't be empty";
				$errors['firstname'] = $message;
				$this->setFlashbag($message, 'danger');
			}


			// -- Check Lastname

			if (empty($lastname)) {
				$message = "Your lastname can't be empty";
				$errors['lastname'] = $message;
				$this->setFlashbag($message, 'danger');				
			}


			// -- Check email

			if (empty($email)) {
				$message = "Your email can't be empty";
				$errors['email'] = $message;
				$this->setFlashbag($message, 'danger');
			}


			// -- Check Password

			if (empty($password)) {
				$message = "Password can't be empty";
				$errors['password'] = $message;
				$this->setFlashbag($message, 'danger');
			}
			else {
				$password = $security->hashPassword($password);
			}


			// -- Check Account unicity

			if (empty($errors)) {
				if ($users->findByEmail($email)) {
					$message = "This user already exist";
					$errors['global'] = $message;
					$this->setFlashbag($message, 'danger');
				}
			}


			// -- Create User account

			if (!empty($errors)) 
			{
				$this->redirectToRoute('signin');
			}
			
			else 
			{
				$shortname = $firstname." ".substr($lastname, 0, 1).".";
				$roles = 'USER';

				// User data
				$user = [
                    "uuid" 		=> $uuid,
                    "firstname" => $firstname,
                    "lastname"	=> $lastname,
                    "shortname"	=> $shortname,
                    "email" 	=> $email,
                    "password" 	=> $password,
                    "roles"		=> $roles
				];

				// Save user in database
                $users->insert($user);

				// User connexion
                $security->logUserIn($user);

				// redirection
				$this->redirectToRoute('app-dashboard');
			}
		}
	}

	/**
	 * 
	 */
	public function signinAction()
	{
		if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['signin'])) 
		{
			$security = new Security;
			$users = new UsersModel;

			
			// -- Retrieve $_POST data
			
			// init $post & $errors
			$post 		= $_POST['signin'];
			$errors 	= [];

			// retrieve $post data
			$email 		= isset($post['email']) 	? $post['email'] : null;
			$password 	= isset($post['password']) 	? $post['password'] : null;

			
			// -- Retrieve User
			
			$user_id = $security->isValidLoginInfo($email, $password);

			$user = $users->find($user_id); 

			
			// -- Proceed to login

			// User connexion
			$security->logUserIn($user);

			// redirection
			$this->redirectToRoute('app-dashboard');
		}
	}

	/**
	 * Logout
	 */
	public function logoutAction()
	{
		$security = new Security;

		// user logout
		$security->logUserOut();

		// redirection
		$this->redirectToRoute('home');
	}
}