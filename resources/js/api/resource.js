import request from '@/utils/request';

/**
 * Simple RESTful resource class
 */
class Resource {
  constructor(uri) {
    this.uri = uri;
  }
  list(query) {
    return request({
      url: '/' + this.uri,
      method: 'get',
      params: query,
    });
  }
  select(query) {
    return request({
      url: '/' + this.uri + '/list',
      method: 'get',
      params: query,
    });
  }
  get(id) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'get',
    });
  }
  store(resource) {
    return request({
      url: '/' + this.uri,
      method: 'post',
      data: resource,
    });
  }
  update(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'put',
      data: resource,
    });
  }
  destroy(id) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'delete',
    });
  }
  unique(name, value, id = undefined) {
    const data = {
      name: name,
      value: value,
    };

    if (id !== undefined) {
      data['id'] = id;
    }

    return request({
      url: '/' + this.uri + '/unique',
      method: 'post',
      data: data,
    });
  }
}

export { Resource as default };
