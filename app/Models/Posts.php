<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Relative;
use App\Models\Category;

class Posts extends Model
{
    protected $post_type;
    protected $table_meta;
    function __construct(array $attributes = [])
    {
        parent::__construct([]);
        $this->post_type = isset($attributes['post_type']) ? $attributes['post_type'] : 'emulator';
        $this->table_meta = $this->post_type.'_meta';
    }

    const LIMIT     = 8;
    const OFFSET    = 0;
    const TABLE     = 'posts';
    const ORDER_BY  = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG      = 1;
    const CATEGORY_RELATIVE_DB = 'post_category';
    /*
     * $settings = [
     *      'offset' => 8,
     *      'limit' => 10,
     *      'order_by' => 'desc',
     *      'order_key' => 'create_at'
     *      'lang' => 1
     * ];
     * */
    public function getPublicPosts($settings = []) {
        $limit     = isset($settings['limit']) ? $settings['limit'] : self::LIMIT;
        $offset    = isset($settings['offset']) ? $settings['offset'] : self::OFFSET;
        $order_by  = isset($settings['order_by']) ? $settings['order_by'] : self::ORDER_BY;
        $order_key = isset($settings['order_key']) ? $settings['order_key'] : self::ORDER_KEY;
        $lang      = isset($settings['lang']) ? $settings['lang'] : self::LANG;

        $t1 = self::TABLE;
        $t2 = $this->table_meta;

        $data = [];

        $posts = DB::table($t1)
            ->where($t1.'.status',  'public')
            ->where($t1.'.post_type', $this->post_type)
            ->where($t1.'.lang', $lang)
            ->join($t2, $t1.'.id', '=', $t2.'.post_id')
            ->select( $t1.'.*', $t2.'.*')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order_key, $order_by)
            ->get();

        if(!$posts->isEmpty()) {
        	foreach($posts as $item) {
        		$category_id = Relative::getRelativeByPostId(self::CATEGORY_RELATIVE_DB, $item->id);
				if(empty($category_id)){
					$data[] = array_merge(get_object_vars($item), ['category' => []]);
				}
				else {
					$category = new Category();
					$cat_data = $category->getPublicPostById($category_id);
					$data[] = array_merge(get_object_vars($item),
						['category' => $cat_data->isEmpty() ? [] : $cat_data[0]]
					);
				}
			}
		}
        return $data;
    }
    public function getPublicPostByUrl($url) {
        $t1 = self::TABLE;
        $t2 = $this->table_meta;

        $post = DB::table($t1)
            ->where($t1.'.permalink', $url)
            ->where($t1.'.status','public')
            ->join($t2, $t1.'.id', '=', $t2.'.post_id')
            ->select( $t1.'.*', $t2.'.*')
            ->get();
        return $post;
    }
    public function insert($common_data, $meta_data) {
        $insert_id = DB::table(self::TABLE)->insertGetId($common_data);
        $meta_data['post_id'] = $insert_id;
        DB::table($this->table_meta)->insert($meta_data);
        return $insert_id;
    }
    public function getTotalCountPublicByLang($lang = self::LANG) {
        return  DB::table(self::TABLE)
                    ->where('status', 'public')
                    ->where('lang', $lang)
                    ->count();
    }
    public function getAll($post_type){
        $post = DB::table(self::TABLE)
            ->where('post_type', $post_type)
            ->orderBy('create_at', 'desc')
            ->get();
        return $post;
    }
    public function getPostById($id) {
        $t1 = self::TABLE;
        $t2 = $this->table_meta;

        $post = DB::table($t1)
            ->where( 'id', $id)
            ->join($t2, $t1.'.id', '=', $t2.'.post_id')
            ->select( $t1.'.*', $t2.'.*')
            ->get();
        return $post;
    }
    public function updateById($id, $data) {
        DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }
    public static function getByPermalink($permalink) {
        $post = DB::table(self::TABLE)
                ->where( 'permalink', $permalink)
                ->get();
        return $post;
    }
    public function getPosts($settings = []) {
        $limit     = isset($settings['limit']) ? $settings['limit'] : self::LIMIT;
        $offset    = isset($settings['offset']) ? $settings['offset'] : self::OFFSET;
        $order_by  = isset($settings['order_by']) ? $settings['order_by'] : self::ORDER_BY;
        $order_key = isset($settings['order_key']) ? $settings['order_key'] : self::ORDER_KEY;
        $lang      = isset($settings['lang']) ? $settings['lang'] : self::LANG;

        $t1 = self::TABLE;
        $t2 = $this->table_meta;

        $posts = DB::table($t1)
            ->where($t1.'.post_type', $this->post_type)
            ->where($t1.'.lang', $lang)
            ->join($t2, $t1.'.id', '=', $t2.'.post_id')
            ->select( $t1.'.*', $t2.'.*')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order_key, $order_by)
            ->get();
        return $posts;
    }
    public function getTotalCountByLang($lang = self::LANG) {
        return  DB::table(self::TABLE)
            ->where('post_type', $this->post_type)
            ->where('lang', $lang)
            ->count();
    }
    public function relativeCategoryInsert($id, $arr_category_id){
    	if(!empty($arr_category_id)) {
    		$data = [];
    		foreach ($arr_category_id as $item) {
    			$data[] = [
    				'post_id'     => $id,
					'category_id' => $item
				];
			}
			DB::table(self::CATEGORY_RELATIVE_DB)
				->insert($data);
		}
	}
	public function updateMetaById($id, $data) {
		DB::table($this->table_meta)
			->where('post_id', $id)
			->update($data);
	}
	public static function clearCategoryPostById($id) {
		DB::table(self::CATEGORY_RELATIVE_DB)
			->where('post_id', $id)
			->delete();
    }
	public static function searchByTitle($lang, $post_type, $str) {
		$posts = [];
		if(!empty($str)) {
			$posts = DB::table(self::TABLE)
				->where('post_type',  $post_type)
				->where('lang', $lang)
				->where('title', 'like', '%'.$str.'%')
				->get();
		}
		return $posts;
	}
	public function getPublicPostsByArrId($arr) {
		$order_by  = self::ORDER_BY;
		$order_key = self::ORDER_KEY;

		$t1 = self::TABLE;
		$t2 = $this->table_meta;

		if(empty($arr)) return [];
		$posts = DB::table($t1)
			->where($t1.'.status',  'public')
			->whereIn($t1.'.id', $arr)
			->join($t2, $t1.'.id', '=', $t2.'.post_id')
			->select( $t1.'.*', $t2.'.*')
			->orderBy($order_key, $order_by)
			->get();
		return $posts;
	}
}