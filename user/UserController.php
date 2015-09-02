<?
class UserController extends MadController {
	function indexAction() {
		$get = $this->params;
		$model = $this->model;
		$model->fetch( $this->user->id );
	}
	function viewAction() {
		$user = $this->user;
		$get = $this->params;
		if( $user->is('admin') && $get->id ) {
			$this->model->fetch( $get->id );
		} else {
			$this->model = $this->user;
		}
	}
	function writeAction() {
		$this->model->fetch( $this->params->id );
	}
	function saveAction() {
		$model = $this->model;
		$model->setData( $this->params );

		if ( preg_match( "!user/write$!", $this->router->referer ) ) {
			if ( ! $this->session->user ) {
				throw new Exception( 'need login.' );
			}
			if ( ! $this->session->user->isAdmin() ) {
				throw new Exception( 'you need login as admin.' );
			}
			if( ! $model->userLevel ) {
				$model->userLevel = 200;
			}
			$saveMode = 'write';
		} elseif ( preg_match( "!user/signup$!", $this->router->referer ) ) {
			$saveMode = 'signup';
			$model->userLevel = 300;
		} else {
			throw new Exception( 'illegal access' );
		}

		$result = $model->save();

		if ( ! $result ) {
			throw new Exception('Save Error');
		}

		// code
		if ( $saveMode == 'write' ) {
			return $result;
		}
		$json = new MadJson("$this->component/verify.json");
		$code = uniqid();
		$json->$code = $model->userId;
		$json->save();

		// message
		$message = new MadView( "$this->component/greetingMail.html" );
		$message->code = $code;
		$message->model = $model;

		$mail = new MadMail;
		$mail->to( "$model->userId <$model->email>" );
		$mail->from("스톰팩토리 <{$this->info->email}>");
		$mail->subject("스톰팩토리 메일 확인");
		$mail->message( $message );
		if ( ! $mail->send() ) {
			throw new Exception( 'Email sending failed' );
		}

		return $result;
	}
	function verifyAction() {
		$get = $this->params;
		$model = $this->model;

		$json = new MadJson("$this->component/verify.json");
		if ( ! $userId = $json->{$get->code} ) {
			throw new Exception( 'wrong code' );
		}
		$model->fetchUserId( $userId );
		$model->userLevel = 200;

		if ( ! $model->save() ) {
			throw new Exception( 'save error' );
		}
		unset( $json->{$get->code} );
		$json->save();
	}
	function deleteAction() {
		return $this->model->delete( $this->get->id );
	}
	function signinAction() {
	}
	function signoffAction() {
	}
	function googleAuthAction() {
		$post = $this->params;

		$google = $this->createModel( 'GoogleOAuth' );
		$google->setIdToken( $post->id_token );
		$google->auth();

		if ( $google->error_description ) {
			throw new Exception($google->error_description );
		}
		$model = $this->model;
		$model->fetchEmail( $google->email );
		if ( $model->id ) {
			$this->session->user = $model;
			return true;
		}
		$model->userId = $google->sub;
		$model->email = $google->email;
		$model->userLevel = 200;
		$id = $model->save();

		$model->fetch( $id );
		$this->session->user = $model;

		return true;
	}
	function loginAction() {
		if ( ! strpos($this->router->backUrl, 'user' ) ) {
			$this->session->after = $this->router->backUrl;
		}

		if( $this->user->isLogin() ) {
			$this->js->replace('~/');
		}
		$json = new MadJson("$this->component/googleOAuth.json");
		$this->view->api = $json->web;
	}
	function registSessionAction() {
		$post = $this->params;
		$model = $this->model;

		$model->fetchLogin( $post->userId, $post->userPw );

		$this->session->user = $model;
		return $model->isLogin();
	}
	function logoutAction() {
		unset( $this->session->user );
		unset( $this->session->company );
		return true;
	}
	function findIdAction() {
	}
	function findPwAction() {
	}
	function historyAction() {
	}
	function isIdAction() {
		$post = $this->params;
		if ( ! $post->userId ) {
			throw new Exception( 'illigal appoach' );
		}
		$query = "select count(*) from user where userId like '$post->userId'";
		return current($this->db->query( $query )->fetch( PDO::FETCH_ASSOC));
	}
	function isEmailAction() {
		$post = $this->params;

		if ( ! $post->email ) {
			return 'illigal appoach';
		}
		$rv = '-1';
		if ( IS_AJAX ) {
			$validate = $this->log->simpleValidateEmail($post->email);
		} else {
			$validate = $this->log->validateEmail($post->email);
		}
		if ( ! $validate ) {
			return false;
		}
		$query = "select count(*) as cnt from MadLog_user where email like '$post->email' limit 1";
		$q = new Q($query);
		return  $q->getFirst();
	}
	// from Persona
	function personaAction() {
		$this->persona = MadPersona::getInstance();
		$get = $this->get;
		if ( $get->projectId ) {
			$this->persona->setProject( $get->projectId );
		}
		if ( ! $this->persona->isProject() ) {
			$this->js->replace( '~/user/login' );
		}
		$referer = $server->HTTP_REFERER;
		if( $referer && ! preg_match('!px/persona!i', $referer) ) {
			$_SESSION['referer'] = $referer;
		}


		if ( $project = $this->session->currentProject ) {
			$index = new MadJson( "projects/$project/persona.json" );
		} else {
			$index = new MadData;
		}
		$this->view->index = $index;
	}
	function personaRegistSessionAction() {
		$this->persona->isProject();
		$persona = MadPersona::getInstance()->login( $this->post );

		if( isset( $_SESSION['referer'] ) ) {
			$referer = $_SESSION['referer'];
			unset( $_SESSION['referer'] );
		} else {
			$referer = '/' . $this->persona->getProject();
		}

		$this->js->alert( "you logged in as " . $this->persona->label )->replace( $referer );
	}
	function googleAction() {
		$data = print_R( $this->params, $true ) . "\n\n";
		@file_put_contents( "$this->component/google.log", $data, FILE_APPEND );
		die;
	}
}
