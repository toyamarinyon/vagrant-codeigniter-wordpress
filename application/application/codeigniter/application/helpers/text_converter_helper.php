<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
if ( ! function_exists('sqlSanitize'))
{
	function sqlSanitize($sqlParam = '', $isNumeric =false)
	{
		return $sqlParam;
	}
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
if ( ! function_exists('sqlLikeFormat'))
{
	function sqlLikeFormat($likeParam = '')
	{
		return $likeParam;
	}
}
// ------------------------------------------------------------------------

	/**
	 * mailAddressFormat
	 *
	 * メールアドレス用の文字列変換処理
	 *
	 * @access	public
	 * @string
	 * @return
	 */
if ( ! function_exists('mailAddressFormat'))
{
	function mailAddressFormat($inputStr = '')
	{
			$tmpArray = explode('@', $inputStr);
			if (count($tmpArray) != 2)
				return "";
			
			list($address, $domain) = explode('@', $inputStr);
			$doubleQuote = Chr(34); // ダブルクォート
			$backslash   = Chr(92); // バックスラッシュ

			$convertAddress = $address;

			// バックスラッシュ [ \ ] をエスケープ
			//$convertStr = str_replace($backslash, "\{$backslash}", $inputStr);
			// ダブルクォート   [ " ] をエスケープ
			$convertAddress = str_replace($doubleQuote, "\\\"", $address);

			if ( preg_match("/[()\[\]:;,\"]/", $convertAddress, $matches) ) {
					$convertAddress = '"'.$convertAddress.'"';
			}

			$convertMailAddress = $convertAddress.'@'.$domain;
			// メールヘッダー用エンコード(ISO-2022-JP)に置換
		return $convertMailAddress;
	}
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
if ( ! function_exists('mailFormat'))
{
	function mailFormat($inputStr = '')
	{
		// 変換した文字列
		$retVal = '';

		// 機種依存文字の置換
		$retVal = dependedCharConvert($inputStr);
		
		// Outlook用に置換(タグ系の文字変換)
		$retVal = str_replace(array('<', '>'), array('＜', '＞'), $retVal);
		
		// メール用エンコード(ISO-2022-JP)に置換
		$retVal = mb_convert_encoding($retVal, "ISO-2022-JP", "UTF-8"); 

		return $retVal;
	}
}

// ------------------------------------------------------------------------

/**
 * mailMobileFormat
 *
 * メール用の文字列変換処理（携帯向け）
 *
 * @access	public
 * @string
 * @return
 */
if ( ! function_exists('mailMobileFormat'))
{
	function mailMobileFormat($inputStr = '')
	{
		// 変換した文字列
		$retVal = '';

		// 機種依存文字の置換
		$retVal = dependedCharConvert($inputStr);
		
		// ASCII文字の全角→半角変換（携帯用に「～」を除いて変換）
		$retVal = mobileAsciiNormalize($retVal);
		
		// 全角カタカナを半角に変換
		$retVal = zenKanaToHanKana($retVal);
		
		// Outlook用に置換(タグ系の文字変換)
		//$retVal = str_replace(array('<', '>'), array('＜', '＞'), $retVal);

		// メール用エンコード(ISO-2022-JP)に置換
		$retVal = mb_convert_encoding($retVal, "JIS", "UTF-8");

		return $retVal;
	}
}

// ------------------------------------------------------------------------

	/**
	 * mailSubjectFomrat
	 *
	 * メール件名用の文字列変換処理
	 *
	 * @access	public
	 * @string
	 * @return
	 */
if ( ! function_exists('mailSubjectFormat'))
{
	function mailSubjectFormat($inputStr = '')
	{
		// 変換した文字列
		$retVal = '';

		// 機種依存文字の置換
		$retVal = dependedCharConvert($inputStr);
		
		// Outlook用に置換(タグ系の文字変換)
		$retVal = str_replace(array('<', '>'), array('＜', '＞'), $retVal);
		
		// メールヘッダー用エンコード(ISO-2022-JP)に置換
//	    $retVal = mb_encode_mimeheader($retVal, "UTF-8", "B");
		$retVal = mb_encode_mimeheader($retVal, "iso-2022-jp", "B");	

		return $retVal;
	}
}

// ------------------------------------------------------------------------

/**
 * mailSubjectMobileFormat
 *
 * メール件名用の文字列変換処理（携帯向け）
 *
 * @access	public
 * @string
 * @return
 */
if ( ! function_exists('mailSubjectMobileFormat'))
{
	function mailSubjectMobileFormat($inputStr = '')
	{
		// 変換した文字列
		$retVal = '';

		// 機種依存文字の置換
		$retVal = dependedCharConvert($inputStr);
		
		// ASCII文字の全角→半角変換（携帯用に「～」を除いて変換）
		$retVal = mobileAsciiNormalize($retVal);
		
		// 全角カタカナを半角に変換
		$retVal = zenKanaToHanKana($retVal);

		// Outlook用に置換(タグ系の文字変換)
		//$retVal = str_replace(array('<', '>'), array('＜', '＞'), $retVal);

		// メールヘッダー用エンコード(ISO-2022-JP)に置換
		$retVal = mb_encode_mimeheader($retVal, "UTF-8", "B");

		return $retVal;
	}
}

// ------------------------------------------------------------------------

	/**
	 * utfEscape
	 *
	 * UTF-8エンコードの文字化け対策
	 *
	 * @access	public
	 * @string
	 * @return
	 */
if ( ! function_exists('utfEscape'))
{
	function utfEscape($inputStr = '')
	{
		$utf_escape_patterns =array(
		
			// 波ダッシュを全角チルダ(～)へ変換
			'/\xE3\x80\x9C/' =>"\xEF\xBD\x9E",
			
			// 全角マイナス記号(－)の変換
			'/\xE2\x88\x92/' =>"\xEF\xBC\x8D",
			
			// 双柱・平行記号(∥)の変換
			'/\xE2\x80\x96/' =>"\xE2\x88\xA5",
			
			// セント記号(￠)の変換 
			'/\xC2\xA2/' =>"\xEF\xBF\xA0",
			
			// ポンド記号(￡)の変換
			'/\xC2\xA3/' =>"\xEF\xBF\xA1",
			
			// 否定記号(￢)の変換
			'/\xC2\xAC/' =>"\xEF\xBF\xA2",
		);

		return preg_replace(array_keys($utf_escape_patterns), array_values($utf_escape_patterns), $inputStr);
	}
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
if ( ! function_exists('mailFormatBR'))
{
	function mailFormatBR($inputStr = '')
	{
		return $inputStr;
	}
}

// ------------------------------------------------------------------------

	/**
	 * htmlSanitize
	 *
	 * HTML用サニタイズ
	 *
	 * html_escape + スペースを実態参照文字に置換
	 *
	 * @access	public
	 * @string
	 * @return
	 */
if ( ! function_exists('htmlSanitize'))
{
	function htmlSanitize($inputStr = '')
	{
		return str_replace(' ', '&nbsp;', html_escape($inputStr));
	}
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
if ( ! function_exists('htmlEditFormat'))
{
	function htmlEditFormat($inputStr='', $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
	{
		// 値がなければ初期値を返す
		if($inputStr === ''){
			return htmlSanitize($nullVal);
		}


		if($maxLength <= 1){
			return htmlSanitize(dependedCharConvert($inputStr));
		}
		else{
			$convertStr = shortNameConvert(dependedCharConvert($inputStr), $maxLength, $isAddDot);

			if(mb_substr($convertStr, -1, 1) === '…'){
				return htmlSanitize($convertStr) . $omissionHtml;
			}
			else{
				return htmlSanitize($convertStr);
			}
		}


		return $inputStr;
	}
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
if ( ! function_exists('htmlEditFormatCRLF'))
{
	function htmlEditFormatCRLF($inputStr='', $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
	{
		// 値がなければ初期値を返す
		if($inputStr === ''){
			return htmlSanitize($nullVal);
		}

		if($maxLength <= 1){
			$convertStr = htmlSanitize(dependedCharConvert($inputStr));
			return str_replace(PHP_EOL, '<br/>', $convertStr);
		}
		else{
			$convertStr = shortNameConvert(dependedCharConvert($inputStr), $maxLength, $isAddDot);

			if(mb_substr($convertStr, -1, 1) === '…'){
				$convertStr = htmlSanitize($convertStr) . $omissionHtml;
				return str_replace(PHP_EOL, '<br/>', $convertStr);
			}
			else{
				$convertStr = htmlSanitize($convertStr);
				return str_replace(PHP_EOL, '<br/>', $convertStr);
			}
		}


		return $inputStr;
	}
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
if ( ! function_exists('htmlEditFormatDelCRLF'))
{
	function htmlEditFormatDelCRLF($inputStr='', $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
	{
		// 値がなければ初期値を返す
		if($inputStr === ''){
			return htmlSanitize($nullVal);
		}


		if($maxLength <= 1){
			$convertStr = htmlSanitize(dependedCharConvert($inputStr));
			return str_replace(PHP_EOL, '', $convertStr);
		}
		else{
			$convertStr = shortNameConvert(dependedCharConvert($inputStr), $maxLength, $isAddDot);

			if(mb_substr($convertStr, -1, 1) === '…'){
				$convertStr = htmlSanitize($convertStr) . $omissionHtml;
				return str_replace(PHP_EOL, '', $convertStr);
			}
			else{
				$convertStr = htmlSanitize($convertStr);
				return str_replace(PHP_EOL, '', $convertStr);
			}
		}


		return $inputStr;
	}
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
if ( ! function_exists('inputTextFormat'))
{
	function inputTextFormat($inputStr = '')
	{
		return str_replace('&nbsp;', ' ', htmlEditFormat($inputStr));
	}
}

// ------------------------------------------------------------------------

	/**
	 * textareaFormat
	 *
	 * textareaのvalue用に整形
	 * &nbsp;をスペースに置換
	 *
	 * @access	public
	 * @string
	 * @return
	 */
if ( ! function_exists('textareaFormat'))
{
	function textareaFormat($inputStr = '')
	{
		return str_replace('&nbsp;', ' ', htmlEditFormat($inputStr));
	}
}

// ------------------------------------------------------------------------

	/**
	 * dbConvert
	 *
	 * DB格納用に整形
	 *  > rtrimしてdependedCharaConvert
	 *
	 * @access	public
	 * @string
	 * @return
	 */
if ( ! function_exists('dbConvert'))
{
	function dbConvert($inputStr = '')
	{
		return dependedCharConvert(rtrim($inputStr));
	}
}

// ------------------------------------------------------------------------

	/**
	 * dependedCharConvert
	 *
	 * 機種依存文字の変換
	 *
	 * @access	public
	 * @string
	 * @return
	 */
if ( ! function_exists('dependedCharConvert'))
{
	function dependedCharConvert($inputStr = '')
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

		return str_replace($source, $dest, utfEscape($inputStr));
	}
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
if ( ! function_exists('textNormalize'))
{
	function textNormalize($inputStr = '')
	{
		$source = array( "ｶﾞ","ｷﾞ","ｸﾞ","ｹﾞ","ｺﾞ","ｻﾞ","ｼﾞ","ｽﾞ","ｾﾞ","ｿﾞ","ﾀﾞ","ﾁﾞ","ﾂﾞ",
						"ﾃﾞ","ﾄﾞ","ﾊﾞ","ﾋﾞ","ﾌﾞ","ﾍﾞ","ﾎﾞ","ﾊﾟ","ﾋﾟ","ﾌﾟ","ﾍﾟ","ﾎﾟ","ｱ",
						"ｲ","ｳ","ｴ","ｵ","ｶ","ｷ","ｸ","ｹ","ｺ","ｻ","ｼ","ｽ","ｾ","ｿ","ﾀ","ﾁ",
						"ﾂ","ﾃ","ﾄ","ﾅ","ﾆ","ﾇ","ﾈ","ﾉ","ﾊ","ﾋ","ﾌ","ﾍ","ﾎ","ﾏ","ﾐ","ﾑ",
						"ﾒ","ﾓ","ﾔ","ﾕ","ﾖ","ﾗ","ﾘ","ﾙ","ﾚ","ﾛ","ﾜ","ｦ","ﾝ","ｧ","ｨ","ｩ",
						"ｪ","ｫ","ｬ","ｭ","ｮ","ｯ");
		$dest = array("ガ","ギ","グ","ゲ","ゴ","ザ","ジ","ズ","ゼ","ゾ","ダ","ヂ","ヅ",
						"デ","ド","バ","ビ","ブ","ベ","ボ","パ","ピ","プ","ペ","ポ","ア",
						"イ","ウ","エ","オ","カ","キ","ク","ケ","コ","サ","シ","ス","セ","ソ","タ","チ",
						"ツ","テ","ト","ナ","ニ","ヌ","ネ","ノ","ハ","ヒ","フ","ヘ","ホ","マ","ミ","ム",
						"メ","モ","ヤ","ユ","ヨ","ラ","リ","ル","レ","ロ","ワ","ヲ","ン","ァ","ィ","ゥ",
						"ェ","ォ","ャ","ュ","ョ","ッ");

		return str_replace($source, $dest, $inputStr);
	}
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
if ( ! function_exists('asciiNormalize'))
{
	function asciiNormalize($inputStr = '')
	{
		$source = array("　","！","”","＃","＄","％","＆","’","（","）","＊","＋","－","．","／",
			"０","１","２","３","４","５","６","７","８","９","：","；","＜","＝","＞","？","＠",
			"Ａ","Ｂ","Ｃ","Ｄ","Ｅ","Ｆ","Ｇ","Ｈ","Ｉ","Ｊ","Ｋ","Ｌ","Ｍ","Ｎ","Ｏ","Ｐ","Ｑ",
			"Ｒ","Ｓ","Ｔ","Ｕ","Ｖ","Ｗ","Ｘ","Ｙ","Ｚ","［","￥","］","＾","＿","｀","ａ","ｂ",
			"ｃ","ｄ","ｅ","ｆ","ｇ","ｈ","ｉ","ｊ","ｋ","ｌ","ｍ","ｎ","ｏ","ｐ","ｑ","ｒ","ｓ",
			"ｔ","ｕ","ｖ","ｗ","ｘ","ｙ","ｚ","｛","｜","｝","～");
		$dest = array(" ","!","\"","#","\$","%","&","'","(",")","*","+","-",".","/","0","1","2",
			"3","4","5","6","7","8","9",":",";","<","=",">","?","@","A","B","C","D","E","F","G",
			"H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","[","\\",
			"]","^","_","`","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q",
			"r","s","t","u","v","w","x","y","z","{","|","}","~");

		return str_replace($source, $dest, $inputStr);
	}
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
if ( ! function_exists('telNormalize'))
{
	function telNormalize($inputStr = '')
	{
		$source = array("０","１","２","３","４","５","６","７","８","９","ー","－","（","）");
		$dest = array("0","1","2","3","4","5","6","7","8","9","-","-","(",")");

		return str_replace($source, $dest, $inputStr);
	}
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
if ( ! function_exists('zipNormalize'))
{
	function zipNormalize($inputStr = '')
	{
		$source = array("０","１","２","３","４","５","６","７","８","９","ー");
		$dest = array("0","1","2","3","4","5","6","7","8","9","-");

		return str_replace($source, $dest, $inputStr);
	}
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
if ( ! function_exists('numNormalize'))
{

	function numNormalize($inputStr = '', $zeroSuppress = true)
	{
		$source = array("０","１","２","３","４","５","６","７","８","９","ー","．");
		$dest = array("0","1","2","3","4","5","6","7","8","9","-",".");

		if($zeroSuppress){
			return preg_replace('/^(-|)0+/', '$1', str_replace($source, $dest, $inputStr));
		}

		return str_replace($source, $dest, $inputStr);
	}
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
if ( ! function_exists('numEmSize'))
{
	function numEmSize($inputStr = '')
	{
		$source = array("0","1","2","3","4","5","6","7","8","9","-",".");
		$dest = array("０","１","２","３","４","５","６","７","８","９","ー","．");

		return str_replace($source, $dest, $inputStr);
	}
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
if ( ! function_exists('shortNameConvert'))
{
	function shortNameConvert($inputStr = '', $maxLength = '', $isAddDot = true, $forceBrNum = 0, $isConvertBR = false)
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
			if($currentLength >= $maxLength && $currentLength < mb_strlen($inputStr)){ // 入力文字数が最大文字数と同じ時は …　をつけない
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
if ( ! function_exists('crlf2br'))
{
	function crlf2br($inputStr = '')
	{
		return str_replace(PHP_EOL, '<br />', dependedCharConvert($inputStr));
	}
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
if ( ! function_exists('htmlEditFormatNoSanitizeCRLF'))
{
	function htmlEditFormatNoSanitizeCRLF($inputStr='', $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
	{
		// 値がなければ初期値を返す
		if($inputStr === ''){
			return $nullVal;
		}

		if($maxLength <= 1){
			$convertStr = dependedCharConvert($inputStr);
			return str_replace(PHP_EOL, '<br />', $convertStr);
		}
		else{
			$convertStr = shortNameConvert(dependedCharConvert($inputStr), $maxLength, $isAddDot);

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
if ( ! function_exists('crlfCountConvert'))
{
	function crlfCountConvert($inputStr='', $forceBrNum = 0)
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
if ( ! function_exists('htmlEditCRLFCountFormat'))
{
	function htmlEditCRLFCountFormat($inputStr='', $countBR = 99999, $nullVal='', $maxLength=-1, $isAddDot=true, $omissionHtml='')
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
			$convertStr = dependedCharConvert($line);

			$addstr = '';
			$beginIndex = 0;
			$length = 1;
			while( ($subStr = mb_substr($convertStr, $beginIndex, 1)) !== ''){
				// 切り出した1文字を追加
				$addstr .= $subStr;

				// 切り出し位置を進める
				$beginIndex++;

				// 指定文字数を追加したら改行コードを追加
				$beginIndex % $countBR == 0 ? $addstr .= PHP_EOL : '' ;
			}
			return str_replace(PHP_EOL, '<br />', htmlSanitize($addstr));
		}
		/*
		 * ------------------------------------------------
		 * 省略あり
		 * ------------------------------------------------
		 */
		else{
			$convertStr = shortNameConvert(dependedCharConvert($inputStr), $maxLength, $isAddDot);

			$addstr = '';
			$beginIndex = 0;
			$length = 1;
			while( ($subStr = mb_substr($convertStr, $beginIndex, 1)) !== ''){
				// 切り出した1文字を追加
				$addstr .= $subStr;

				// 切り出し位置を進める
				$beginIndex++;

				// 指定文字数を追加したら改行コードを追加
				$beginIndex % $countBR == 0 ? $addstr .= PHP_EOL : '' ;
			}

			if(mb_substr($addstr, -1, 1) === '…'){
				return str_replace(PHP_EOL, '<br />', htmlSanitize($addstr) . $omissionHtml);
			}
			else{
				return str_replace(PHP_EOL, '<br />', htmlSanitize($addstr));
			}
		}


		return $inputStr;
	}
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
if ( ! function_exists('csvExportFormat'))
{
	function csvExportFormat($inputStr = '', $isEnquote = false)
	{
		// 変換した文字列
		$retVal = '';
		
		// ダブルコーテーションの変換
		$retVal = str_replace('"', '""', $inputStr);		

		// 機種依存文字の置換
		$retVal = dependedCharConvert($retVal);
		
		$retVal = '"' . preg_replace('/\r\n|\n|\r/', '<br />', $retVal) . '"';
		
		return $retVal;
	}
}


// ------------------------------------------------------------------------

	/**
	 * csvImportFormat
	 *
	 * CSVインポート用データに整形する
	 *
	 * @access	public
	 * @string
	 * @boolean
	 * @return
	 */
if ( ! function_exists('csvImportFormat'))
{
	function csvImportFormat($inputStr = '')
	{
		// 変換した文字列
		$retVal = '';
		
		// 機種依存文字の置換
		$retVal = dependedCharConvert($inputStr);
		
		// ダブルコーテーションの変換
		$retVal = str_replace('""', '"', $retVal);
		
		return $retVal;
	}
}

// ------------------------------------------------------------------------

	/**
	 * csvImportFormatCRLF
	 *
	 * CSVインポート用データに整形する
	 *
	 * @access	public
	 * @string
	 * @boolean
	 * @return
	 */
if ( ! function_exists('csvImportFormatCRLF'))
{
	function csvImportFormatCRLF($inputStr = '')
	{
		// 変換した文字列
		$retVal = '';
		
		// ダブルコーテーションの変換
		$retVal = str_replace('""', '"', $inputStr);
		
		// 改行文字の変換
		$retVal = str_replace(array('<br />', '<br>', '<BR />', '<BR>'), array("\n", "\n", "\n", "\n"), $retVal);
		
		// 機種依存文字の置換
		$retVal = dependedCharConvert($retVal);
		
		return $retVal;
	}
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
if ( ! function_exists('formalizeMapURL'))
{
	function formalizeMapURL($inputStr = '', $isEnquote = false)
	{
		return $inputStr;
	}
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
if ( ! function_exists('mapURLConvert'))
{
	function mapURLConvert($inputStr = '', $isEnquote = false)
	{
		return $inputStr;
	}
}

// ------------------------------------------------------------------------

	/**
	 * 入力変換 全角スペース → 半角スペース
	 *
	 * @access public
	 * @param string
	 * @return string
	 */
if ( ! function_exists('fullSpaceConvert'))
{
	function fullSpaceConvert($inputStr = "")
	{
	    $str = mb_convert_encoding($inputStr, 'UTF-8', 'auto');
	    $str = mb_ereg_replace("　", " ", $str);

	    return $str;
	}
}

// ------------------------------------------------------------------------

	/**
	 * zenKanaToHanKana
	 *
	 * 全角カタカナを半角に変換
	 *
	 * @access	public
	 * @string
	 * @return
	 */
if ( ! function_exists('zenKanaToHanKana'))
{
	function zenKanaToHanKana($inputStr = '')
	{
		$source = array("ヴ","ヰ","ヱ","ガ","ギ","グ","ゲ","ゴ","ザ","ジ","ズ","ゼ","ゾ","ダ","ヂ","ヅ","デ","ド","バ","ビ","ブ","ベ","ボ","パ","ピ","プ","ペ","ポ","ア","イ","ウ","エ","オ","カ","キ","ク","ケ","コ","サ","シ","ス","セ","ソ","タ","チ","ツ","テ","ト","ナ","ニ","ヌ","ネ","ノ","ハ","ヒ","フ","ヘ","ホ","マ","ミ","ム","メ","モ","ヤ","ユ","ヨ","ラ","リ","ル","レ","ロ","ワ","ヲ","ン","ァ","ィ","ゥ","ェ","ォ","ャ","ュ","ョ","ッ","ー","゛","゜","・","「","」","、","。");
		$dest = array("ｳﾞ","ｲ","ｴ","ｶﾞ","ｷﾞ","ｸﾞ","ｹﾞ","ｺﾞ","ｻﾞ","ｼﾞ","ｽﾞ","ｾﾞ","ｿﾞ","ﾀﾞ","ﾁﾞ","ﾂﾞ","ﾃﾞ","ﾄﾞ","ﾊﾞ","ﾋﾞ","ﾌﾞ","ﾍﾞ","ﾎﾞ","ﾊﾟ","ﾋﾟ","ﾌﾟ","ﾍﾟ","ﾎﾟ","ｱ","ｲ","ｳ","ｴ","ｵ","ｶ","ｷ","ｸ","ｹ","ｺ","ｻ","ｼ","ｽ","ｾ","ｿ","ﾀ","ﾁ","ﾂ","ﾃ","ﾄ","ﾅ","ﾆ","ﾇ","ﾈ","ﾉ","ﾊ","ﾋ","ﾌ","ﾍ","ﾎ","ﾏ","ﾐ","ﾑ","ﾒ","ﾓ","ﾔ","ﾕ","ﾖ","ﾗ","ﾘ","ﾙ","ﾚ","ﾛ","ﾜ","ｦ","ﾝ","ｧ","ｨ","ｩ","ｪ","ｫ","ｬ","ｭ","ｮ","ｯ","ｰ","ﾞ","ﾟ","･","｢","｣","､","｡");
		
		return str_replace($source, $dest, utfEscape($inputStr));
	}
}

// ------------------------------------------------------------------------

/**
 * mobileAsciiNormalize
 *
 * ASCII文字の全角→半角変換（携帯用に「～」を除いて変換）
 *
 * @access	public
 * @string
 * @return
 */
if ( ! function_exists('mobileAsciiNormalize'))
{
	function mobileAsciiNormalize($inputStr = '')
	{
		$source = array("　","！","“","”","＃","＄","％","＆","‘","’","（","）","＊","＋","－","．","／","０","１","２","３","４","５","６","７","８","９","：","；","＜","＝","＞","？","＠","Ａ","Ｂ","Ｃ","Ｄ","Ｅ","Ｆ","Ｇ","Ｈ","Ｉ","Ｊ","Ｋ","Ｌ","Ｍ","Ｎ","Ｏ","Ｐ","Ｑ","Ｒ","Ｓ","Ｔ","Ｕ","Ｖ","Ｗ","Ｘ","Ｙ","Ｚ","［","￥","］","＾","＿","｀","ａ","ｂ","ｃ","ｄ","ｅ","ｆ","ｇ","ｈ","ｉ","ｊ","ｋ","ｌ","ｍ","ｎ","ｏ","ｐ","ｑ","ｒ","ｓ","ｔ","ｕ","ｖ","ｗ","ｘ","ｙ","ｚ","｛","｜","｝");
		$dest = array(" ","!",'"','"',"#","$","%","&","'","'","(",")","*","+","-",".","/","0","1","2","3","4","5","6","7","8","9",":",";","<","=",">","?","@","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","[","\\","]","^","_","`","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","{","|","}");

		return str_replace($source, $dest, utfEscape($inputStr));
	}
}

// ------------------------------------------------------------------------

/**
 * htmlEditFormatCRLFEnableAnchor
 *
 * htmlEditFormatCRLFをしつつ、anchorタグはエスケープしない
 *
 * @access	public
 * @string
 * @return
 */
if ( ! function_exists('htmlEditFormatCRLFEnableAnchor'))
{
	function htmlEditFormatCRLFEnableAnchor($inputStr = '')
	{
		return preg_replace_callback(
			"/&lt;a(.+?)&gt;(.+?)&lt;\/a&gt;/i",
			function($match) {
				return "<a"
						.str_replace("&nbsp;", " ", htmlspecialchars_decode($match[1]))
						. ">"
						. $match[2]
						. "</a>";
			},
			htmlEditFormatCRLF($inputStr));
	}
}
