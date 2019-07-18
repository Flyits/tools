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
	 * @param string $url
	 * @param int    $return 返回响应数据或包含状态等全部数据
	 * @return bool|mixed
	 */
	public static function get(string $url, int $return = 0)
	{
		$ch = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result['body'] = curl_exec($ch);
		$result['info'] = curl_getinfo($ch);
		curl_close($ch);
		return $return ? $return : $result['body'];
	}
	
	/**
	 *
	 * @param string $url
	 * @param array  $data
	 * @type string  $type
	 * @param int    $return 返回响应数据或包含状态等全部数据
	 * @return mixed
	 */
	public static function post(string $url, array $data = [], string $type = 'json', int $return = 0)
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
		$result['body'] = curl_exec($ch);
		$result['info'] = curl_getinfo($ch);
		curl_close($ch);
		return $return ? $return : $result['body'];
		
	}
	
	/**
	 * 上传文件
	 * @param string $url
	 * @param array  $data
	 * @param string $file
	 * @param int    $return 返回响应数据或包含状态等全部数据
	 * @author flyits
	 * @time   2019/7/17 17:34
	 * @throws
	 * @return mixed
	 */
	public static function postFile(string $url, array $data, string $file = 'media', int $return = 0)
	{
		$ch = curl_init($url);
		//判断PHP版本,以便使用兼容curl文件上传
		if (class_exists('CURLFile')) {
			$data[$file] = new \CURLFile($data[$file], '', '');
			curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
		} else {
			$data[$file] = "@" . $data[$file];
		}
		//        var_dump($file);exit();
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result['body'] = curl_exec($ch);
		$result['info'] = curl_getinfo($ch);
		curl_close($ch);
		return $return ? $return : $result['body'];
	}
}