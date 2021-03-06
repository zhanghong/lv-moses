<?php

use Illuminate\Database\Seeder;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ConnectException;

use App\Models\Category\Category;
use App\Models\Category\Property;
use App\Models\Category\Selector;
use App\Models\Category\PropertySelector;

class BaseCategoryPropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::where('level', 3)->where('id', '>', 709)->orderBy('id', 'ASC')->each(function($category) {
            echo(' catetory id: '.$category->id);
            // $file_name = 'files/wx_category_property.json';
            // $content = file_get_contents(base_path($file_name));
            $content = $this->getWechatCategoryContent($category);
            $this->saveContentData($category, $content);
            sleep(1);
        });
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
                            'ad_session_searchzone' => 'vi9FeQmIMkAQF0KKkn9vc7gmzmqC5I4c',
                            'appid_searchzone' => $appid,
                            'data_bizuin' => '3001033114',

                            'data_ticket' => 'GNbLDAUqFy2KDa4f6Y5DqikwyX5WGMKaX3YRlZa9VAzDSOrQaaFwd',
                            'mmsearch_flag' => '4B945849F4DDD89A9416007496A0571FC42245D6A8A4AC27C016526B61FA75DE    ',
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

        $selectors = $data[1]['id_list'] ?? [] ;
        foreach ($selectors as $key => $item) {
            if (in_array($item['id'], ['6235387', '6235388', '6235389', '6235390'])) {
                continue;
            }
            $selector = Selector::firstOrNew([
                'outer_name' => Selector::OUTER_FROM_WECHAT,
                'outer_key' => strval($item['id']),
            ]);
            $selector->shop_id = 0;
            $selector->name = $item['name'];
            $selector->save();
        }

        $properties = $data[0]['field_list'] ?? [] ;
        foreach ($properties as $key => $item) {
            if (in_array($item['id'], ['2069615', '2069616'])) {
                continue;
            }
            $property = Property::firstOrNew([
                'outer_name' => Property::OUTER_FROM_WECHAT,
                'outer_key' => strval($item['id']),
            ]);

            $property->name = $item['property_name'];
            switch (intval($item['property_type'])) {
                case 1:
                    $property->type = Property::TYPE_PARAMS;
                    break;
                case 2:
                    $property->type = Property::TYPE_STANDARDS;
                    break;
                default:
                    $property->type = $item['property_type'];
                    break;
            }
            switch (intval($item['property_choice'])) {
                case 1:
                    $property->choice = Property::CHOICE_SELECT;
                    break;
                case 2:
                    $property->choice = Property::CHOICE_CHECKBOX;
                    break;
                default:
                    $property->choice = $item['property_choice'];
                    break;
            }
            $property->outer_selector_ids = $item['property_selector_id_list'];
            $property->outer_cid = $category->outer_key;
            $property->shop_id = 0;
            $property->save();

            if (empty($property->outer_selector_ids)) {
                $outer_selector_ids = [];
            } else {
                $outer_selector_ids = explode(',', $property->outer_selector_ids);
            }

            $selector_ids = [];
            if (!empty($outer_selector_ids)){
                $field_str = implode(',', $outer_selector_ids);
                $selector_ids = Selector::whereIn('outer_key', $outer_selector_ids)
                                        ->orderByRaw(DB::raw("FIELD(outer_key, $field_str)"))
                                        ->get()
                                        ->map(function($selector) {
                                            return $selector->id;
                                        })->toArray();
            }

            if (count($outer_selector_ids) != count($selector_ids)) {
                dd([
                    'pid' => $property->id,
                    'outer' => $outer_selector_ids,
                    'ids' => $selector_ids,
                ]);
            }

            if (!empty($selector_ids)) {
                Selector::whereIn('id', $selector_ids)
                            ->update(['property_id' => $property->id]);
            }

            $property->value_ids = implode(',', $selector_ids);
            $property->save();


            $property->categories()->syncWithoutDetaching([
                $category->id => ['editor_id' => 0, 'order' => $key + 1]
            ]);
        }

        return ;
    }

    // /**
    //  * 保存属性和属性值关联信息
    //  * @Author   zhanghong(Laifuzi)
    //  * @DateTime 2020-02-03
    //  * @return   null
    //  */
    // private function bindPropertySelector()
    // {
    //     Property::all()->each(function ($property) {
    //         echo(' property id: '.$property->id);
    //         if (empty($property->outer_selector_ids)) {
    //             $outer_selector_ids = [];
    //         } else {
    //             $outer_selector_ids = explode(',', $property->outer_selector_ids);
    //         }

    //         $selector_ids = [];
    //         if (!empty($outer_selector_ids)){
    //             $field_str = implode(',', $outer_selector_ids);
    //             $selector_ids = Selector::whereIn('outer_key', $outer_selector_ids)
    //                                     ->orderByRaw(DB::raw("FIELD(outer_key, $field_str)"))
    //                                     ->get()
    //                                     ->map(function($selector) {
    //                                         return $selector->id;
    //                                     })->toArray();
    //         }

    //         if (count($outer_selector_ids) != count($selector_ids)) {
    //             dd([
    //                 'pid' => $property->id,
    //                 'outer' => $outer_selector_ids,
    //                 'ids' => $selector_ids,
    //             ]);
    //         }

    //         if (!empty($selector_ids)) {
    //             Selector::whereIn('id', $selector_ids)
    //                         ->update(['property_id' => $property->id]);
    //         }

    //         foreach ($selector_ids as $key => $sid) {
    //             $item = PropertySelector::firstOrNew([
    //                 'property_id' => $property->id,
    //                 'selector_id' => $sid,
    //             ]);
    //             $item->shop_id = 0;
    //             $item->order = $key + 1;
    //             $item->save();
    //         }

    //         $delete_query = PropertySelector::where('property_id', $property->id);
    //         if ($selector_ids) {
    //             $delete_query->whereNotIn('selector_id', $selector_ids);
    //             $property->value_ids = implode(',', $selector_ids);
    //         } else {
    //             $property->value_ids = '';
    //         }

    //         $delete_query->delete();
    //         $property->save();
    //     });
    //     return ;
    // }
}
