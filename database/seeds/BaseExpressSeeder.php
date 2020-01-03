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
            ['code' => 'shentong', 'name' => '申通快递'],
            ['code' => 'yunda', 'name' => '韵达速递'],
            ['code' => 'zhongtong', 'name' => '中通快递'],
            ['code' => 'shunfeng', 'name' => '顺丰速运'],
            ['code' => 'ems', 'name' => 'EMS'],
            ['code' => 'zhaijisong', 'name' => '宅急送'],
            ['code' => 'quanfengkuaidi', 'name' => '全峰快递'],
            ['code' => 'huitongkuaidi', 'name' => '百世快递'],
            ['code' => 'tiantian', 'name' => '天天快递'],
            ['code' => 'youshuwuliu', 'name' => '优速物流'],
            ['code' => 'debangwuliu', 'name' => '德邦快递'],
        ];
        foreach ($list as $key => $item) {
            $express = Express::firstOrNew(['code' => $item['code']]);
            if (!$express->exists) {
                // 新建的分类默认是可见的
                $express->is_enabled = true;
            }
            $express->save();
        }
    }
}
