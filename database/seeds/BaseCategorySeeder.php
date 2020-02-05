<?php

use Illuminate\Database\Seeder;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ConnectException;
use App\Models\Category\Category;

class BaseCategorySeeder extends Seeder
{
    public function run()
    {
        // $file_name = 'files/wx_categories.json';
        // $content = file_get_contents(base_path($file_name));
        $content = $this->syncFromWechat();
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

                $category = Category::firstOrNew([
                    'outer_name' => Category::OUTER_FROM_WECHAT,
                    'outer_key' => strval($item['id'])
                ]);
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

    private function syncFromWechat()
    {
        $jar = new CookieJar;
        $appid = 'wx362ccbae95e206c5';
        $cookies = CookieJar::fromArray([
                            'ad_session_searchzone' => 'gm_NMJ9ERRLkW_zivmN7afCou_ZjDoOv',
                            'appid_searchzone' => $appid,
                            'data_bizuin' => '3001033114',
                            'data_ticket    ' => '00ePhANA7EIk1zqBqyWIOds2m63SK0qlE',
                            'mmsearch_flag' => 'C06F544A54E573E2026B7B62AA7DF05E754D789F5A8573978C23A8C8D707666A',
                            'plugin_id' => 'searchzone',
                        ], 'wsad.weixin.qq.com');

        $client = new Client();
        $array = [
            'headers' => [],
            'cookies' => $cookies,
            'query' => [
                'appid'=> $appid,
            ],
            'http_errors' => false   #支持错误输出
        ];

        $content = null;
        $error_times = 0;
        while (true) {
            try {
                $response = $client->request('GET', 'https://wsad.weixin.qq.com/official/getitemcategory', $array);
                $content = $response->getBody()->getContents();
                break;
            } catch (ConnectException $e) {
                $error_times++;
                sleep(5);
                echo('connection time out .... ' . $error_times);
            } catch (RequestException $e) {
                $error_times++;
                sleep(5);
                echo('request time out .... ' . $error_times);
            } catch (Exception $e) {
                $error_times++;
                sleep(5);
                echo('unknow exception .... ' . $error_times);
            }
        }
    }
}
