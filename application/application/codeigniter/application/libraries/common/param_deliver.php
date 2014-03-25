<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ToDOリスト
 *
 * □ 機種依存文字の変換
 * 
 */


class Param_deliver
{
	// param配列
	public  $_recvParam = array();
	private $_sendParam = array();

	// packageParam配列
	private $_recvPackageParam = array();
	private $_sendPackageParam = array();

	// url生成用バッファ
	private $_urlbuffer = '';

	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		// param,  packageParamの初期化
		// $_GET, $_POSTの値を配列に格納
		$this->_initRecvParam();
		$this->_initRecvPackageParam();
	}

// ------------------------------------------------------------------------
// Param & PackageParam 操作メソッド
// ------------------------------------------------------------------------

	/**
	 * sendParameterByURL
	 *
	 * パラメータ変数から値を取得しURLに整形
	 *
	 * @access	public
	 * @param	string	パラメータから除外したい値(配列で複数指定可能)
	 * @param	string	パッケージパラメータから除外したい値(配列で複数指定可能)
	 * @param   string  文字列の頭に付加する記号(デフォルトは?)
	 * @paran   boolean PK変数の付加フラグ(デフォルトは付加する)
	 * @paran   boolean sendParam除外フラグ(デフォルトはオフ)
	 * @paran   boolean sendPackageParam除外フラグ(デフォルトはオフ)
	 * @return	string
	 */
	public function sendParameterByURL($exclutionParam='', $exclutionPackageParam='', $leadChar='?', $addPK=true, $ignoreSendParam=false, $ignoreSendPackageParam=false)
	{
		// URLクエリ文字列
		$query = $leadChar;

		// $sendParam をクエリに追加
		if( ! $ignoreSendParam ){
			$query = $query . $this->sendParamByURL('', $exclutionParam);
		}

		// $sendPackageParam をクエリに追加
		if ( ! $ignoreSendPackageParam ){
			// 余分に & をつけない
			$leadChar = $query === '?' ? '' : '&';
			$query = $query . $this->sendPackageParamByURL($leadChar, $exclutionPackageParam);
		}

		// PK変数をクエリに追加
		if ( $addPK ){
			$CI =& get_instance();
			$CI->load->library('common/util');
			// 余分に & をつけない
			//$leadChar = $query === '?' || substr($query, -1) === '=' ? '' : '&';
			$leadChar = $query === '?'  ? '' : '&';
			// 空の_pp_をクエリから削除
			if( $leadChar === '' ){
				str_replace('_pp_=', '', $query);
			}
		
			$query = $query . $CI->util->getAdjustPK($leadChar);
		}
		return $query;

	}

	// ------------------------------------------------------------------------

	/**
	 * sendParameterByHidden
	 *
	 * パラメータ変数から値を取得しHiddenフォームを生成
	 *
	 * @access	public
	 * @param	string	パラメータから除外したい値(配列で複数指定可能)
	 * @param	string	パッケージパラメータから除外したい値(配列で複数指定可能)
	 * @param   string  文字列の頭に付加する記号(デフォルトは?)
	 * @paran   boolean sendParam除外フラグ(デフォルトはオフ)
	 * @paran   boolean sendPackageParam除外フラグ(デフォルトはオフ)
	 * @return	string
	 */
	public function sendParameterByHidden($exclutionParam='', $exclutionPackageParam='', $leadChar='?', $ignoreSendParam=false, $ignoreSendPackageParam=false)
	{
		// hiddenフォーム
		$hidden = '';

		// $sendParam をhiddenに追加
		if( ! $ignoreSendParam ){
			$hidden = $this->sendParamByHidden($exclutionParam);
		}

		// $sendPackageParam をhiddenに追加
		if ( ! $ignoreSendPackageParam ){
			$hidden = $hidden . $this->sendPackageParamByHidden($exclutionPackageParam);
		}

		return $hidden;

	}

