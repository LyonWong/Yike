(function () {
  "use strict";

  function log() {
    if (typeof console !== "undefined") console.log.apply(console, arguments);
  }

  function postMessage(cmd, data) {
    self.postMessage({
      cmd: cmd,
      data: data
    });
  }

  log("mp3 recorder worker started!");

  importScripts('./lame.all.js');

  var dataBuffer = [];
  var mp3Encoder;
  var numChannels;
  var inputSampleRate;
  var outputSampleRate;

  function appendBuffer(buffer) {
    dataBuffer.push(new Int8Array(buffer));
  }

  function clearBuffer() {
    dataBuffer = [];
  }

  function init(data) {
    numChannels = data.numChannels || 1;
    inputSampleRate = data.inputSampleRate;
    outputSampleRate = Math.min(data.outputSampleRate || inputSampleRate, inputSampleRate);

    clearBuffer();

    var lame = new Lamejs();
    mp3Encoder = new lame.Mp3Encoder(numChannels, outputSampleRate, data.bitRate || 128);
  }

  function convertBuffer(buffer) {
    var input;
    var i = 0;

    if (inputSampleRate !== outputSampleRate) {
      var compression = inputSampleRate / outputSampleRate;
      var length = Math.ceil(buffer.length / compression);
      var index = 0;
      input = new Float32Array(length);
      for (i=0; index < length; i += compression) {
        input[index++] = buffer[~~i];
      }
    } else {
      input = new Float32Array(buffer);
    }

    length = input.length;
    var output = new Int16Array(length);

    for (i=0; i < length; i++) {
      var s = Math.max(-1, Math.min(1, input[i]));

      output[i] = s < 0 ? s * 0x8000 : s * 0x7FFF;
    }

    return output;
  }

  function encode(data) {
    var samplesLeft = convertBuffer(data[0]);
    var samplesRight = numChannels > 1 ? convertBuffer(data[1]) : undefined;

    var maxSamples = 1152;
    var length = samplesLeft.length;
    var remaining = length;
    var i = 0;

    for (; remaining >= maxSamples; i += maxSamples) {
      var left = samplesLeft.subarray(i, i + maxSamples);
      var right = samplesRight ? samplesRight.subarray(i, i + maxSamples) : undefined;
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
    var obj = e.data;
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
})();
