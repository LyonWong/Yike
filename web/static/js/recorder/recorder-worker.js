/* recorder-worker.js 录制音频并输出为MP3格式
* https://github.com/devin87/mp3-recorder
* author:devin87@qq.com
* update:2015/12/30 08:58
*/

// (function (undefined) {
(function () {
  "use strict";

  // 日志输出
  function log() {
    if (typeof console !== "undefined") console.log.apply(console, arguments);
  }

  // 发送 worker 数据
  function postMessage(cmd, data) {
    self.postMessage({
      cmd: cmd,
      data: data
    });
  }

  log("mp3 recorder worker started!");

  // 导入lame.js以实现mp3编码
  // Worker 内可以使用 importScripts 导入js
  importScripts('./lame.all.js');
  // var Lamejs = require('./lame.all')

  var dataBuffer = []     // 数据缓冲区
  var mp3Encoder          // mp3编码器
  var numChannels         // 通道数
  // var //sampleBits,          // 采样位数
  var inputSampleRate     // 输入采样率
  var outputSampleRate    // 输出采样率

  // 添加缓冲数据
  function appendBuffer(buffer) {
    dataBuffer.push(new Int8Array(buffer));
  }

  // 清除缓冲数据
  function clearBuffer() {
    dataBuffer = [];
  }

  // 初始化
  function init(data) {
    numChannels = data.numChannels || 1;
    inputSampleRate = data.inputSampleRate;
    outputSampleRate = Math.min(data.outputSampleRate || inputSampleRate, inputSampleRate);

    clearBuffer();

    var lame = new lamejs();
    mp3Encoder = new lame.Mp3Encoder(numChannels, outputSampleRate, data.bitRate || 128);
  }

  // 数据压缩与转换
  function convertBuffer(buffer) {
    var input;
    var i = 0

    // 修改采样率
    if (inputSampleRate !== outputSampleRate) {
      var compression = inputSampleRate / outputSampleRate
      var length = Math.ceil(buffer.length / compression)
      var index = 0
      input = new Float32Array(length)
      for (i=0; index < length; i += compression) {
        input[index++] = buffer[~~i];
      }
    } else {
      input = new Float32Array(buffer);
    }

    // floatTo16BitPCM
    length = input.length
    var output = new Int16Array(length)

    for (i=0; i < length; i++) {
      var s = Math.max(-1, Math.min(1, input[i]));

      output[i] = s < 0 ? s * 0x8000 : s * 0x7FFF;
    }

    return output;
  }

  // 编码音频数据
  function encode(data) {
    var samplesLeft = convertBuffer(data[0])
    var samplesRight = numChannels > 1 ? convertBuffer(data[1]) : undefined

    var maxSamples = 1152
    var length = samplesLeft.length
    var remaining = length
    var i = 0;

    for (; remaining >= maxSamples; i += maxSamples) {
      var left = samplesLeft.subarray(i, i + maxSamples)
      var right = samplesRight ? samplesRight.subarray(i, i + maxSamples) : undefined
      var mp3buffer = mp3Encoder.encodeBuffer(left, right);

      appendBuffer(mp3buffer);
      remaining -= maxSamples;
    }
  }

  function stop() {
    appendBuffer(mp3Encoder.flush());

    postMessage("complete", dataBuffer);

    clearBuffer();
  }

  self.onmessage = function (e) {
    var obj = e.data
    var data = obj.data;

    switch (obj.cmd) {
      case "init":
        init(data);
        break;

      case "encode":
        encode(data);
        break;

      case "stop":
        stop();
        break;
    }
  };
})()
