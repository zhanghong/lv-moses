import request from '@/utils/request';

export function updateShop(data) {
  return request({
    url: '/shop/base/index',
    method: 'patch',
    data,
  });
}

export function getShop() {
  return request({
    url: '/shop/base/index',
    method: 'get',
  });
}

export function checkNameUnique(name) {
  const data = {
    name: 'name',
    value: name,
  };

  return request({
    url: '/shop/base/index/unique',
    method: 'post',
    data,
  });
}
