import vue from 'vue';
/**
 * acitons
 */
const _prefix = process.env.NODE_ENV == 'production' ? process.env.LIVE_HOST.replace(/\/$/,'') : '/api';

const _get = ({ url, query }, commit) => {
  //if (commit) commit('UPDATE_LOADING', true);
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
      //if (commit) commit('UPDATE_LOADING', false);
      if (res.status >= 200 && res.status < 300) {
        return res.data
      }
      return Promise.reject(new Error(res.status))
    })
};

const _post = ({ url, body }, commit) => {
  let _url = url;

  return vue.http.post(_url, body, {emulateJSON:true,withCredentials:true})
    .then((res) => {
      if (res.status >= 200 && res.status < 300) {
        return res.data
      }
      return Promise.reject(new Error(res.status))
    })
};

/*获取历史记录*/
export const fetchHistory = ({commit}, query) => {
  const url = `${_prefix}/live-slice-tim.api`;

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

/*退款*/
export const fetchRefund = ({commit}, body) => {
  const url = `${_prefix}/lesson-refund-freely.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve();
      }
      return Promise.reject(json.error);
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*进入课后交流*/
export const fetchStartComment = ({commit}, body) => {
  const url = `${_prefix}/lesson-repose.api`;
  // 开始请求
  commit('UPDATE_LOADING', true);
  return _post({ url, body }, commit)
    .then((json) => {
      commit('UPDATE_LOADING', false);
      if (json.error == 0) {
        return Promise.resolve();
      }
      return Promise.reject(json.error);
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*结束授课*/
export const fetchEndLesson = ({commit}, body) => {
  const url = `${_prefix}/lesson-finish.api`;
  // 开始请求
  commit('UPDATE_LOADING', true);
  return _post({ url, body }, commit)
    .then((json) => {
      commit('UPDATE_LOADING', false);
      if (json.error == 0) {
        return Promise.resolve();
      }
      return Promise.reject(json.error);
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*开始评价*/
export const fetchEvaluate = ({commit}, body) => {
  const url = `${_prefix}/lesson-rating.api`;
  // 开始请求
  commit('UPDATE_LOADING', true);
  return _post({ url, body }, commit)
    .then((json) => {
      commit('UPDATE_LOADING', false);
      if (json.error == 0) {
        return Promise.resolve();
      }
      return Promise.reject(json.error);
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*分享直播配置*/
export const fetchWXConfig = ({commit}, query) => {
  const url = `${_prefix}/weixin-jsConfig.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(new Error('Fetch_WX_Config failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*获得七牛云token*/
export const fetchQiniuToken = ({commit}, query) => {
  const url = `${_prefix}/live-audio_draft.api`;

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


/*轮询获取压缩地址*/
export const fetchAudioCheck = ({commit}, query) => {
  const url = `${_prefix}/live-audio_check.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0 || json.error == 1 || json.error == 2) {
        return Promise.resolve(json);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*引用用户*/
export const fetchQuote = ({commit}, body) => {
  const url = `${_prefix}/live-quote-text.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*禁言用户*/
export const fetchLiveForbid = ({commit}, body) => {
  const url = `${_prefix}/live-forbid_speak.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*发送备课*/
export const fetchLivePrepareSend = ({commit}, body) => {
  const url = `${_prefix}/live-prepare-send.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json);
      }
      return Promise.reject(json)
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*邀请嘉宾*/
export const fetchInviteGuest = ({commit}, body) => {
  const url = `${_prefix}/live-invite.api`;

  return _post({ url, body }, commit)
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

/*是否有尾注*/
export const fetchExistsTail = ({commit}, query) => {
  const url = `${_prefix}/live-conf.api`;

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
