<?php

use Illuminate\Database\Seeder;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use App\Models\Base\Area;

class BaseAreaSeeder extends Seeder
{
    public function run()
    {
        $provices = [
            ['id' => 1, 'name' => '北京'],
            ['id' => 2, 'name' => '上海'],
            ['id' => 3, 'name' => '天津'],
            ['id' => 4, 'name' => '重庆'],
            ['id' => 5, 'name' => '河北'],
            ['id' => 6, 'name' => '山西'],
            ['id' => 7, 'name' => '河南'],
            ['id' => 8, 'name' => '辽宁'],
            ['id' => 9, 'name' => '吉林'],
            ['id' => 10, 'name' => '黑龙江'],
            ['id' => 11, 'name' => '内蒙古'],
            ['id' => 12, 'name' => '江苏'],
            ['id' => 13, 'name' => '山东'],
            ['id' => 14, 'name' => '安徽'],
            ['id' => 15, 'name' => '浙江'],
            ['id' => 16, 'name' => '福建'],
            ['id' => 17, 'name' => '湖北'],
            ['id' => 18, 'name' => '湖南'],
            ['id' => 19, 'name' => '广东'],
            ['id' => 20, 'name' => '广西'],
            ['id' => 21, 'name' => '江西'],
            ['id' => 22, 'name' => '四川'],
            ['id' => 23, 'name' => '海南'],
            ['id' => 24, 'name' => '贵州'],
            ['id' => 25, 'name' => '云南'],
            ['id' => 26, 'name' => '西藏'],
            ['id' => 27, 'name' => '陕西'],
            ['id' => 28, 'name' => '甘肃'],
            ['id' => 29, 'name' => '青海'],
            ['id' => 30, 'name' => '宁夏'],
            ['id' => 31, 'name' => '新疆'],
            ['id' => 52993, 'name' => '港澳'],
            ['id' => 32, 'name' => '台湾'],
            ['id' => 84, 'name' => '钓鱼岛'],
        ];

        foreach ($provices as $key => $node) {
            $node['areaCode'] = '';
            $this->saveWithChildren($node, null);
        }

        return true;
    }

    /**
     * 递归保存区域信息
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @param    array              $node        结点信息
     * @param    Array              $parent_area 父Area实例
     * @return   bool
     */
    private function saveWithChildren($node, $parent_area) {
        if ($parent_area) {
            $parent_id = $parent_area->id;
        } else {
            $parent_id = 0;
        }

        $conditions = [
            'parent_id' => $parent_id,
            'outer_name' => 'jd',
            'outer_key' => $node['id'],
        ];

        $data = [
            'name' => $node['name'],
            'is_enabled' => true,
            'outer_code' => $node['areaCode'],
        ];

        $area = Area::updateOrCreate($conditions, $data);
        $area->refresh();
        if ($area->level < 3) {
            // 只有省、市、区三级才爬取下一层区域
            $children_nodes = $this->getChildren($node);
            if ($area->level > 0) {
                sleep(2);
            }
            foreach ($children_nodes as $key => $child_node) {
                $this->saveWithChildren($child_node, $area);
            }
        }

        return true;
    }

    /**
     * 爬取区域的下级节点
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-17
     * @param    array              $node 当前区域信息
     * @return   array
     */
    private function getChildren($node)
    {
        if (in_array($node['id'], [1, 2, 3, 4]) && strpos($node['name'], '市') === false) {
            $node['name'] = $node['name'] . '市';
            return [$node];
        }

        echo('    id: ' . $node['id'] . ', name: ' . $node['name']);

        $error_times = 0;
        while($error_times < 10) {
            $client = new Client();
            $array = [
                'headers' => [],
                'query' => [
                    'fid'=> $node['id'],
                    '_' => time(),
                ],
                'http_errors' => false   #支持错误输出
            ];

            try {
                $response = $client->request('GET', 'https://fts.jd.com/area/get', $array);
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

        return json_decode($response->getBody()->getContents(), true);
    }
}
