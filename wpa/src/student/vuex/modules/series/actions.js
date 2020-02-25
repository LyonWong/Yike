import vue from 'vue';

/**
 * get请求
 * @param  {String} options.url   api地址
 * @param  {String} options.query query参数
 * @return {Promise}               Promise
 */
const _prefix = process.env.NODE_ENV == 'production' ? process.env.STUDENT_HOST.replace(/\/$/,'') : '/api';

const _get = ({ url, query }, commit) => {
  if (commit) commit('START_LOADING');
  let _url;
  if (query) {
    // 是否是对象
    if(Object.prototype.toString.call(query) == '[object Object]'){
      let temp = '';
      for(let q in query){
        temp = `${(temp?temp+'&':temp)}${q}=${query[q]}`;
      }
      //
      query = temp;
    }
    _url = `${url}?${query}`
  } else {
    _url = `${url}`
  }

  return vue.http.get(_url)
    .then((res) => {
      if (commit) commit('FINISH_LOADING');
      if (res.status >= 200 && res.status < 300) {
        return res.data
      }
      return Promise.reject(new Error(res.status))
    })
};

const _post = ({ url, body }, commit) => {
  if (commit) commit('START_LOADING');
  let _url = url;

  return vue.http.post(_url, body, {emulateJSON:true})
    .then((res) => {
      if (commit) commit('FINISH_LOADING');
      if (res.status >= 200 && res.status < 300) {
        return res.data
      }
      return Promise.reject(new Error(res.status))
    })
};

export const fetchSeriesList = ({commit}, query) => {
  const url = `${_prefix}/lesson-list.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        commit('FETCH_SERIES_LIST', json.data)
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

export const fetchSeriesDetail = ({commit}, query) => {
  const url = `${_prefix}/series-detail.api`;

  // 开始请求
  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        commit('FETCH_SERIES_DETAIL', json.data);
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

export const fetchSeriesTeacher = ({commit}, query) => {
  const url = `${_prefix}/teacher-info.api`;

  // 开始请求
  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

export const fetchTeacherSingle = ({commit}, query) => {
  const url = `${_prefix}/teacher-singleLesson.api`;

  // 开始请求
  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

export const fetchTeacherSeries = ({commit}, query) => {
  const url = `${_prefix}/teacher-seriesLesson.api`;

  // 开始请求
  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 获得订单详情
export const fetchSeriesOrder = ({commit}, body) => {
  const url = `${_prefix}/series-order.api`;

  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        let opt = {};
        opt[body.series_sn] = json.data;
        commit('FETCH_SERIES_ORDER', opt)
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 获得订单详情
export const fetchSeriesCheckOrder = ({commit}, body) => {
  const url = `${_prefix}/series-check.api`;

  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 订单确认
export const fetchSeriesPurchase = ({commit}, body) => {
  const url = `${_prefix}/series-purchase.api`;

  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};
