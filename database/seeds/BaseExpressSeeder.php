<?php

use Illuminate\Database\Seeder;
use App\Models\Base\Express;

class BaseExpressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [
            ['code' => 'ems', 'name' => 'EMS', 'outer_key' => '2000'],
            ['code' => 'jingdong', 'name' => '京东物流', 'outer_key' => '2017'],
            ['code' => 'shunfeng', 'name' => '顺丰速运', 'outer_key' => '2011'],
            ['code' => 'yunda', 'name' => '韵达', 'outer_key' => '2005'],
            ['code' => 'dhl', 'name' => 'DHL', 'outer_key' => '2002'],
            ['code' => 'yuantong', 'name' => '圆通', 'outer_key' => '2001'],
            ['code' => 'zhongtong', 'name' => '中通', 'outer_key' => '2004'],
            ['code' => 'huitongkuaidi', 'name' => '百世快递', 'outer_key' => '2008'],
            ['code' => 'debangwuliu', 'name' => '德邦', 'outer_key' => '2009'],
            ['code' => 'shentong', 'name' => '申通快递', 'outer_key' => '2010'],
            ['code' => 'shunxing', 'name' => '顺兴', 'outer_key' => '2012'],
            ['code' => 'rufeng', 'name' => '如风达', 'outer_key' => '2014'],
            ['code' => 'yousu', 'name' => '优速', 'outer_key' => '2015'],
            ['code' => 'changling', 'name' => '畅灵', 'outer_key' => '2006'],
            ['code' => 'zhaijisong', 'name' => '宅急送'],
            ['code' => 'quanfeng', 'name' => '全峰快递'],
            ['code' => 'tiantian', 'name' => '天天快递'],
        ];
        foreach ($list as $key => $item) {
            $express = Express::firstOrNew(['code' => $item['code']]);
            if (!$express->exists) {
                // 新建的分类默认是可见的
                $express->is_enabled = true;
            }
            if (isset($item['outer_key'])) {
                $express->outer_name = Express::OUTER_FROM_WECHAT;
                $express->outer_key = $item['outer_key'];
            } else {
                $express->outer_name = '';
                $express->outer_key = '';
            }
            $express->name = $item['name'];
            $express->order = $key + 1;
            $express->save();
        }
    }
}
