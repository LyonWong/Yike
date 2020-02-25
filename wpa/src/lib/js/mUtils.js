/**
 * 存储localStorage
 */
export const setStore = (name, content) => {
  if (!name) return;
  if (typeof content !== 'string') {
    content = JSON.stringify(content);
  }
  window.localStorage.setItem(name, content);
};

/**
 * 获取localStorage
 */
export const getStore = name => {
  if (!name) return;
  try {
    let value = window.localStorage.getItem(name);
    if (value) {
      return value;
    } else {
      return '';
    }
  } catch (e) {
    return '';
  }
};

/**
 * 删除localStorage
 */
export const removeStore = name => {
  if (!name) return;
  window.localStorage.removeItem(name);
};

/**
 * 获取style样式
 */
export const getStyle = (element, attr, NumberMode = 'int') => {
  let target;
  // scrollTop 获取方式不同，没有它不属于style，而且只有document.body才能用
  if (attr === 'scrollTop') {
    target = element.scrollTop;
  } else if (element.currentStyle) {
    target = element.currentStyle[attr];
  } else {
    target = document.defaultView.getComputedStyle(element, null)[attr];
  }
  //在获取 opactiy 时需要获取小数 parseFloat
  return NumberMode == 'float' ? parseFloat(target) : parseInt(target);
};

/**
 * 页面到达底部，加载更多
 */
export const loadMore = (element, callback) => {
  let windowHeight = window.screen.height;
  let height;
  let setTop;
  let paddingBottom;
  let marginBottom;
  let requestFram;
  let oldScrollTop;

  document.body.addEventListener('scroll', () => {
    loadMore();
  }, false)
  //运动开始时获取元素 高度 和 offseTop, pading, margin
  element.addEventListener('touchstart', () => {
    height = element.offsetHeight;
    setTop = element.offsetTop;
    paddingBottom = getStyle(element, 'paddingBottom');
    marginBottom = getStyle(element, 'marginBottom');
  }, {passive: true})

  //运动过程中保持监听 scrollTop 的值判断是否到达底部
  element.addEventListener('touchmove', () => {
    loadMore();
  }, {passive: true})

  //运动结束时判断是否有惯性运动，惯性运动结束判断是非到达底部
  element.addEventListener('touchend', () => {
    oldScrollTop = document.body.scrollTop;
    moveEnd();
  }, {passive: true})

  const moveEnd = () => {
    requestFram = requestAnimationFrame(() => {
      if (document.body.scrollTop != oldScrollTop) {
        oldScrollTop = document.body.scrollTop;
        loadMore();
        moveEnd();
      } else {
        cancelAnimationFrame(requestFram);
        //为了防止鼠标抬起时已经渲染好数据从而导致重获取数据，应该重新获取dom高度
        height = element.offsetHeight;
        loadMore();
      }
    })
  }

  const loadMore = () => {
    if (document.body.scrollTop + windowHeight >= height + setTop + paddingBottom + marginBottom) {
      callback();
    }
  }
};

/**
 * 显示返回顶部按钮，开始、结束、运动 三个过程中调用函数判断是否达到目标点
 */
export const showBack = callback => {
  let requestFram;
  let oldScrollTop;

  document.addEventListener('scroll', () => {
    showBackFun();
  }, false)
  document.addEventListener('touchstart', () => {
    showBackFun();
  }, {passive: true})

  document.addEventListener('touchmove', () => {
    showBackFun();
  }, {passive: true})

  document.addEventListener('touchend', () => {
    oldScrollTop = document.body.scrollTop;
    moveEnd();
  }, {passive: true})

  const moveEnd = () => {
    requestFram = requestAnimationFrame(() => {
      if (document.body.scrollTop != oldScrollTop) {
        oldScrollTop = document.body.scrollTop;
        moveEnd();
      } else {
        cancelAnimationFrame(requestFram);
      }
      showBackFun();
    })
  }

  //判断是否达到目标点
  const showBackFun = () => {
    if (document.body.scrollTop > 500) {
      callback(true);
    } else {
      callback(false);
    }
  }
};

/**
 * 去除首位空格
 */
export const trimStr = (str) => str.replace(/(^\s*)|(\s*$)/g, '');

/**
 * 根据名字获取hash参数值
 */
export const getQueryString = (name) => {
  var reg = new RegExp('(^|&|\\?)' + name + '=([^&]*)(&|$)', 'i');
  var r = window.location.hash.substr(1).match(reg);
  if (r != null) return unescape(r[2]);
  return null;
};

