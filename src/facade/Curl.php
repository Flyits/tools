<?php
/**
 * Created by PhpStorm.
 * User: flyits
 * Date: 2019/7/29
 * Time: 11:20
 */

namespace flyits\tool\facade;


class Curl extends Facade
{
	protected static function getFacadeClass()
	{
		return 'flyits\tool\Curl';
	}
}