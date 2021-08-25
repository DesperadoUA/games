<?php
namespace App\Http\Controllers\Api;

use App\Models\Posts;
use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Category;
use App\CardBuilder;

class PageController extends BaseFrontController
{
	const TABLE = 'pages';
	const TABLE_CATEGORY = 'category';
	const TABLE_POSTS = 'posts';
	const POST_TYPE = 'page';
	const OFFSET = 0;
	const LIMIT = 8;
	const MAIN_PAGE_LIMIT_CATEGORY = 6;
	const LIMIT_POPULAR_GAMES = 8;

	public function main()
	{
		$response = [
			'body' => [],
			'confirm' => 'error'
		];
		$post = new Pages();
		$data = $post->getPublicPostByUrl('/');
		if (!$data->isEmpty()) {
			$response['body'] = self::dataMetaDecode($data[0]);
			$category = new Category();
			$settings = [
				'limit' => self::MAIN_PAGE_LIMIT_CATEGORY,
				'parent_id' => config('constants.CATEGORY.ROMS.ID')
			];
			$list_category = $category->getPublicPosts($settings);
			$response['body']['category'] = CardBuilder::categoryCard($list_category, 'roms');
			$rome = new Posts(['post_type' => 'rome']);
			$settings = [
				'lang'      => $data[0]->lang,
				'limit'     => self::LIMIT_POPULAR_GAMES,
				'order_key' => 'rating'
			];
			$response['body']['rome'] = CardBuilder::postCard($rome->getPublicPosts($settings));
			$response['confirm'] = 'ok';
		}
		return response()->json($response);
	}
}

