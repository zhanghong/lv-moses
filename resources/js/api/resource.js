import request from '@/utils/request';

/**
 * Simple RESTful resource class
 */
class Resource {
  constructor(uri) {
    this.uri = '/' + uri;
  }
  list(query) {
    return this.request('get', null, null, query);
  }
  select(query) {
    return this.request('get', 'list', null, query);
  }
  get(id) {
    return this.request('get', null, id);
  }
  store(resource) {
    return this.request('post', null, null, resource);
  }
  update(id, resource) {
    return this.request('put', null, id, resource);
  }
  destroy(id) {
    return this.request('delete', null, id);
  }
  unique(name, value, id = undefined) {
    const data = {
      name: name,
      value: value,
    };

    if (id !== undefined) {
      data['id'] = id;
    }

    return this.request('post', 'unique', null, data);
  }
  form() {
    return this.request('post', 'form');
  }
  isPrsent(value) {
    if (value === undefined) {
      return false;
    } else if (value === null) {
      return false;
    } else if (value === '') {
      return false;
    }
    return true;
  }
  request(method, name, id = null, data = {}) {
    let url = this.uri;

    if (this.isPrsent(id)) {
      url += '/' + id;
    }

    if (this.isPrsent(name)) {
      url += '/' + name;
    }

    return request({
      url: url,
      method: method,
      data: data,
    });
  }
}

export { Resource as default };
