<?php
namespace App;
use App\Models\Posts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Relative;

class CardBuilder {
	static function searchAdminCard($arr_posts) {
		if(empty($arr_posts)) return [];
		$posts = [];
		foreach ($arr_posts as $item) {
			$posts[] = [
				'title' => $item->title,
				'permalink' => '/admin/'.$item->post_type.'/'.$item->id
			];
		}
		return $posts;
	}
	static function categoryCard($arr_posts, $slug) {
		if(empty($arr_posts)) return [];
		$posts = [];
		foreach ($arr_posts as $item) {
			$posts[] = [
				'title' => $item->title,
				'thumbnail' => $item->thumbnail,
				'permalink' => '/'.$slug.'/'.$item->permalink
			];
		}
		return $posts;
	}
	static function postCard($arr_posts) {
		if(empty($arr_posts)) return [];
		$posts = [];
		foreach ($arr_posts as $item) {
			$posts[] = [
				'title' => $item['title'],
				'thumbnail' => $item['thumbnail'],
				'permalink' => '/'.$item['slug'].'/'.$item['category']->permalink.'/'.$item['permalink']
			];
		}
		return $posts;
	}
	static function categoryCardWithDownload($arr_posts, $slug){
		if(empty($arr_posts)) return [];
		$posts = [];
		foreach ($arr_posts as $item) {
			$posts_id = Relative::getPostIdByRelative('post_category', $item->id);
			$posts[] = [
				'title' => $item->title,
				'thumbnail' => $item->thumbnail,
				'permalink' => '/'.$slug.'/'.$item->permalink,
				'posts_count' => count($posts_id)
			];
		}
		return $posts;
	}
}