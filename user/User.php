<?
class User extends MadModel {
	protected $data = array();
	protected $levels = null;
	protected $level = 1000;

	function __construct( $id='' ) {
		$this->initLevels();
		parent::__construct( $id );
	}
	public static function session() {
		if ( ! isset( $_SESSION['user'] ) ) {
			$_SESSION['user'] = new self;
		}
		return $_SESSION['user'];
	}
	public static function getSession() {
		if ( isset( $_SESSION['user'] ) ) {
			return $_SESSION['user'];
		}
		return new self;
	}
	protected function initLevels() {
		$this->levels = new MadJson( "user/levels.json" );
		if ( ! $this->levels->isFile() ) {
			$this->levels->setData( array(
				'root' => 0,
				'admin' => 1,
				'localAdmin' => 5,
				'member' => 200,
				'user' => 255,
				'guest' => 1000,
			) );
		}
	}
	function save( $data=null ) {
		if ( empty($this->id) ) {
			$this->wDate = date('Y-m-d H:i:s');
			$this->userPw = sha1($this->userPw);
		}
		$this->uDate = date('Y-m-d H:i:s');
		return parent::save();
	}
	function getNameLevel( $name = '' ) {
		return $this->getLevels()->$name;
	}
	function getLevelName( $level ) {
		return $this->getLevels()->find($level);
	}
	function getIndex() {
		$index = new MadIndex( $this );
		$query = $index->getQuery();
		if ( $this->id ) {
			$query->where( "( level > $this->level or id = $this->id )" );
		} else {
			// $query->where( "level > $this->level" );
		}

		$query->order("uDate desc");
		return $index;
	}
	public function getLevels() {
		return $this->levels;
	}
	function isLogin() {
		return $this->getLevel() <= $this->getLevel('member');
	}
	public final function isRoot() {
		return $this->getLevel() === 0;
	}
	function isAdmin() {
		return $this->isLevel( 1 );
	}
	function isLevel( $level=1000 ) {
		if ( is_string( $level ) ) {
			$level = $this->getNameLevel( $level );
		}
		return $this->getLevel() === $level;
	}
	public function inLevel( $condition ) {
		if ( empty( $condition ) ) {
			return true;
		}
		list($sLevel, $eLevel) = explode('-', $condition);
		$userLevel = $this->getLevel();
		return ( $userLevel >= $sLevel && $userLevel <= $eLevel );
	}
	function hasAuth( $level = 0 ) {
		return $this->getLevel() <= $level ;
	}
	function fetch( $id='' ) {
		if ( empty( $id ) ) {
			return $this;
		}
		$query = "select * from User where id=:id";
		$stmt = $this->getDb()->prepare( $query );
		if ( ! $stmt->execute( array( ':id' => $id ) ) ) {
			throw new Exception( "No id: $id" );
		}
		$this->data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this;
	}
	function fetchUserId( $userId ) {
		$query = "select * from $this->name where userId=:userId";
		$stmt = $this->getDb()->prepare( $query );
		if( ! $stmt->execute( array( 'userId' => $userId ) ) ) {
			throw new Exception('No user : ' . $userId);
		}
		$this->data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this;
	}
	function fetchEmail( $email ) {
		printR( $this->getSetting()->userLevel->options );
		$query = "select * from $this->name where email=:email";
		$stmt = $this->getDb()->prepare( $query );
		if( ! $stmt->execute( array( 'email' => $email ) ) ) {
			throw new Exception('No email : ' . $email);
		}
		$this->data = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this;
	}
	function fetchLogin( $userId, $userPw ) {
		$this->fetchUserId( $userId );
		if( $this->userPw != sha1( $userPw ) ) {
			throw new Exception('Wrong password!');
		}
		return $this;
	}
	function login() {
		$_SESSION['user'] = $this;
		return $this;
	}
	function logout() {
		unset( $_SESSION['user'] );
		return true;
	}
	function setLevel( $level = 1000 ) {
		$this->userLevel = $level;
		return $this;
	}
	public function getLevel( $name='' ) {
		if ( ! empty( $name ) ) {
			if ( isset( $this->levels->$name ) ) {
				return $this->levels->$name;
			} else {
				return $this->levels->guest;
			}
		}
		if ( ! isset( $this->userLevel ) ) {
			$this->userLevel = $this->levels->guest;
		}
		return $this->userLevel;
	}
	function getDefaultLevel() {
		if ( ! isset($this->levels->guest) ) {
			$this->levels->guest = 300;
		}
		return $this->levels->guest;
	}
	// todo: from MadRouter. intergrate this.
	function checkAuth() {
		if ( ! isset( $this->auth ) ) {
			return false;
		}
		if ( ! $user = self::session() ) {
			return false;
		}
		if ( $user->hasAuth( $this->authLevel ) ) {
			return false;
		}
		if ( $this->authPath == MadRouter::getInstance()->getComponentPath() ) {
			return false;
		}
		header( "Location: $this->authPath" );
		// throw new Exception('권한이 부족합니다.');;
	}
	public function __call( $method, $args ) {
		if ( 0 !== strpos( $method , 'is' ) ) {
			throw new Exception("there is no $method method in " . __class__);
		}
		if ( $this->isRoot() ) {
			return true;
		}
		$target = lcFirst( substr( $method, 2 ) );
		if ( ! $level = $this->levels->$target ) {
			return false;
		}
		return $this->userLevel === $level;
	}
}
