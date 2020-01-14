import request from '@/utils/request';

export function updateShop(shop_id, data) {
  console.log(data);
  return request({
    url: '/shops/' + shop_id + '/config',
    method: 'patch',
    data,
  });
}

export function getShop(shop_id) {
  return request({
    url: '/shops/' + shop_id + '/config',
    method: 'get',
  });
}
