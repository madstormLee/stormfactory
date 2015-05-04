<?
class UserController extends MadController {
	function init() {
		parent::init();
		if ( ! strpos($this->router->backUrl, 'user' ) ) {
			$this->session->after = $this->router->backUrl;
		}
		$query = new MadQuery('User');
		if ( ! $query->isTable() ) {
			$this->installAction();
		}
	}
	function indexAction() {
		$get = $this->params;
		$model = $this->model;
		$sessionUser = $model::getSession();

		$query = new MadQuery('User');
		$query->where( "( level > $sessionUser->level or id = $sessionUser->id )" );

		if ( $get->level ) {
			$query->where( "level=$get->level" );
		} else {
			$query->where( 'level <= 100' );
		}

		$query->order("utime desc");

		$this->view->index = $query;
	}
	function viewAction() {
	}
	function writeAction() {
		$this->model->fetch( $this->params->id );
	}
	function saveAction() {
		return $this->model->setData( $this->params )->save();
	}
	function deleteAction() {
		return $this->model->delete( $this->get->id );
	}
	function saveSignupAction() {
		$post = $this->params;
		$model = $this->model;

		if ( $model->idExists( $post->userid ) ) {
			throw new Exception('이미 사용중인 아이디입니다.');
		}
		$post->level = 100;
		$post->pw = md5( $post->pw );

		$model->setData( $post )->save();
		$this->js->alert('가입 승인이 요청되었습니다.')->replace('./login');
	}
	function signoffAction() {
	}
	function loginAction() {
	}
	function registSessionAction() {
		$post = $this->params;
		$model = $this->model;

		$model->fetchLogin( $post->userId, $post->userPw );

		$this->session->user = $model;
		$this->js->replace( $this->router->project . '/' );
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
		if ( ! $post->id ) {
			throw new Exception( 'illigal appoach' );
		}
		return ! ! $this->query->count("userid like '$post->id'");
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

	function migrateAction() {
		$table = get_class($this->model);
		$mg = $table . '_migrate';

		$query = "alter table $table rename to $mg";
		// $this->db->exec( $query );
		$scheme = new MadScheme( $this->model );
		$result = $this->db->exec( $scheme );

		$query = "PRAGMA table_info($mg)";
		$mgInfo = new MadData($this->db->query( $query)->fetchAll(PDO::FETCH_CLASS));

		$query = "PRAGMA table_info($table)";
		$info = new MadData($this->db->query( $query)->fetchAll(PDO::FETCH_CLASS));

		$columns = $mgInfo->dic('name')->intersect($info->dic('name')->getData() )->implode(',');

		$query = "insert into $table ($columns) select $columns from $mg";
		$result = $this->db->exec( $query );
		return $result;
	}
	function installAction() {
		$this->dropAction();
		$query = new MadScheme( $this->model );
		return $this->db->exec( $query );
	}
	function dropAction() {
		$query = "drop table " . get_class($this->model);
		return $this->db->exec( $query );
	}
}