/**
 * 根据名字获取hash参数值
 */
export const decodeQueryString = (name) => {
  var reg = new RegExp('(^|&|\\?)' + name + '=([^&]*)(&|$)', 'i');
  var r = window.location.search.substr(1).match(reg);
  if (r != null) return decodeURIComponent(r[2]);
  return null;
};

/**
 * 运动效果
 * @param {HTMLElement} element   运动对象，必选
 * @param {JSON}        target    属性：目标值，必选
 * @param {number}      duration  运动时间，可选
 * @param {string}      mode      运动模式，可选
 * @param {function}    callback  可选，回调函数，链式动画
 */
export const animate = (element, target, duration = 400, mode = 'ease-out', callback) => {
  clearInterval(element.timer);

  //判断不同参数的情况
  if (duration instanceof Function) {
    callback = duration;
    duration = 400;
  } else if (duration instanceof String) {
    mode = duration;
    duration = 400;
  }

  //判断不同参数的情况
  if (mode instanceof Function) {
    callback = mode;
    mode = 'ease-out';
  }

  //获取dom样式
  const attrStyle = attr => {
    if (attr === "opacity") {
      return Math.round(getStyle(element, attr, 'float') * 100);
    } else {
      return getStyle(element, attr);
    }
  }
  //根字体大小，需要从此将 rem 改成 px 进行运算
  const rootSize = parseFloat(document.documentElement.style.fontSize);

  const unit = {};
  const initState = {};

  //获取目标属性单位和初始样式值
  Object.keys(target).forEach(attr => {
    if (/[^\d^\.]+/gi.test(target[attr])) {
      unit[attr] = target[attr].match(/[^\d^\.]+/gi)[0] || 'px';
    } else {
      unit[attr] = 'px';
    }
    initState[attr] = attrStyle(attr);
  });

  //去掉传入的后缀单位
  Object.keys(target).forEach(attr => {
    if (unit[attr] == 'rem') {
      target[attr] = Math.ceil(parseInt(target[attr]) * rootSize);
    } else {
      target[attr] = parseInt(target[attr]);
    }
  });


  let flag = true; //假设所有运动到达终点
  const remberSpeed = {};//记录上一个速度值,在ease-in模式下需要用到
  element.timer = setInterval(() => {
    Object.keys(target).forEach(attr => {
      let iSpeed = 0;  //步长
      let status = false; //是否仍需运动
      let iCurrent = attrStyle(attr) || 0; //当前元素属性址
      let speedBase = 0; //目标点需要减去的基础值，三种运动状态的值都不同
      let intervalTime; //将目标值分为多少步执行，数值越大，步长越小，运动时间越长
      switch (mode) {
        case 'ease-out':
          speedBase = iCurrent;
          intervalTime = duration * 5 / 400;
          break;
        case 'linear':
          speedBase = initState[attr];
          intervalTime = duration * 20 / 400;
          break;
        case 'ease-in':
          let oldspeed = remberSpeed[attr] || 0;
          iSpeed = oldspeed + (target[attr] - initState[attr]) / duration;
          remberSpeed[attr] = iSpeed
          break;
        default:
          speedBase = iCurrent;
          intervalTime = duration * 5 / 400;
      }
      if (mode !== 'ease-in') {
        iSpeed = (target[attr] - speedBase) / intervalTime;
        iSpeed = iSpeed > 0 ? Math.ceil(iSpeed) : Math.floor(iSpeed);
      }
      //判断是否达步长之内的误差距离，如果到达说明到达目标点
      switch (mode) {
        case 'ease-out':
          status = iCurrent != target[attr];
          break;
        case 'linear':
          status = Math.abs(Math.abs(iCurrent) - Math.abs(target[attr])) > Math.abs(iSpeed);
          break;
        case 'ease-in':
          status = Math.abs(Math.abs(iCurrent) - Math.abs(target[attr])) > Math.abs(iSpeed);
          break;
        default:
          status = iCurrent != target[attr];
      }

      if (status) {
        flag = false;
        //opacity 和 scrollTop 需要特殊处理
        if (attr === "opacity") {
          element.style.filter = "alpha(opacity:" + (iCurrent + iSpeed) + ")";
          element.style.opacity = (iCurrent + iSpeed) / 100;
        } else if (attr === 'scrollTop') {
          element.scrollTop = iCurrent + iSpeed;
        } else {
          element.style[attr] = iCurrent + iSpeed + 'px';
        }
      } else {
        flag = true;
      }

      if (flag) {
        clearInterval(element.timer);
        if (callback) {
          callback();
        }
      }
    })
  }, 20);
};

