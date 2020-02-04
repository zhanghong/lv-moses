import request from '@/utils/request';

/**
 * 获取所有省/市/区数据
 * @Author   zhanghong(Laifuzi)
 * @DateTime 2020-02-04
 */
export function getDistricts() {
  return request({
    url: '/areas/districts',
    method: 'get',
  });
}

/**
 * 获取区县下的街道和乡镇数据
 * @Author   zhanghong(Laifuzi)
 * @DateTime 2020-02-04
 * @param    int                district_id 区县ID
 */
export function getStreets(district_id) {
  return request({
    url: '/areas/streets?pid=' + district_id,
    method: 'get',
  });
}
