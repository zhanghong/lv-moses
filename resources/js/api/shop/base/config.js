import request from '@/utils/request';

export function updateShop(shop_id, data) {
  // console.log(data);
  return request({
    url: '/shops/' + shop_id + '/base/index',
    method: 'patch',
    data,
  });
}

export function getShop(shop_id) {
  return request({
    url: '/shops/' + shop_id + '/base/index',
    method: 'get',
  });
}

export function checkNameUnique(shop_id, name) {
  const data = {
    id: shop_id,
    name: 'name',
    value: name,
  };

  return request({
    url: '/shops/base/index/unique',
    method: 'post',
    data,
  });
}
