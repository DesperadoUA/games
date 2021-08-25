<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
/**
 * Created by PhpStorm.
 * User: Костя
 * Date: 22.08.2021
 * Time: 11:40
 */
class BaseFrontController extends Controller {
	protected static function dataMetaDecode($data)
	{
		$newData = [];
		$newData['title'] = htmlspecialchars_decode($data->title);
		$newData['short_desc'] = htmlspecialchars_decode($data->short_desc);
		$newData['h1'] = htmlspecialchars_decode($data->h1);
		$newData['meta_title'] = htmlspecialchars_decode($data->meta_title);
		$newData['description'] = htmlspecialchars_decode($data->description);
		$newData['keywords'] = htmlspecialchars_decode($data->keywords);
		$str = str_replace('<pre', '<div', htmlspecialchars_decode($data->content));
		$str = str_replace('</pre', '</div', $str);
		$str = str_replace('&nbsp;', '', $str);
		$str = str_replace('<p><br></p>', '', $str);
		$str = str_replace('<p></p>', '', $str);
		$newData['content'] = htmlspecialchars_decode($str);
		return $newData;
	}
}