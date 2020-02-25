/* recorder.js 录制音频并输出为MP3格式
* https://github.com/devin87/mp3-recorder
* author:devin87@qq.com
* update:2015/12/30 08:58
*/
// (function (window, undefined) {
(function (window, undefine) {
  "use strict";

  // 浏览器兼容
  window.AudioContext = window.AudioContext || window.webkitAudioContext;
  navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;

  // MP3音频录制
  function MP3Recorder(ops) {
    ops = ops || {};

    var self = this;
    self.bufferSize = ops.bufferSize || 16384;
    self.numChannels = ops.numChannels || 1;
    self.bitRate = ops.bitRate || 128;

    self.ops = ops;

    // 录音支持检测
    self.support = !!(window.AudioContext && navigator.getUserMedia && window.Worker);

    self.init();
  }

  MP3Recorder.prototype = {
    constructor: MP3Recorder,

    // 初始化
    init: function () {
      var self = this;
      if (!self.support) return;

      var ops = self.ops
      var onComplete = ops.complete
      var onError = ops.error

      var context = new AudioContext()
      var worker = new Worker(ops.WORKER_PATH || 'js/recorder-worker.js');

      worker.onmessage = function (e) {
        var obj = e.data
        var data = obj.data;

        switch (obj.cmd) {
          case "complete":
            if (onComplete) onComplete(data, "audio/mp3");
            break;

          case "error":
            if (onError) onError(data);
            break;
        }
      };

      self.inputSampleRate = context.sampleRate;
      self.outputSampleRate = ops.sampleRate || self.inputSampleRate;

      worker.postMessage({
        cmd: "init",
        data: {
          numChannels: self.numChannels,
          sampleBits: self.sampleBits,
          inputSampleRate: self.inputSampleRate,
          outputSampleRate: self.outputSampleRate,
          bitRate: self.bitRate
        }
      });

      self.context = context;
      self.worker = worker;
    },

    // 开始录音
    start: function (success, error) {
      var self = this;

      navigator.getUserMedia({audio: true}, function (stream) {
        var context = self.context
        var  worker = self.worker

        var numChannels = self.numChannels

        var source = context.createMediaStreamSource(stream)
        var processor = (context.createScriptProcessor || context.createJavaScriptNode).call(context, self.bufferSize, numChannels, numChannels);

        processor.onaudioprocess = function (e) {
          if (self._paused) return;

          var data = []
          var i = 0;
          for (; i < numChannels; i++) {
            data.push(e.inputBuffer.getChannelData(i));
          }

          worker.postMessage({cmd: "encode", data: data});
        };

        source.connect(processor);
        processor.connect(context.destination);

        self.source = source;
        self.processor = processor;

        if (typeof success === "function") success();
      }, error);
    },
    // 暂停录音
    pause: function () {
      this._paused = true;
    },
    // 恢复录音
    resume: function () {
      this._paused = false;
    },
    // 停止录音
    stop: function () {
      var self = this;
      if (self.source) self.source.disconnect();
      if (self.processor) self.processor.disconnect();
      if (self.worker) self.worker.postMessage({cmd: "stop"});
    }
  };
  window.MP3Recorder = MP3Recorder;
})(window)
