<?php
namespace App\Http\Controllers\Api;

use App\Models\Posts;
use App\Models\Category;
use App\CardBuilder;
use App\Models\Relative;

class CategoryController extends BaseFrontController
{
	const LIMIT_POSTS = 50;
	public function show($id){
		$response = [
			'body' => [],
			'confirm' => 'error'
		];
		$category = new Category();
		$data = $category->getPublicPostByUrl($id);
		if(!$data->isEmpty()) {
			$response['body'] = self::dataMetaDecode($data[0]);
			$response['body']['parent_id'] = $data[0]->parent_id;
			$post_type = $data[0]->parent_id == 1 ? 'rome' : 'emulator';

			$post_id = Relative::getPostIdByRelative('post_category', $data[0]->id);
			$posts = new Posts(['post_type'=> $post_type]);
			$data = $posts->getPublicPostsByArrId($post_id);
			$response['body']['posts'] = $data;
			$response['confirm'] = 'ok';
		}
		return response()->json($response);
	}
	public function roms(){
		$response = [
			'body' => [],
			'confirm' => 'error'
		];
		$category = new Category();
		$data = $category->getPublicPostByUrl(config('constants.CATEGORY.ROMS.URL'));
		if(!$data->isEmpty()) {
			$response['body'] = self::dataMetaDecode($data[0]);
			$settings = [
				'limit' => 1000,
				'parent_id' => config('constants.CATEGORY.ROMS.ID')
			];
			$list_category = $category->getPublicPosts($settings);
			$response['body']['category'] = CardBuilder::categoryCardWithDownload($list_category, 'roms');
			$response['confirm'] = 'ok';
		}
		return response()->json($response);
	}
	public function emulators(){
		$response = [
			'body' => [],
			'confirm' => 'error'
		];
		$category = new Category();
		$data = $category->getPublicPostByUrl(config('constants.CATEGORY.ROMS.URL'));
		if(!$data->isEmpty()) {
			$response['body'] = self::dataMetaDecode($data[0]);
			$settings = [
				'limit' => 1000,
				'parent_id' => config('constants.CATEGORY.EMULATORS.ID')
			];
			$list_category = $category->getPublicPosts($settings);
			$response['body']['category'] = CardBuilder::categoryCardWithDownload($list_category, 'emulators');
			$response['confirm'] = 'ok';
		}
		return response()->json($response);
	}
}

