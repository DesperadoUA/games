<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
}
