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

export const fetchBoardInit = ({commit}, query) => {
  const url = `${_prefix}/board-init.api`;

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

export const fetchBoardSlice = ({commit}, query) => {
  const url = `${_prefix}/board-slice.api`;

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

export const fetchBoardFocus = ({commit}, query) => {
  const url = `${_prefix}/board-focus.api`;

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

// 留言
export const fetchBoardComment = ({commit}, body) => {
  const url = `${_prefix}/board-comment.api`;
  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(json);
    })
    .catch((error) => {
      return Promise.reject(error);
    })
};

// 回复留言
export const fetchBoardReply = ({commit}, body) => {
  const url = `${_prefix}/board-reply.api`;
  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(json);
    })
    .catch((error) => {
      return Promise.reject(error);
    })
};

// 获取七牛权限
export const fetchBoardDraft = ({commit}, query) => {
  const url = `${_prefix}/board-draft.api`;

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

// 点赞
export const fetchBoardLike = ({commit}, body) => {
  const url = `${_prefix}/board-like.api`;
  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(json);
    })
    .catch((error) => {
      return Promise.reject(error);
    })
};

// 获取引用记录
export const fetchBoardRefer = ({commit}, query) => {
  const url = `${_prefix}/board-refer.api`;

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

// 获取对话记录
export const fetchBoardChain = ({commit}, query) => {
  const url = `${_prefix}/board-chain.api`;

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

// 获取对话记录
export const fetchBoardAssoc = ({commit}, query) => {
  const url = `${_prefix}/board-assoc.api`;

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

// 删除留言
export const fetchBoardDelete = ({commit}, body) => {
  const url = `${_prefix}/board-delete.api`;
  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(json);
    })
    .catch((error) => {
      return Promise.reject(error);
    })
};

// 举报留言
export const fetchBoardReport = ({commit}, body) => {
  const url = `${_prefix}/board-tipoff.api`;
  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(json);
    })
    .catch((error) => {
      return Promise.reject(error);
    })
};
