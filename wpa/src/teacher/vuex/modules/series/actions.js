import vue from 'vue';

/**
 * get请求
 * @param  {String} options.url   api地址
 * @param  {String} options.query query参数
 * @return {Promise}               Promise
 */
const _prefix = process.env.NODE_ENV == 'production' ? process.env.TEACHER_HOST.replace(/\/$/,'') : '/api';

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
    },(err)=>{
      if (commit) commit('FINISH_LOADING');
      return Promise.reject(new Error(err))
    })
};

// 获取系列课列表
export const fetchSeriesList = ({commit}, query) => {
  const url = `${_prefix}/series-list.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        //
        commit('UPDATE_SERIES_LIST', json.data);
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 获取系列课单课列表
export const fetchSeriesSingle = ({commit}, query) => {
  const url = `${_prefix}/series-listLesson.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        //
        commit('UPDATE_SERIES_LIST', json.data);
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 获取系列课详情
export const fetchSeriesDetail = ({commit}, query) => {
  const url = `${_prefix}/series-detail.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        //
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 获取讲师邀请链接
export const fetchInviteTeacher = ({commit}, body) => {
  const url = `${_prefix}/series-inviteCreate.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        //
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*获得七牛云token*/
export const fetchSeriesQiniuToken = ({commit}, query) => {
  const url = `${_prefix}/series-draft.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};
/*获得七牛云token 用作编辑器上传图片*/
export const fetchSeriesUrlToken = ({commit}, query) => {
  const url = `${_prefix}/series-content_url.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 获取系列课编辑列表
export const fetchSeriesEdit = ({commit}, query) => {
  const url = `${_prefix}/series-edit.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        //
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 创建系列课
export const fetchSeriesCreate = ({commit}, body) => {
  const url = `${_prefix}/series-create.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        //
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 编辑系列课
export const fetchSeriesModify = ({commit}, body) => {
  const url = `${_prefix}/series-modify.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        //
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 编辑系列课
export const fetchSeriesCheck = ({commit}, body) => {
  const url = `${_prefix}/series-check.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        //
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 分享系列课
export const fetchSeriesShare = ({commit}, query) => {
  const url = `${_prefix}/share-series.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        //
        return Promise.resolve(json.data);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};
