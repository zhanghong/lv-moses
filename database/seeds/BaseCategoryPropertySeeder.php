<?php

use Illuminate\Database\Seeder;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ConnectException;

use App\Models\Base\Category;
use App\Models\Base\CategoryProperty;
use App\Models\Base\CategorySelector;
use App\Models\Base\CategoryPropertySelector;

class BaseCategoryPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::where('level', 3)->orderBy('id', 'ASC')->each(function($category) {
            echo(' catetory id: '.$category->id);
            // $file_name = 'files/wx_category_property.json';
            // $content = file_get_contents(base_path($file_name));
            $content = $this->getWechatCategoryContent($category);
            $this->saveContentData($category, $content);
            sleep(1);
        });

        $this->bindPropertySelector();
    }

    /**
     * 抓去分类属性和属性值数据
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-03
     * @param    Category           $category 分类实例
     * @return   string
     */
    private function getWechatCategoryContent($category)
    {
        $appid = 'wx362ccbae95e206c5';
        $jar = new CookieJar;
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
                'category_id' => $category->outer_key,
            ],
            'http_errors' => false   #支持错误输出
        ];

        $content = null;
        while (true) {
            try {
                $response = $client->request('GET', 'https://wsad.weixin.qq.com/official/getpropertybycategoryid', $array);
                $content = $response->getBody()->getContents();
                break;
            } catch (ConnectException $e) {
                // dd($e->getMessages());
                $error_times++;
                sleep(5);
                echo('connection time out .... ' . $error_times);
            } catch (RequestException $e) {
                // dd($e->getMessages());
                $error_times++;
                sleep(5);
                echo('request time out .... ' . $error_times);
            } catch (Exception $e) {
                // dd($e->getMessages());
                $error_times++;
                sleep(5);
                echo('unknow exception .... ' . $error_times);
            }
        }
        return $content;
    }

    /**
     * 保存分类属性信息
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-03
     * @param    Category           $category 分类实例
     * @param    string             $content  Json字符串
     * @return   null
     */
    private function saveContentData($category, $content)
    {
        if (is_array($content)) {
            $json = $content;
        } else {
            $json = json_decode($content, true);
        }
        // json['data'] 是二维数组
        $data = $json['data'] ?? [[], []] ;

        $properties = $data[0]['field_list'] ?? [] ;
        foreach ($properties as $key => $item) {
            $property = CategoryProperty::firstOrNew([
                'outer_name' => CategoryProperty::OUTER_FROM_WECHAT,
                'outer_key' => strval($item['id']),
            ]);

            $property->name = $item['property_name'];
            $property->type = $item['property_type'];
            $property->choice = $item['property_choice'];
            $property->outer_selector_ids = $item['property_selector_id_list'];
            $property->category_id = $category->id;
            $property->outer_cid = $category->outer_key;
            $property->save();
        }

        $selectors = $data[1]['id_list'] ?? [] ;
        foreach ($selectors as $key => $item) {
            $selector = CategorySelector::firstOrNew([
                'outer_name' => CategorySelector::OUTER_FROM_WECHAT,
                'outer_key' => strval($item['id']),
            ]);
            $selector->name = $item['name'];
            $selector->save();
        }
        return ;
    }

    /**
     * 保存属性和属性值关联信息
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-02-03
     * @return   null
     */
    private function bindPropertySelector()
    {
        CategoryProperty::all()->each(function ($property) {
            echo(' property id: '.$property->id);
            if (empty($property->outer_selector_ids)) {
                $outer_selector_ids = [];
            } else {
                $outer_selector_ids = explode(',', $property->outer_selector_ids);
            }

            $selector_ids = [];
            if (!empty($outer_selector_ids)){
                $field_str = implode(',', $outer_selector_ids);
                $selector_ids = CategorySelector::whereIn('outer_key', $outer_selector_ids)
                                        ->orderByRaw(DB::raw("FIELD(outer_key, $field_str)"))
                                        ->get()
                                        ->map(function($selector){
                                            return $selector->id;
                                        })->toArray();
            }

            foreach ($selector_ids as $key => $sid) {
                $item = CategoryPropertySelector::firstOrNew([
                    'property_id' => $property->id,
                    'selector_id' => $sid,
                ]);
                $item->order = $key + 1;
                $item->save();
            }

            $delete_query = CategoryPropertySelector::where('property_id', $property->id);
            if ($selector_ids) {
                $delete_query->whereNotIn('id', $selector_ids);
                $property->value_ids = implode(',', $selector_ids);
            } else {
                $property->value_ids = '';
            }

            $delete_query->delete();
            $property->save();
        });
        return ;
    }
}
