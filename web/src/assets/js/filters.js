/**
 * Author: LyonWong
 * Date: 2018-11-19
 */

const filter = {
  round: (number, precision) => {
    return Math.round(number * 10**precision)/10**precision
  },
  countdown: (seconds) => {
    let h = Math.floor(seconds / 3600)
    let m = Math.floor((seconds % 3600) / 60)
    let s = seconds % 60
    if (h<10) {
      h = '0' + h
    }
    if (m<10) {
      m = '0' + m
    }
    if (s<10) {
      s = '0' + s
    }
    return `${h}:${m}:${s}`
  },
  transTime(seconds) {
    let m = Math.floor(seconds / 60)
    let s = Math.floor(seconds % 60)
    if (m < 10) {
      m = '0' + m
    }
    if (s < 10) {
      s = '0' + s
    }
    return `${m}:${s}`
  },
  durationTime: (seconds) => {
    if (seconds < 100) {
      return seconds + '秒'
    } else if (seconds < 6000) {
      return Math.round(seconds/60) + '分钟'
    } else {
      return Math.round(seconds/3600) + '小时'
    }
  },
  legibleTime: (timeString) => {
    let D = new Date(timeString.replace(/-/g, '/'))
    let delay = ((new Date()).getTime() - D.getTime()) / 1000
    let slices = [
      [0, '刚刚'],
      [60, '分钟'],
      [3600, '小时'],
      [86400, '天'],
      // [86400 * 7, '周'],
      // [86400 * 30, '月'],
      // [86400 * 365, '年'],
      [Infinity, 'Infinify']
    ]
    let suffix = (delay > 0) ? '前' : '后'
    delay = Math.abs(delay)
    if (delay > 86400*7) { // 超过7天直接显示日期
      return D.getFullYear() + '-' + filter.padLeft(D.getMonth()+1, '0', 2) + '-' + filter.padLeft(D.getDate(), '0', 2)
    }
    for (let i in slices) {
      if (delay < slices[i][0]) {
        let _slice = slices[i - 1]
        return (_slice[0] === 0) ? _slice[1] : (Math.floor(delay / _slice[0]) + _slice[1] + suffix)
      }
    }
  },
  padLeft: (input, char, length) => {
    input = String(input)
    if (input.length < length) {
      return char.repeat(length - input.length) + input
    } else {
      return input
    }
  }
}

export default filter
