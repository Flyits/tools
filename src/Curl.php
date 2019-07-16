<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/9
 * Time: 17:57
 */
declare(strict_types = 1);

namespace flyits\tool;

class Curl
{
	/**
	 * @param $url
	 * @return bool|mixed
	 */
	public static function get(string $url)
	{
		$ch = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$sContent = curl_exec($ch);
		$aStatus  = curl_getinfo($ch);
		curl_close($ch);
		if (intval($aStatus["http_code"]) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
	
	/**
	 *
	 * @param string $url
	 * @param string $data
	 * @type string  $type
	 * @return mixed
	 */
	public static function post($url, $data, $type = 'json')
	{
		$data_string = json_encode($data, JSON_UNESCAPED_UNICODE);
		$ch          = curl_init();
		$typeList    = [
			'json'       => 'Content-Type: application/json',
			'text'       => 'Content-Type: text',
			'urlencoded' => 'Content-Type: application/x-www-form-urlencoded',
		];
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array($typeList[$type]));
		$sContent = curl_exec($ch);
		$aStatus  = curl_getinfo($ch);
		curl_close($ch);
		if (intval($aStatus["http_code"]) == 200) {
			return $sContent;
		} else {
			return false;
		}
		
	}
}