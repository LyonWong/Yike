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

export const fetchCourseList = ({commit}, query) => {
  const url = `${_prefix}/lesson-list.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return commit('FETCH_COURSE_LIST', json.data)
      }
      return Promise.reject(new Error('Fetch_Course_List failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

export const fetchCourseDetail = ({commit}, query) => {
  const url = `${_prefix}/lesson-detail.api`;
  // 加个时间戳
  query['t'] = Date.parse(new Date());
  // 开始请求
  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        let opt = {};
        opt[json.data.sn] = json.data;
        commit('FETCH_COURSE_DETAIL', opt);
        return Promise.resolve(json.data)
      }
      return Promise.reject(new Error('Fetch_Course_List failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

export const fetchLessonAccess = ({commit}, query) => {
  const url = `${_prefix}/lesson-access.api`;

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

export const fetchLessonEnroll = ({commit}, body) => {
  const url = `${_prefix}/lesson-enroll.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        let opt = {};
        opt[body.lesson_sn] = json.data;
        commit('FETCH_LESSON_ENROLL', opt);
        return Promise.resolve(json.data);
      }
      return Promise.reject(json);
    })
    .catch((error) => {
      return Promise.reject(error);
    })
};

export const fetchOrderConfirm = ({commit}, body) => {
  const url = `${_prefix}/pay/order-confirm.api`;

  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        let opt = {};
        opt[body.lesson_sn] = json.data;
        commit('FETCH_ORDER_CONFIRM', opt);
        return Promise.resolve(json.data);
      }
      return Promise.reject(new Error('Fetch_Order_Confirm failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

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

export const fetchEvaluteList = ({commit}, query) => {
  const url = `${_prefix}/lesson-rating-list.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        // commit('FETCH_EVALUATE_LIST', json.data);
        return Promise.resolve(json.data);
      }
      return Promise.reject(new Error('Fetch_Evaluate_List failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

export const fetchEvaluteTotal = ({commit}, query) => {
  const url = `${_prefix}/lesson-rating-count.api`;

  return _get({ url, query }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(new Error('Fetch_Evaluate_Total failure'))
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

export const fetchRankSlice = ({commit}, query) => {
  const url = `${_prefix}/promote-rank-slice.api`;

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

export const fetchEvaluate = ({commit}, body) => {
  const url = `${_prefix}/lesson-rating.api`;
  // 开始评价
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

/*课程购买*/
export const fetchLessonPurchase = ({commit}, body) => {
  const url = `${_prefix}/lesson-purchase.api`;
  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve();
      }
      return Promise.reject(json);
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

/*讲师关注*/
export const fetchTeacherFollow = ({commit}, body) => {
  const url = `${_prefix}/follow-follow.api`;
  // 开始请求
  return _post({ url, body }, commit)
    .then((json) => {
      if (json.error == 0) {
        return Promise.resolve(json.data);
      }
      return Promise.reject(json);
    })
    .catch((error) => {
      return Promise.reject(error)
    })
};