//检查文件类型和大小
export const checkPic = (obj, fileSize) => {
  var picExts = 'jpg|jpeg|png|bmp|gif|webp';
  var photoExt = obj.value.substr(obj.value.lastIndexOf(".") + 1).toLowerCase();//获得文件后缀名
  var pos = picExts.indexOf(photoExt);
  if (pos < 0) {
    return swal({
      title: '错误提醒',
      text: '您选中的文件不是图片，请重新选择',
      confirmButtonText: "知道了"
    });
  }
  var fileSize = Math.round(fileSize / 1024 * 100) / 100; //单位为KB
  console.log('filesize(kb)', fileSize);
  /*
  if (fileSize > 5 * 1024) {
      return swal({
          title: '错误提醒',
          text: '您选择的图片大小超过限制(最大为5M)，请重新选择',
          confirmButtonText: "知道了"
      });
  }
  */
  return true;
};

//检查文件类型和大小
export const checkVideo = (obj, fileSize) => {
  var picExts = 'mp4|mov';
  var photoExt = obj.value.substr(obj.value.lastIndexOf(".") + 1).toLowerCase();//获得文件后缀名
  var pos = picExts.indexOf(photoExt);
  if (pos < 0) {
    return swal({
      title: '错误提醒',
      text: '目前只支持mp4,mov格式的视频上传',
      confirmButtonText: "知道了"
    });
  }
  var fileSize = Math.round(fileSize / 1024 * 100) / 100; //单位为KB
  console.log('filesize(kb)', fileSize);

  if (fileSize > 500 * 1024) {
    return swal({
      title: '错误提醒',
      text: '上传视频大小不能超过500M，请重新选择',
      confirmButtonText: "知道了"
    });
  }

  return true;
};

//检查文件类型和大小
export const checkPastePic = (obj, fileSize) => {
  var fileSize = Math.round(fileSize / 1024 * 100) / 100; //单位为KB
  if (fileSize > 5 * 1024) {
    return swal({
      title: '错误提醒',
      text: '您选择的图片大小超过限制(最大为5M)，请重新选择',
      confirmButtonText: "知道了"
    });
  }
  return true;
};

//检查文件类型和大小
export const checkFile = (file, fileSize) => {
  var legalExts = 'jpg|jpeg|png|bmp|gif|webp|txt|doc|docx|xls|ppt|zip|rar|gz';
  var value = file.name;
  var ext = value.substr(value.lastIndexOf(".") + 1).toLowerCase();//获得文件后缀名
  var pos = legalExts.indexOf(ext);
  if (pos < 0) {
    return swal({
      title: '错误提醒',
      text: '您选中的文件类型非法，请重新选择',
      confirmButtonText: "知道了"
    });
  }
  var fileSize = Math.round(fileSize / 1024 * 100) / 100; //单位为KB
  if (fileSize <= 0) {
    return swal({
      title: '错误提醒',
      text: '您选择的文件大小内容为空，请重新选择',
      confirmButtonText: "知道了"
    });
  }
  if (fileSize > 20 * 1024) {
    return swal({
      title: '错误提醒',
      text: '您选择的文件大小超过限制(最大为20M)，请重新选择',
      confirmButtonText: "知道了"
    });
  }
  return true;
};

//检查是否是对象且是否为空
export const checkObject = (obj) => {
  if (Object.prototype.toString.call(obj) == '[object Object]') {
    for (let o in obj) {
      return true;
    }
    ;
    return false;
  }
  return false;
};

//写cookies
export const setCookie = (name, value) => {
  var Days = 30;
  var exp = new Date();
  exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
  document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
};

//读取cookies
export const getCookie = (name) => {
  var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");

  if (arr = document.cookie.match(reg))

    return unescape(arr[2]);
  else
    return null;
};

// 转成数组
export const convertToArray = (arr, data) => {
  if (Array.isArray(data)) {
    return [...arr, ...data];
  } else if (Object.prototype.toString.call(data) == '[object Object]') {
    return [...arr, data];
  }
};

export const strlen = (str) => {
  var len = 0;
  for (var i = 0; i < str.length; i++) {
    var c = str.charCodeAt(i);
    //单字节加1
    if ((c >= 0x0001 && c <= 0x007e) || (0xff60 <= c && c <= 0xff9f)) {
      len++;
    }
    else {
      len += 2;
    }
  }
  return len;
};
