<?php

use Illuminate\Database\Seeder;

use App\Models\User\User;
use App\Models\Shop\Shop;

class DefaultShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manager = User::first();
        if (!$manager) {
            return false;
        }

        $data = [
            'name' => '店铺名称',
            'seo_keywords' => 'SEO 关键词',
            'seo_description' => 'SEO 描述',
            'introduce' => '简单介绍一下自己的店铺概况',
        ];

        Shop::createDefault($data, $manager);
    }
}