// ------------------------------------------------------------------------
// Param操作メソッド
// ------------------------------------------------------------------------

	/**
	 * getParam
	 *
	 * パラメータ変数から値を取得
	 *
	 * @access	public
	 * @param	string	パラメータ名
	 * @param	string	デフォルト値
	 * @return	string
	 */
	public function getParam($key='', $default='')
	{
		// 引数なしで呼び出されたら _recvParam配列 を返す
		if(empty($key) && empty($default)){
			return $this->_recvParam;
		}

		// _recvParam[$key]があればそれを、
		// なければ $default を返す
		if(isset($this->_recvParam[$key])){
			return $this->_recvParam[$key];
		}
		else{
			return $default;
		}
		return '';
	}

	// ------------------------------------------------------------------------

	/**
	 * setParam
	 *
	 * パラメータ変数に値を設定
	 *
	 * @access	public
	 * @param	string	パラメータ名
	 * @param	string	パラメータの値
	 * @return	string
	 */
	public function setParam($key='', $value='')
	{
		if(is_array($array = $key)){
			foreach($array as $key => $value){
				$this->setParam($key, $value);
			}
		}
		else{
			$this->_sendParam[$key] = $value;
		}
	}

	// ------------------------------------------------------------------------
	
	/**
	 * clearParam
	 *
	 * 引き継ぎ対象としてセットしたパラメータを破棄する。
	 *
	 * @access	public
	 * @param	string	破棄せず保持するパラメータ名を指定する。なければすべて破棄する。
	 * @return	string
	 */
	public function clearParam($keepKey='')
	{

		if(empty($keepKey)){
			unset($this->_sendParam);
		}
		else{
			foreach($this->_sendParam as $key => $value){
				if(is_array($keepKey)){
					if(!in_array($key, $keepKey)){
						unset($this->_sendParam[$key]);
					}
				}
				else{
					if($key !== $keepKey){
						unset($this->_sendParam[$key]);
					}
				}
			}
		}

		return;
	}

	// ------------------------------------------------------------------------

	/**
	 * clearTargetParam
	 *
	 * 指定したパラメータを破棄する。
	 *
	 * @access	public
	 * @param	string or array
	 * @return	string
	 */
	public function clearTargetParam($target)
	{		
		if (is_string($target)) {
			unset($this->_sendParam[$target]);
		}
		elseif (is_array($target)) {
			foreach ($target as $key) {
				unset($this->_sendParam[$key]);
			}
		}
	
		return;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * sendParamByURL
	 *
	 * 次画面に渡すパラメータをURLパラメータに整形する
	 *
	 * @access	public
	 * @param	string	頭に付加する文字列(URLパラメータが空の場合も指定された文字を返す)
	 * @param	string	含めないパラメータ(配列で複数指定可能)
	 * @return	
	 */
	public function sendParamByURL($leadChar='', $exclutionParam='')
	{
		//_sendParamが空の場合はここで終了
		if(empty($this->_sendParam)){
			return;
		}

		// URL生成に含めるパラメータ保持変数
		$sendParam = array();

		// 除外する値を走査
		if($exclutionParam !== ''){
			foreach($this->_sendParam as $key => $value){
				if(is_array($exclutionParam)){
					if(!in_array($key, $exclutionParam)){
						$sendParam[$key] = $value;
					}
				}
				else{
					if($key !== $exclutionParam){
						$sendParam[$key] = $value;
					}
				}
			}
		}
		else{
			$sendParam = $this->_sendParam;
		}

		// URL整形 (配列対策で別メソッドに移譲)
		$this->urlBuffer = '';
		$paramStr = $this->_generateParamURL($sendParam, '', '&');

		return $leadChar . rtrim($paramStr, '&');
	}

	// ------------------------------------------------------------------------
	
	/**
	 * sendParamByHidden
	 *
	 * 次画面に渡すパラメータをHiddenパラメータに整形する
	 *
	 * @access	public
	 * @param	string	含めないパラメータ(配列で複数指定可能)
	 * @return
	 */
	public function sendParamByHidden($exclutionParam='')
	{	
		//_sendParamが空の場合はここで終了
		if(empty($this->_sendParam)){
			return;
		}

		// Hiddenに含めるパラメータ保持変数
		$sendParam = array();

		// 除外する値を走査
		if($exclutionParam !== ''){
			foreach($this->_sendParam as $key => $value){
				if(is_array($exclutionParam)){
					if(!in_array($key, $exclutionParam)){
						$sendParam[$key] = $value;
					}
				}
				else{
					if($key !== $exclutionParam){
						$sendParam[$key] = $value;
					}
				}
			}
		}
		else{
			$sendParam = $this->_sendParam;
		}

		$CI =& get_instance();
		$CI->load->helper('form');
		return form_hidden($sendParam);
	}

	// ------------------------------------------------------------------------

	/**
	 * packageRecvParam
	 *
	 * 前画面のパラメータをパッケージに格納(param->packageParam) 
	 *
	 * @access	public
	 * @return	
	 */
	public function packageRecvparam()
	{
		foreach($this->_recvParam as $key => $value){
			$this->_recvPackageParam[$key] = $value;
		}
		return;
	}

	// ------------------------------------------------------------------------

	/**
	 * getAndSetParam
	 *
	 * パラメータを取得したうえで、引き継ぎ対象としてセットする。
	 * 存在しないパラメータ名であれば、指定されたデフォルト値をセットする。
	 *
	 * @access	public
	 * @param	string	パラメータ名
	 * @param	string	デフォルト値
	 * @return	string
	 */
	public function getAndSetParam($key='', $default='')
	{
		$param = $this->getParam($key);
		
		if($param === ''){
			$param = $default;
		}

		$this->setParam($key, $param);
		return $param;
	}

	// ------------------------------------------------------------------------
	
	/**
	 * getRecvParamKeys
	 *
	 * RecvParamのkeyリストを取得する
	 *
	 * @access	public
	 * @return array
	 */
	public function getRecvParamKeys()
	{		
		return array_keys($this->_recvParam);
	}
	
	// ------------------------------------------------------------------------

// ------------------------------------------------------------------------
// PackageParam操作メソッド
// ------------------------------------------------------------------------

	/**
	 * existsRecvPackageParam
	 *
	 * パッケージ変数を受け取っているか、チェックする。
	 * 受け取って入れば true なければ false を返す。
	 *
	 * @access	public
	 * @return	boolean
	 */
	public function existsRecvPackageParam()
	{
		return !empty($this->_recvPackageParam);
	}

	// ------------------------------------------------------------------------

	/**
	 * getPackageParam
	 *
	 * パッケージ変数から値を取得
	 *
	 * @access	public
	 * @param	string	パラメータ名
	 * @param	string	デフォルト値
	 * @return	string
	 */
	public function getPackageParam($key='', $default='')
	{
		//_recvPackageParam が空ならここで終わり
		if(!$this->existsRecvPackageParam()){
			return '';
		}

		// 引数なしで呼び出されたら _recvPackageParam配列 を返す
		if(empty($key) && empty($default)){
			return $this->_recvPackageParam;
		}

		// _recvPackageParam[$key]があればそれを、
		// なければ $default を返す
		if(isset($this->_recvPackageParam[$key])){
			return $this->_recvPackageParam[$key];
		}
		else{
			return $default;
		}
		
		return '';
	}

	// ------------------------------------------------------------------------

	/**
	 * setPackageParam
	 *
	 * パッケージ変数に値を設定
	 *
	 * @access	public
	 * @param	string	パラメータ名
	 * @param	string	パラメータの値
	 * @return	string
	 */
	public function setPackageParam($key='', $value='')
	{
		if(is_array($array = $key)){
			foreach($array as $key => $value){
				$this->setPackageParam($key, $value);
			}
		}
		else{
			if($key !== '' && $value !== ''){
				$this->_sendPackageParam[$key] = $value;
			}
		}

	}

	// ------------------------------------------------------------------------

	/**
	 * sendPackageParamByURL
	 *
	 * 次画面に渡すパッケージをURLパラメータに整形する
	 *
	 * @access	public
	 * @param	string	頭に付加する文字列(URLパラメータが空の場合も指定された文字を返す)
	 * @param	string	含めないパラメータ(配列で複数指定可能)
	 * @return	
	 */
	public function sendPackageParamByURL($leadChar='', $exclutionParam='')
	{
		//_sendPackageParam が空ならここで終わり
		if(empty($this->_sendPackageParam)){
			return;
		}

		// Hiddenに含めるパラメータ保持変数
		$sendPackageParam = array();

		// 除外する値を走査
		if($exclutionParam !== ''){
			foreach($this->_sendPackageParam as $key => $value){
				if(is_array($exclutionParam)){
					if(!in_array($key, $exclutionParam)){
						$sendPackageParam[$key] = $value;
					}
				}
				else{
					if($key !== $exclutionParam){
						$sendPackageParam[$key] = $value;
					}
				}
			}
		}
		else{
			$sendPackageParam = $this->_sendPackageParam;
		}


		$this->urlBuffer = '';
		$paramStr = $this->_generateParamURL($sendPackageParam, '', '|');

		return $leadChar . '_pp_=' . urlencode(rtrim($paramStr, '|'));
	}

	// ------------------------------------------------------------------------

	/**
	 * sendPackageParamByHidden
	 *
	 * 次画面に渡すパッケージをHiddenパラメータに整形する
	 *
	 * @access	public
	 * @param	string	含めないパラメータ(配列で複数指定可能)
	 * @return	
	 */
	public function sendPackageParamByHidden($exclutionParam='')
	{
		//_sendPackageParam が空ならここで終わり
		if(empty($this->_sendPackageParam)){
			return;
		}

		// Hiddenに含めるパラメータ保持変数
		$sendPackageParam = array();

		// 除外する値を走査
		if($exclutionParam !== ''){
			foreach($this->_sendParam as $key => $value){
				if(is_array($exclutionParam)){
					if(!in_array($key, $exclutionParam)){
						$sendPackageParam[$key] = $value;
					}
				}
				else{
					if($key !== $exclutionParam){
						$sendPackageParam[$key] = $value;
					}
				}
			}
		}
		else{
			$sendPackageParam = $this->_sendPackageParam;
		}

		$CI =& get_instance();
		$CI->load->helper('form');

		$packageStr='';

		foreach($sendPackageParam as $value => $key){
			$packageStr .= $value . '=' . $key . '|';
		}

		return form_hidden( array('_pp_' => rtrim($packageStr,  '|')) );
	}

	/**
	 * sendPackageParamByHiddenEx
	 *
	 * 次画面に渡すパッケージをHiddenパラメータに整形する（修正版）
	 *
	 * @access	public
	 * @param	string	含めないパラメータ(配列で複数指定可能)
	 * @return	
	 */
	public function sendPackageParamByHiddenEx($exclutionParam='')
	{
		//_sendPackageParam が空ならここで終わり
		if(empty($this->_sendPackageParam)){
			return;
		}

		// Hiddenに含めるパラメータ保持変数
		$sendPackageParam = array();

		// 除外する値を走査
		if($exclutionParam !== ''){
			foreach($this->_sendPackageParam as $key => $value){
				if(is_array($exclutionParam)){
					if(!in_array($key, $exclutionParam)){
						$sendPackageParam[$key] = $value;
					}
				}
				else{
					if($key !== $exclutionParam){
						$sendPackageParam[$key] = $value;
					}
				}
			}
		}
		else{
			$sendPackageParam = $this->_sendPackageParam;
		}

		$CI =& get_instance();
		$CI->load->helper('form');

		$packageStr='';

		foreach($sendPackageParam as $value => $key){
			$packageStr .= $value . '=' . $key . '|';
		}

		return form_hidden( array('_pp_' => rtrim($packageStr,  '|')) );
	}

	// ------------------------------------------------------------------------

	/**
	 * repackageParam
	 *
	 * パッケージ変数を次画面に渡すパッケージに格納する
	 *
	 * @access	public
	 * @param	string	パッケージに含めないパラメータ(配列で複数指定可能)
	 * @return	
	 */
	public function repackageParam($exclutionParam='')
	{
		//_recvPackageParam が空ならここで終わり
		if(!$this->existsRecvPackageParam()){
			return;
		}

		foreach($this->_recvPackageParam as $key => $value){
			if(is_array($exclutionParam)){
				if(!in_array($key, $exclutionParam)){
					$this->_sendPackageParam[$key] = $value;
				}
			}
			else{
				if($key !== $exclutionParam){
					$this->_sendPackageParam[$key] = $value;
				}
			}
		}
		return;
	}

	// ------------------------------------------------------------------------

	/**
	 * unpackParamByURL
	 *
	 * パッケージ変数を次画面に渡すパッケージに格納する
	 *
	 * @access	public
	 * @param	string	パッケージに含めないパラメータ(配列で複数指定可能)
	 * @return	
	 */
	public function unpackParamByURL()
	{
		//_recvPackageParam が空ならここで終わり
		if(!$this->existsRecvPackageParam()){
			return;
		}

		$paramStr = '';

		foreach($this->_sendPackageParam as $value => $key){
			$paramStr .= $value . '=' . $key . '&';
		}

		return urlencode(rtrim($paramStr, '&'));
	}

	// ------------------------------------------------------------------------

	/**
	 * removeFromSendPackage
	 *
	 * 次画面に渡すパッケージから、特定の変数を除去する
	 *
	 * @access	public
	 * @param	string	除去する変数名(配列で複数指定可)
	 * @return	
	 */
	public function removeSendPackageParam($removeParamName='')
	{
		//_sendPackageParam が空ならここで終わり
		if(empty($this->_sendPackageParam)){
			return;
		}

		if(is_array($removeParamName)){
			foreach($removeParamName as $value){
				$this->removeSendPackageParam($value);
			}
			return;
		}


		if(isset($this->_sendPackageParam[$removeParamName])){
			unset($this->_sendPackageParam[$removeParamName]);
		}

		return;
	}

// ------------------------------------------------------------------------
// privateメソッド
// ------------------------------------------------------------------------

	/**
	 * generateParamURL
	 *
	 * 次画面に渡すパラメータをURL整形する
	 *
	 * @access	private
	 * @return	string
	 */
	private function _generateParamURL($name, $value = '', $delimitter)
	{
		

		if (is_array($name))
		{
			foreach ($name as $key => $val)
			{
				$this->_generateParamURL($key, $val, $delimitter);
			}
			return $this->urlBuffer;
		}

		if ( ! is_array($value))
		{
			$this->urlBuffer .= $name . '=' . urlencode($value) . $delimitter;
		}
		else
		{
			foreach ($value as $k => $v)
			{
				$k = (is_int($k)) ? '' : $k;
				$this->_generateParamURL($name.'['.$k.']', $v, $delimitter);
			}
		}

		return $this->urlBuffer;
	}

	// ------------------------------------------------------------------------

	/**
	 * _initRecvParam
	 *
	 * FORM変数、URL変数の内容をすべて、_recvParam に格納する
	 *
	 * @access	private
	 * @return	
	 */
	private function _initRecvParam()
	{
		foreach($_GET as $key => $value){
			if($key !== 'PK' && $key !== '_pp_'){
				// 全半角ともスペースを削除
				$_param = preg_replace('/[ 　]+$/u', '', $value);
				// NO-BREAK SPACE -> SPACE (2012/11/11 K.Kamiyama)
				if (is_string($_param) && mb_strpos($_param, mb_chr(0xC2A0))) {
					$_param = str_replace(mb_chr(0xC2A0), chr(0x20), $_param);
				}
				$this->_recvParam[$key] = $_param; 
			}
		}
		foreach($_POST as $key => $value){
			if($key !== 'PK' && $key !== '_pp_' && $key !== 'x' && $key !== 'y'){
				// 全半角ともスペースを削除
				$_param = preg_replace('/[ 　]+$/u', '', $value);
				// NO-BREAK SPACE -> SPACE (2012/11/11 K.Kamiyama)
				if (is_string($_param) && mb_strpos($_param, mb_chr(0xC2A0))) {
					$_param = str_replace(mb_chr(0xC2A0), chr(0x20), $_param);
				}
				$this->_recvParam[$key] = $_param; 
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * _initRecvPackageParam
	 *
	 * 変数 _pp_ を _recvPackageParam に格納する
	 *
	 * @access	private
	 * @return	
	 */
	private function _initRecvPackageParam()
	{
		if(isset($_GET['_pp_']) and !empty($_GET['_pp_'])){
			foreach(explode('|',  urldecode($_GET['_pp_'])) as $element){
				list($key,  $value) = explode('=',  $element);
					$this->_recvPackageParam[$key] = $value;
			}
		}

		if(isset($_POST['_pp_']) and !empty($_POST['_pp_'])){
			foreach(explode('|',  $_POST['_pp_']) as $element){
				list($key,  $value) = explode('=',  $element);
					$this->_recvPackageParam[$key] = $value;
			}
		}
	}


}

/**
 * chr関数のマルチバイト版
 * 指定コードに対応したマルチバイト文字を返す。
 * @param unknown_type $num
 * @return string
 */
if ( ! function_exists('mb_chr'))
{
	function mb_chr($num){
	  return ($num < 256) ? chr($num) : mb_chr($num / 256).chr($num % 256);
	}
}

/* End of file param_deliver.php */
/* Location: ./libraries/common/param_deliver */
