import qs from 'qs';

/* 对象转json*/
export function toJson(data){
  var json = qs.stringify(data);
  return json;
}

/* 判断是否为空*/
export function isEmpty(str){
  if (str === '' || str === null || str === undefined) {
    return true;
  }

  return false;
}
