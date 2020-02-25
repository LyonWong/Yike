import vue from 'vue'

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

export const fetchUserInfo = ({commit}, query) => {
  const url = `${_prefix}/user-profile.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
        //return commit('FETCH_USER_INFO', json.data);
      }
      return Promise.reject(new Error('FETCH_USER_INFO failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};
// 获得统计信息
export const fetchStatsOverview = ({commit}, query) => {
  const url = `${_prefix}/stats-overview.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
        //return commit('FETCH_USER_INFO', json.data);
      }
      return Promise.reject(new Error('FETCH_USER_INFO failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};
