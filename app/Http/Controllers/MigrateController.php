<?php

namespace App\Http\Controllers;

use DB;

class MigrateController extends Controller
{
    public function CategoryMigrate(){
    	$data = DB::table('categories')->get();
    	$new_data = [];
    	foreach ($data as $item) {
    		$meta = json_decode($item->extras, true);
    		$new_data[] = [
    			'id'          => $item->id,
				'permalink'   => $item->slug,
				'title'       => empty($item->title) ? '': $item->title,
				'thumbnail'   => empty($item->image) ? '': $item->image,
				'short_desc'  => empty($item->short_description) ? '': $item->short_description,
				'h1'          => empty($item->title) ? '': $item->title,
				'meta_title'  => empty($meta['meta_title']) ? '': $meta['meta_title'],
				'description' => empty($meta['meta_description']) ? '': $meta['meta_description'],
				'keywords'    => empty($meta['meta_keywords']) ? '': $meta['meta_keywords'],
				'content'     => empty($item->content) ? '': $item->content,
				'parent_id'   => empty($item->parent_id) ? 0: $item->parent_id
			];
		}
		//DB::table('new_categories')->insert($new_data);
    	return 'Category Migrate';
	}
	public function EmulatorsMigrate(){
    	$data = DB::table('emulators')->get();
		foreach ($data as $item) {
			$meta = json_decode($item->extras, true);
			$new_data[] = [
				'id'          => $item->id,
				'permalink'   => $item->slug,
				'post_type'   => 'emulator',
				'slug'        => 'emulator',
				'title'       => empty($item->title) ? '': $item->title,
				'thumbnail'   => empty($item->image) ? '': $item->image,
				'short_desc'  => empty($item->short_description) ? '': $item->short_description,
				'h1'          => empty($item->title) ? '': $item->title,
				'meta_title'  => empty($meta['meta_title']) ? '': $meta['meta_title'],
				'description' => empty($meta['meta_description']) ? '': $meta['meta_description'],
				'keywords'    => empty($meta['meta_keywords']) ? '': $meta['meta_keywords'],
				'content'     => empty($item->content) ? '': $item->content,
			];
			$meta_data[] = [
				'post_id' => $item->id,
				'file_id' => $item->file_id,
				'icon'    => $item->icon
			];
			$category[] = [
				'post_id'     => $item->id,
				'relative_id' => $item->category_id
			];
		}
		//DB::table('posts')->insert($new_data);
		//DB::table('emulator_meta')->insert($meta_data);
		//DB::table('post_category')->insert($category);
	}
	public function RomesMigrate(){
		set_time_limit(0);
		$data = DB::table('romes')->get();
		foreach ($data as $item) {
			$meta = json_decode($item->extras, true);
			$new_data = [
				'permalink'   => $item->slug,
				'post_type'   => 'rome',
				'slug'        => 'rome',
				'title'       => empty($item->title) ? '': $item->title,
				'thumbnail'   => empty($item->image) ? '': $item->image,
				'short_desc'  => empty($item->short_description) ? '': $item->short_description,
				'h1'          => empty($item->title) ? '': $item->title,
				'meta_title'  => empty($meta['meta_title']) ? '': $meta['meta_title'],
				'description' => empty($meta['meta_description']) ? '': substr($meta['meta_description'], 250),
				'keywords'    => empty($meta['meta_keywords']) ? '': $meta['meta_keywords'],
				'content'     => empty($item->content) ? '': $item->content,
			];

			/*$insert_id = DB::table('posts')->insertGetId($new_data);

			$meta_data = [
				'post_id' => $insert_id,
				'file_id' => $item->file_id,
				'icon'    => $item->icon,
				'genre'   => empty($item->genre) ? '': $item->genre
			];

			DB::table('rome_meta')->insert($meta_data);
			$category = [
				'post_id'     => $insert_id,
				'relative_id' => $item->category_id
			];
			DB::table('post_category')->insert($category);
*/
		}
	}
	public function SetRating(){
		/*$posts = DB::table('posts')->where('post_type', 'rome')->get();
		foreach ($posts as $item) {
			$data = [
				'rating' => random_int(1, 100)
			];
			DB::table('rome_meta')
				->where('post_id', $item->id)
				->update($data);
		}
		*/
	}
}
