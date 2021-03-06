<?php

namespace App\Http\Controllers\Api;

use App\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Relative;
use App\Models\Posts;
use App\Models\Category;


class AdminPostsController extends Controller
{
    function __construct(Request $request)
    {

    }
    const OFFSET    = 0;
    const LIMIT     = 8;
    const ORDER_BY  = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG      = 1;
    const ARR_LANG  = [
        'ru' => 1,
        'ua' => 2
    ];
    const DEFAULT_POST_TYPE = 'emulator';
    const SLUG              = 'emulator';
	const CATEGORY_RELATIVE_TABLE  = 'post_category';
	const CATEGORY_TABLE           = 'category';

    public function delete(Request $request) {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        Posts::where('id', $request->input('data'))->delete();
        return response()->json($response);
    }
    protected static function dataValidateSave($id, $data) {
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['create_at'])) {
            $newData['create_at'] = $data['create_at'];
        }
        else {
            $newData['create_at'] = date('Y-m-d');
        }

        if(isset($data['update_at'])) {
            $newData['update_at'] = $data['update_at'];
        }
        else {
            $newData['update_at'] = date('Y-m-d');
        }

        if(isset($data['content'])) {
            $newData['content'] = Validate::textValidate($data['content']);
        }
        else {
            $newData['content'] = '';
        }

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }

        if(isset($data['permalink'])) {
            $newData['permalink'] = self::permalinkUpdate($id, $data['permalink']);
        }
        elseif (empty($data['permalink'])) {
            $newData['permalink'] = self::permalinkUpdate($id, $data['title']);
        }
        else {
            $newData['permalink'] = self::permalinkUpdate($id, $data['title']);
        }

        return $newData;
    }
    protected static function dataValidateInsert($data){
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['create_at'])) {
            $newData['create_at'] = $data['create_at'];
        }
        else {
            $newData['create_at'] = date('Y-m-d');
        }

        if(isset($data['update_at'])) {
            $newData['update_at'] = $data['update_at'];
        }
        else {
            $newData['update_at'] = date('Y-m-d');
        }

        if(isset($data['content'])) {
            $newData['content'] = Validate::textValidate($data['content']);
        }
        else {
            $newData['content'] = '';
        }

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }

        if(isset($data['title'])) {
            $newData['permalink'] = self::permalinkInsert($data['title']);
        }

        if(isset($data['lang'])) {
            if(isset(self::ARR_LANG[$data['lang']])) {
                $newData['lang'] = self::ARR_LANG[$data['lang']];
            } else {
                $newData['lang'] = self::ARR_LANG['ru'];
            }
        }

        if(isset($data['post_type'])) {
            $newData['post_type'] = $data['post_type'];
        } else {
            $newData['post_type'] = self::DEFAULT_POST_TYPE;
        }

        if(isset($data['slug'])) {
            $newData['slug'] = $data['slug'];
        } else {
            $newData['slug'] = self::SLUG;
        }

        return $newData;
    }
    protected static function permalinkUpdate($id, $permalink) {
        $candidate = Posts::getByPermalink($permalink);
        if($candidate->isEmpty()) {
            return str_slug($permalink);
        }
        else {
            if($candidate[0]->id === $id) return $permalink;
            else {
                $counter = 0;
                do {
                    $counter++;
                    $new_permalink = $permalink.'-'.$counter;
                    $new_candidate = Posts::getByPermalink($new_permalink);
                    if($new_candidate->isEmpty()) break;
                } while (true);
                return str_slug($new_permalink);
            }
        }
    }
    protected static function permalinkInsert($permalink) {
        $permalink = str_slug($permalink);
        $candidate = Posts::getByPermalink($permalink);
        if($candidate->isEmpty()) {
            return $permalink;
        }
        else {
            $counter = 0;
            do {
                $counter++;
                $new_permalink = $permalink.'-'.$counter;
                $new_candidate = Posts::getByPermalink($new_permalink);
                if($new_candidate->isEmpty()) break;
            } while (true);
            return $new_permalink;
        }
    }
    protected static function dataCommonDecode($data) {
        $newData =  [];
        $newData['id'] = $data->id;
        $newData['title'] =  htmlspecialchars_decode($data->title, ENT_NOQUOTES);
        $newData['status'] = $data->status;
        $newData['create_at'] = $data->create_at;
        $newData['update_at'] = $data->update_at;
        $newData['slug'] = $data->slug;
        $newData['content'] = htmlspecialchars_decode($data->content, ENT_NOQUOTES);
        $newData['description'] = htmlspecialchars_decode($data->description, ENT_NOQUOTES);
        $newData['h1'] = htmlspecialchars_decode($data->h1, ENT_NOQUOTES);
        $newData['keywords'] = htmlspecialchars_decode($data->keywords, ENT_NOQUOTES);
        $newData['meta_title'] = htmlspecialchars_decode($data->meta_title, ENT_NOQUOTES);
        $newData['short_desc'] = htmlspecialchars_decode($data->short_desc, ENT_NOQUOTES);
        $newData['thumbnail'] = $data->thumbnail;
        $newData['permalink'] = $data->permalink;
        return $newData;
    }
	protected static function relativeCategory($id) {
		$data = [];
		$current_post = Posts::where('id', $id)->get();
		if($current_post->isEmpty()) {
			return $data;
		}
		else {
			$arr_title_category = [];
			$category = new Category();
			$list_category = $category->getAllPostsByLang($current_post[0]->lang);
			if(!$list_category->isEmpty()) {
				foreach ($list_category as $item) $arr_title_category[] = $item->title;
			}
			$data['all_value'] = $arr_title_category;
			$arr_relative_category_id = Relative::getRelativeByPostId(self::CATEGORY_RELATIVE_TABLE, $current_post[0]->id);
			if(empty($arr_relative_category_id)) $data['current_value'] = [];
			else {
				$arr_category = DB::table(self::CATEGORY_TABLE)
					->whereIn('id', $arr_relative_category_id)
					->get();
				$data['current_value'] = [];
				foreach ($arr_category as $item) $data['current_value'][] = $item->title;
			}
			return $data;
		}
	}
	public function updateCategory($id, $arr_titles) {
		DB::table(self::CATEGORY_RELATIVE_TABLE)->where('post_id', $id)->delete();
		if(!empty($arr_titles)) {
			$current_post = Posts::where('id', $id)
				->get();
			if(!$current_post->isEmpty()) {
				$arr_category = DB::table(self::CATEGORY_TABLE)
					->whereIn('title', $arr_titles)
					->get();
				$data = [];
				foreach ($arr_category as $item) {
					$data[] = [
						'post_id' => $current_post[0]->id,
						'relative_id' => $item->id
					];
				}
				Relative::insert(self::CATEGORY_RELATIVE_TABLE, $data);
			}
		}
	}
}
