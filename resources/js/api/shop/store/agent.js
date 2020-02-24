import request from '@/utils/request';

export function checkNameUnique(id, name) {
  const data = {
    id: id,
    name: 'name',
    value: name,
  };

  return request({
    url: '/shop/store/agents/unique',
    method: 'post',
    data,
  });
}
