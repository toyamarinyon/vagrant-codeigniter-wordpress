<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * note
 *
 * いったんCFに存在していた関数名で空のメソッドを作成しています。
 *
 * 	ex.) udfHTMLEditFormat -> htmlEditFormat
 *
 * 呼び出し側(コントローラー、ビュー)では、
 * CFで使われている関数から udf を除いて書けば、とりあえずエラー出ずに動くはずです。
 *
 * メソッドの中身の構築はグローバル仕様書を参照しながら、必要に応じて進めていきます。
 *
 * 2012-4-23 toyama
 */

class Text_converter
{

	public function __construct(){}

// ------------------------------------------------------------------------

	/**
	 * sqlSanitize
	 *
	 * SQL用サニタイズ
	 *
	 * @access	public
	 * @string
	 * @boolean
	 * @return
	 */
	public function sqlSanitize($sqlParam = '', $isNumeric =false)
	{
		return $sqlParam;
	}

// ------------------------------------------------------------------------

	/**
	 * sqlLikeFormat
	 *
	 * SQL用文字列整形(LIKE句の条件)
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function sqlLikeFormat($likeParam = '')
	{
		return $likeParam;
	}

// ------------------------------------------------------------------------

	/**
	 * mailFormat
	 *
	 * メール用の文字列変換処理
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function mailFormat($inputStr = '')
	{
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * mailFormatBR
	 *
	 * メール用の文字列変換処理
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function mailFormatBR($inputStr = '')
	{
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * htmlSanitize
	 *
	 * HTML用サニタイズ
	 *
	 * html_escapeが同じ機能を提供しているので、エイリアスにしておく
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function htmlSanitize($inputStr = '')
	{
		return html_escape($inputStr);
	}

// ------------------------------------------------------------------------

	/**
	 * htmlEditFormat
	 *
	 * HTML用表示用に整形
	 *
	 * @access	public
	 * @string
	 * @string
	 * @integer
	 * @boolean
	 * @string
	 * @return
	 */
	public function htmlEditFormat($inputStr='', $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
	{
		// 値がなければ初期値を返す
		if($inputStr === ''){
			return $this->htmlSanitize($nullVal);
		}


		if($maxLength <= 1){
			return $this->htmlSanitize($this->dependedCharConvert($inputStr));
		}
		else{
			$convertStr = $this->shortNameConvert($this->dependedCharConvert($inputStr), $maxLength, $isAddDot);

			if(mb_substr($convertStr, -1, 1) === '…'){
				return $this->htmlSanitize($convertStr) . $omissionHtml;
			}
			else{
				return $this->htmlSanitize($convertStr);
			}
		}


		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * htmlEditFormatCRLF
	 *
	 * HTML用表示用に整形(改行有効)
	 *
	 * @access	public
	 * @string
	 * @string
	 * @integer
	 * @boolean
	 * @string
	 * @return
	 */
	public function htmlEditFormatCRLF($inputStr='', $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
	{
		// 値がなければ初期値を返す
		if($inputStr === ''){
			return $this->htmlSanitize($nullVal);
		}

		if($maxLength <= 1){
			$convertStr = $this->htmlSanitize($this->dependedCharConvert($inputStr));
			return str_replace(PHP_EOL, '<br />', $convertStr);
		}
		else{
			$convertStr = $this->shortNameConvert($this->dependedCharConvert($inputStr), $maxLength, $isAddDot);

			if(mb_substr($convertStr, -1, 1) === '…'){
				$convertStr = $this->htmlSanitize($convertStr) . $omissionHtml;
				return str_replace(PHP_EOL, '<br />', $convertStr);
			}
			else{
				$convertStr = $this->htmlSanitize($convertStr);
				return str_replace(PHP_EOL, '<br />', $convertStr);
			}
		}


		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * htmlEditFormatDelCRLF
	 *
	 * HTML用表示用に整形(改行無効)
	 *
	 * @access	public
	 * @string
	 * @string
	 * @integer
	 * @boolean
	 * @string
	 * @return
	 */
	public function htmlEditFormatDelCRLF($inputStr='', $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
	{
		// 値がなければ初期値を返す
		if($inputStr === ''){
			return $this->htmlSanitize($nullVal);
		}


		if($maxLength <= 1){
			$convertStr = $this->htmlSanitize($this->dependedCharConvert($inputStr));
			return str_replace(PHP_EOL, '', $convertStr);
		}
		else{
			$convertStr = $this->shortNameConvert($this->dependedCharConvert($inputStr), $maxLength, $isAddDot);

			if(mb_substr($convertStr, -1, 1) === '…'){
				$convertStr = $this->htmlSanitize($convertStr) . $omissionHtml;
				return str_replace(PHP_EOL, '', $convertStr);
			}
			else{
				$convertStr = $this->htmlSanitize($convertStr);
				return str_replace(PHP_EOL, '', $convertStr);
			}
		}


		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * inputTextFormat
	 *
	 * textboxのvalue用に整形
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function inputTextFormat($inputStr = '')
	{
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * textareaFormat
	 *
	 * textareaのvalue用に整形
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function textareaFormat($inputStr = '')
	{
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * dbConvert
	 *
	 * DB格納用に整形
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function dbConvert($inputStr = '')
	{
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * dependedCharConvert
	 *
	 * 機種依存文字の変換
	 *
	 * TODO 波ダッシュ～
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function dependedCharConvert($inputStr = '')
	{
		$source = array( "ｶﾞ","ｷﾞ","ｸﾞ","ｹﾞ","ｺﾞ","ｻﾞ","ｼﾞ","ｽﾞ","ｾﾞ","ｿﾞ","ﾀﾞ","ﾁﾞ","ﾂﾞ",
						"ﾃﾞ","ﾄﾞ","ﾊﾞ","ﾋﾞ","ﾌﾞ","ﾍﾞ","ﾎﾞ","ﾊﾟ","ﾋﾟ","ﾌﾟ","ﾍﾟ","ﾎﾟ","ｱ",
						"ｲ","ｳ","ｴ","ｵ","ｶ","ｷ","ｸ","ｹ","ｺ","ｻ","ｼ","ｽ","ｾ","ｿ","ﾀ","ﾁ",
						"ﾂ","ﾃ","ﾄ","ﾅ","ﾆ","ﾇ","ﾈ","ﾉ","ﾊ","ﾋ","ﾌ","ﾍ","ﾎ","ﾏ","ﾐ","ﾑ",
						"ﾒ","ﾓ","ﾔ","ﾕ","ﾖ","ﾗ","ﾘ","ﾙ","ﾚ","ﾛ","ﾜ","ｦ","ﾝ","ｧ","ｨ","ｩ",
						"ｪ","ｫ","ｬ","ｭ","ｮ","ｯ","ｰ","ﾞ","ﾟ","･","｢","｣","､","｡","①","②",
						"③","④","⑤","⑥","⑦","⑧","⑨","⑩","⑪","⑫","⑬","⑭","⑮","⑯","⑰","⑱",
						"⑲","⑳","Ⅰ","Ⅱ","Ⅲ","Ⅳ","Ⅴ","Ⅵ","Ⅶ","Ⅷ","Ⅸ","Ⅹ","№","℡","㈱","㈲",
						"㈹","㍾","㍽","㍼","㍻");
		$dest = array("ガ","ギ","グ","ゲ","ゴ","ザ","ジ","ズ","ゼ","ゾ","ダ","ヂ","ヅ",
						"デ","ド","バ","ビ","ブ","ベ","ボ","パ","ピ","プ","ペ","ポ","ア",
						"イ","ウ","エ","オ","カ","キ","ク","ケ","コ","サ","シ","ス","セ","ソ","タ","チ",
						"ツ","テ","ト","ナ","ニ","ヌ","ネ","ノ","ハ","ヒ","フ","ヘ","ホ","マ","ミ","ム",
						"メ","モ","ヤ","ユ","ヨ","ラ","リ","ル","レ","ロ","ワ","ヲ","ン","ァ","ィ","ゥ",
						"ェ","ォ","ャ","ュ","ョ","ッ","ー","゛","゜","・","「","」","、","。","(1)","(2)",
						"(3)","(4)","(5)","(6)","(7)","(8)","(9)","(10)","(11)","(12)","(13)","(14)","(15)","(16)","(17)","(18)",
						"(19)","(20)","1","2","3","4","5","6","7","8","9","10","No.","Tel","(株)","(有)",
						"(代)","明治","大正","昭和","平成");

		return str_replace($source, $dest, $inputStr);
	}


// ------------------------------------------------------------------------

	/**
	 * textNormalize
	 *
	 * 半角カナを全角に変換
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function textNormalize($inputStr = '')
	{
		return $inputStr;
	}


// ------------------------------------------------------------------------

	/**
	 * asciiNormalize
	 *
	 * ASCII文字の全角->半角変換
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function asciiNormalize($inputStr = '')
	{
		$source = array("　","！","”","＃","＄","％","＆","’","（","）","＊","＋","－","．","／","０","１","２","３","４","５","６","７","８","９","：","；","＜","＝","＞","？","＠","Ａ","Ｂ","Ｃ","Ｄ","Ｅ","Ｆ","Ｇ","Ｈ","Ｉ","Ｊ","Ｋ","Ｌ","Ｍ","Ｎ","Ｏ","Ｐ","Ｑ","Ｒ","Ｓ","Ｔ","Ｕ","Ｖ","Ｗ","Ｘ","Ｙ","Ｚ","［","￥","］","＾","＿","｀","ａ","ｂ","ｃ","ｄ","ｅ","ｆ","ｇ","ｈ","ｉ","ｊ","ｋ","ｌ","ｍ","ｎ","ｏ","ｐ","ｑ","ｒ","ｓ","ｔ","ｕ","ｖ","ｗ","ｘ","ｙ","ｚ","｛","｜","｝","～");
		$dest = array(" ","!","\"","#","\$","%","&","'","(",")","*","+","-",".","/","0","1","2","3","4","5","6","7","8","9",":",";","<","=",">","?","@","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","[","\\","]","^","_","`","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","{","|","}","~");

		return str_replace($source, $dest, $inputStr);
	}


// ------------------------------------------------------------------------

	/**
	 * telNormalize
	 *
	 * 電話番号の正規化
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function telNormalize($inputStr = '')
	{
		$source = array("０","１","２","３","４","５","６","７","８","９","ー","（","）");
		$dest = array("0","1","2","3","4","5","6","7","8","9","-","(",")");

		return str_replace($source, $dest, $inputStr);
	}


// ------------------------------------------------------------------------

	/**
	 * zipNormalize
	 *
	 * 郵便番号の正規化
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function zipNormalize($inputStr = '')
	{
		$source = array("０","１","２","３","４","５","６","７","８","９","ー");
		$dest = array("0","1","2","3","4","5","6","7","8","9","-");

		return str_replace($source, $dest, $inputStr);
	}

// ------------------------------------------------------------------------

	/**
	 * numNormalize
	 *
	 * 数字の正規化
	 * ・全角数字を半角数字に置き換える
	 * ・全角マイナスを半角マイナスに置き換える
	 * ・全角ピリオドを半角ピリオドに置き換える
	 *
	 * @access	public
	 * @string
	 * @boolean
	 * @return
	 */
	public function numNormalize($inputStr = '', $zeroSuppress = true)
	{
		$source = array("０","１","２","３","４","５","６","７","８","９","ー","．");
		$dest = array("0","1","2","3","4","5","6","7","8","9","-",".");

		if($zeroSuppress){
			return preg_replace('/^(-|)0+/', '$1', str_replace($source, $dest, $inputStr));
		}

		return str_replace($source, $dest, $inputStr);
	}


// ------------------------------------------------------------------------

	/**
	 * numEmSize
	 *
	 * 数字を全角に変換
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function numEmSize($inputStr = '')
	{
		$source = array("0","1","2","3","4","5","6","7","8","9","-",".");
		$dest = array("０","１","２","３","４","５","６","７","８","９","ー","．");

		return str_replace($source, $dest, $inputStr);
	}


// ------------------------------------------------------------------------

	/**
	 * shortNameConvert
	 *
	 * 指定文字数での省略
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function shortNameConvert($inputStr = '', $maxLength = '', $isAddDot = true, $forceBrNum = 0, $isConvertBR = false)
	{
		/**
		 * TODO
		 * 改行コードの変換は必要？？
		 */

		$tempStr = '';
		$crlfCount = 0;
		$currentLength = 0;
		$splitStr = preg_split('/\r\n|\n|\r/', $inputStr, NULL);

		foreach($splitStr as $line){
			// あと何文字追加できる？
			$remainCharacter = $maxLength - $currentLength;
			// 追加できる文字を切り出す
			$addableCharacter = mb_substr($line, 0, $remainCharacter);

			// 追加！
			$tempStr .= $addableCharacter;

			// 改行コードカウントをインクリメント
			$crlfCount++;

			// 指定の改行コード数に達していたら、ループを抜ける
			if($forceBrNum !== 0 && $crlfCount >= $forceBrNum){
				if($isAddDot){
					$tempStr .= "…";
				}
				break;
			}

			// 現在の文字列長(改行コードを除く)
			$currentLength += mb_strlen($addableCharacter);

			// 指定文字列長に達していたら、ループを抜ける
			if($currentLength >= $maxLength){
				if($isAddDot){
					$tempStr .= "…";
				}
				break;
			}

			// まだ文字列長に余裕があれば改行コードを足しておく
			$tempStr .= PHP_EOL;
		}

		return $tempStr;
	}


// ------------------------------------------------------------------------

	/**
	 * crlf2Br
	 *
	 * CRLF-><BR>
	 *
	 * @access	public
	 * @string
	 * @return
	 */
	public function crlf2br($inputStr = '')
	{
		return str_replace(PHP_EOL, '<br />', $this->dependedCharConvert($inputStr));
	}

// ------------------------------------------------------------------------

	/**
	 * htmlEditFormatNoSanitizeCRLF
	 *
	 * HTML表示用に整形する
	 *
	 * @access	public
	 * @string
	 * @string
	 * @integer
	 * @boolean
	 * @string
	 * @return
	 */
	public function htmlEditFormatNoSanitizeCRLF($inputStr='', $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
	{
		// 値がなければ初期値を返す
		if($inputStr === ''){
			return $nullVal;
		}

		if($maxLength <= 1){
			$convertStr = $this->dependedCharConvert($inputStr);
			return str_replace(PHP_EOL, '<br />', $convertStr);
		}
		else{
			$convertStr = $this->shortNameConvert($this->dependedCharConvert($inputStr), $maxLength, $isAddDot);

			if(mb_substr($convertStr, -1, 1) === '…'){
				$convertStr = $convertStr . $omissionHtml;
				return str_replace(PHP_EOL, '<br />', $convertStr);
			}
			else{
				return str_replace(PHP_EOL, '<br />', $convertStr);
			}
		}


		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * crlfCountConvert
	 *
	 * 特定の数の改行コードを残してあとは削除する
	 *
	 * @access	public
	 * @string
	 * @numeric
	 * @return
	 */
	public function crlfCountConvert($inputStr='', $forceBrNum = 0)
	{
		$splitStr = preg_split('/\r\n|\n|\r/', $inputStr, NULL);
		$crlfCount = 0;
		$convertStr = '';

		foreach($splitStr as $line){
			// 文字列連結
			$convertStr .= $line;

			// 現在の改行回数が指定の改行回数よりも少ない場合は改行コードを連結
			if($crlfCount < forceBrNum){
				$convertStr .= PHP_EOL;
				// 改行回数インクリメント
				$crlfCount++;
			}

		}
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * htmlEditCRLFCountFormat
	 *
	 * HTML表示用に整形する
	 * ・改行無効にしたうえで、指定文字数で改行する
	 * ・機種依存文字を変換
	 *
	 * @access	public
	 * @string
	 * @integer
	 * @string
	 * @integer
	 * @boolean
	 * @string
	 * @return
	 */
	public function htmlEditCRLFCountFormat($inputStr='', $countBR = 99999, $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
	{
		// 値がなければ初期値を返す
		if($inputStr === ''){
			return $nullVal;
		}

		// $inputStr の改行を無効にし、1行の文字列に
		$line = preg_replace('/\r\n|\n|\r/', '', $inputStr, NULL);

		/*
		 * ------------------------------------------------
		 * 省略なし
		 * ------------------------------------------------
		 */
		if($maxLength <= 1){
			$convertStr = $this->dependedCharConvert($line);

			$addstr = '';
			$beginIndex = 0;
			$length = 1;
			while($subStr = mb_substr($convertStr, $beginIndex, 1)){
				// 切り出した1文字を追加
				$addstr .= $subStr;

				// 切り出し位置を進める
				$beginIndex++;

				// 指定文字数を追加したら改行コードを追加
				$beginIndex % $countBR == 0 ? $addstr .= PHP_EOL : '' ;
			}
			return str_replace(PHP_EOL, '<br />', $this->htmlSanitize($addstr));
		}
		/*
		 * ------------------------------------------------
		 * 省略あり
		 * ------------------------------------------------
		 */
		else{
			$convertStr = $this->shortNameConvert($this->dependedCharConvert($inputStr), $maxLength, $isAddDot);

			$addstr = '';
			$beginIndex = 0;
			$length = 1;
			while($subStr = mb_substr($convertStr, $beginIndex, 1)){
				// 切り出した1文字を追加
				$addstr .= $subStr;

				// 切り出し位置を進める
				$beginIndex++;

				// 指定文字数を追加したら改行コードを追加
				$beginIndex % $countBR == 0 ? $addstr .= PHP_EOL : '' ;
			}

			if(mb_substr($addstr, -1, 1) === '…'){
				return str_replace(PHP_EOL, '<br />', $this->htmlSanitize($addstr) . $omissionHtml);
			}
			else{
				return str_replace(PHP_EOL, '<br />', $this->htmlSanitize($addstr));
			}
		}


		return $inputStr;
	}


// ------------------------------------------------------------------------

	/**
	 * csvExportFormat
	 *
	 * CSVエクスポート用データに整形する
	 *
	 * @access	public
	 * @string
	 * @boolean
	 * @return
	 */
	public function csvExportFormat($inputStr = '', $isEnquote = false)
	{
		$inputStr = '"' . preg_replace('/\r\n|\n|\r/', '<br />', $inputStr) . '"';
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * csvInportFormat
	 *
	 * CSVインポート用データに整形する
	 *
	 * @access	public
	 * @string
	 * @boolean
	 * @return
	 */
	public function csvInportFormat($inputStr = '')
	{
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * csvInportFormatCRLF
	 *
	 * CSVインポート用データに整形する
	 *
	 * @access	public
	 * @string
	 * @boolean
	 * @return
	 */
	public function csvExportFormatCRLF($inputStr = '')
	{
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * formalizeMapURL
	 *
	 * 地図URLの整形
	 *
	 * @access	public
	 * @string
	 * @boolean
	 * @return
	 */
	public function formalizeMapURL($inputStr = '', $isEnquote = false)
	{
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * mapURLConvert
	 *
	 * 地図URLの表示用変数生成
	 *
	 * @access	public
	 * @string
	 * @boolean
	 * @return
	 */
	public function mapURLConvert($inputStr = '', $isEnquote = false)
	{
		return $inputStr;
	}

// ------------------------------------------------------------------------

	/**
	 * 入力変換 全角スペース → 半角スペース
	 *
	 * @access public
	 * @param string
	 * @return string
	 */
	public function fullSpaceConvert($inputStr = "")
	{
	    $str = mb_convert_encoding($inputStr, 'UTF-8', 'auto');
	    $str = mb_ereg_replace("　", " ", $str);

	    return $str;
	}

}
