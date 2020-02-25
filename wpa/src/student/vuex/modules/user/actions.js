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

export const fetchEnrollList = ({commit}, query) => {
  const url = `${_prefix}/lesson-history.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return commit('FETCH_ENROLL_LIST', json.data)
      }
      return Promise.reject(new Error('Fetch_Enroll_List failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

export const fetchAdvise = ({commit}, body) => {
  const url = `${_prefix}/feedback-submit.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve()
      }
      return Promise.reject(new Error('Fetch_Advise failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 账户余额
export const fetchMoneyBalance = ({commit}, query) => {
  const url = `${_prefix}/pay/money-balance.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return commit('FETCH_MONEY_BALANCE', json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 账单条目
export const fetchMoneyBill = ({commit}, query) => {
  const url = `${_prefix}/pay/money-bill.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        /*commit('FETCH_MONEY_BILL', json.data)*/
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 余额条目
export const fetchMoneyDebit = ({commit}, query) => {
  const url = `${_prefix}/pay/money-debit.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        /*commit('FETCH_MONEY_DEBIT', json.data)*/
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 可提现的余额
export const fetchMoneyOverview = ({commit}, query) => {
  const url = `${_prefix}/pay/money-overview.api`;

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

export const fetchMoneyDrawCash = ({commit}, body) => {
  const url = `${_prefix}/pay/money-drawcash.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      //
      if (json.error == 0) {
        return Promise.resolve(json.data)
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

// 讲师关注列表
export const fetchFollowList = ({commit}, query) => {
  const url = `${_prefix}/follow-list.api`;

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
