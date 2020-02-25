/*License (MIT)

Copyright © 2013 Matt Diamond

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and
to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of
the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.
*/

(function(window){

  var WORKER_PATH = 'static/_static/live/js/recorderjs/recorderWorker.js';

  var Recorder = function(source, cfg){
    var config = cfg || {};
    var bufferLen = config.bufferLen || 4096;
    var workerPath;
    // 取得工作路径
    if(config.workerPath){
      workerPath = config.workerPath + '?t=' + Date.parse(new Date());
    }else{
      workerPath = WORKER_PATH;
    }
    this.context = source.context;
    if(!this.context.createScriptProcessor){
      // 第二个参数输入通道，第三个输出通道
      this.node = this.context.createJavaScriptNode(bufferLen, 2, 2);
    } else {
      this.node = this.context.createScriptProcessor(bufferLen, 2, 2);
    }

    var worker = new Worker(workerPath);
    //
    worker.postMessage({
      command: 'init',
      config: {
        sampleRate: this.context.sampleRate,
        // sampleRate: 44100,
        numChannels: 1,
        path: config.workerPath ? config.workerPath.replace(/(.*)\/[\w\.]+$/, '/$1/lame.min.js?t='+Date.parse(new Date())) : '/static/_static/live/js/recorderjs/lame.js',
      },
    });
    var recording = false,
      currCallback;

    this.node.onaudioprocess = function(e){
      if (!recording) return;
      worker.postMessage({
        command: 'record',
        buffer: [
          e.inputBuffer.getChannelData(0),
          // e.inputBuffer.getChannelData(1)
        ]
      });
    }

    this.configure = function(cfg){
      for (var prop in cfg){
        if (cfg.hasOwnProperty(prop)){
          config[prop] = cfg[prop];
        }
      }
    }

    this.record = function(){
      recording = true;
    }

    this.stop = function(){
      recording = false;
    }

    this.clear = function(){
      worker.postMessage({ command: 'clear' });
    }

    this.terminate = function () {
      worker.terminate();
    }

    this.getBuffers = function(cb) {
      currCallback = cb || config.callback;
      worker.postMessage({ command: 'getBuffers' })
    }

    this.exportWAV = function(cb, type){
      currCallback = cb || config.callback;
      type = type || config.type || 'audio/wav';
      if (!currCallback) throw new Error('Callback not set');
      worker.postMessage({
        command: 'exportWAV',
        type: type
      });
    }

    this.exportMonoWAV = function(cb, type){
      currCallback = cb || config.callback;
      type = type || config.type || 'audio/wav';
      if (!currCallback) throw new Error('Callback not set');
      worker.postMessage({
        command: 'exportMonoWAV',
        type: type
      });
    }

    this.exportMP3 = function (cb) {
      currCallback = cb || config.callback;
      //
      if (!currCallback) throw new Error('Callback not set');
      worker.postMessage({
        command: 'exportMP3',
        type: 'audio/mp3',
      });
    }

    this.Uint8ArrayToFloat32Array = function(u8a) {
      var f32Buffer = new Float32Array(u8a.length);
      for (var i = 0; i < u8a.length; i++) {
        var value = u8a[i << 1] + (u8a[(i << 1) + 1] << 8);
        if (value >= 0x8000) value |= ~0x7FFF;
        f32Buffer[i] = value / 0x8000;
      }
      return f32Buffer;
    }

    this.parseWav = function(wav) {
      //
      var readInt = function(i, bytes) {
        var ret = 0,
          shft = 0;

        while (bytes) {
          ret += wav[i] << shft;
          shft += 8;
          i++;
          bytes--;
        }
        return ret;
      };
      if (readInt(20, 2) != 1) throw 'Invalid compression code, not PCM';
      if (readInt(22, 2) != 1) throw 'Invalid number of channels, not 1';
      return {
        sampleRate: readInt(24, 4),
        bitsPerSample: readInt(34, 2),
        samples: wav.subarray(44)
      };
    }

    worker.onmessage = function(e){
      var blob = e.data;
      currCallback(blob);
    }

    source.connect(this.node);
    this.node.connect(this.context.destination);   // if the script node is not connected to an output the "onaudioprocess" event is not triggered in chrome.
  };

  Recorder.setupDownload = function(blob, filename){
    var url = (window.URL || window.webkitURL).createObjectURL(blob);
    var link = document.getElementById("save");
    link.href = url;
    link.download = filename || 'output.wav';
  }

  window.Recorder = Recorder;

})(window);
