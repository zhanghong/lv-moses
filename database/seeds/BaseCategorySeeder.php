<?php

use Illuminate\Database\Seeder;

use App\Models\Base\Category;

class BaseCategorySeeder extends Seeder
{
    public function run()
    {
        // 微信精品店分类数据（https://wsad.weixin.qq.com/official/getitemcategory?appid=xxxx）
        $file_name = 'files/wx_categories.json';
        $content = file_get_contents(base_path($file_name));
        $data = json_decode($content, true);
        if (isset($data['category_list']) && is_array($data['category_list'])) {
            // 主键对应列表
            $refs = [];

            foreach ($data['category_list'] as $key => $item) {
                if ($item['parent_id']) {
                    $parent_key = strval($item['parent_id']);
                }else {
                    $parent_key = null;
                }

                $category = Category::firstOrNew(['outer_name' => 'wechat', 'outer_key' => strval($item['id'])]);
                $category->name = trim($item['category_name']);
                if ($parent_key && isset($refs[$parent_key])) {
                    $category->parent_id = $refs[$parent_key];
                } else {
                    $category->parent_id = 0;
                }

                if (!$category->exists) {
                    // 新建的分类默认是可见的
                    $category->is_enabled = true;
                }

                $category->save();
                // 添加到主键对应列表
                $refs[$category->outer_key] = $category->id;

                // if ($key > 10) {
                //     break;
                // }
            }
        }

        return ;
    }
}
