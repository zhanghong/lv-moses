import request from '@/utils/request';

export function getDistricts() {
  return request({
    url: '/areas/districts',
    method: 'get',
  });
}
