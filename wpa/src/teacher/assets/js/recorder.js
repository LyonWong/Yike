import swal from 'sweetalert';

/*-----*/
window.AudioContext = window.AudioContext || window.webkitAudioContext;

var audioContext = new AudioContext();
var audioInput = null,
  realAudioInput = null,
  inputPoint = null,
  audioRecorder = null,
  analyserNode = null,
  zeroGain = null,
  recIndex = 0,
  ctx = null;

// 定义录制时间
var startTimer = 0;
// 定义显示时间
//var showTimer = 0;
var timeMinute = 0;
var timeSecond = 0;
// 开始监测状态
var starting = false;

/* TODO:

 - offer mono option
 - "Monitor input" switch
 */
function gotBuffers( buffers ) {
  // the ONLY time gotBuffers is called is right after a new recording is completed -
  // so here's where we should set up the download.
  // audioRecorder.exportWAV( doneEncoding );
  audioRecorder.exportMP3( doneEncoding );
  // audioRecorder.exportMonoWAV( gotMp3Buffer );
}

function gotMp3Buffer( wav ) {
  // gotMp3Buffer
  audioRecorder.exportMP3( doneEncoding, wav );
}

function doneEncoding( blob ) {
  console.log(blob);
  var url = (window.URL || window.webkitURL).createObjectURL(blob);
  var link = document.getElementById("save");
  link.setAttribute('src', url);
  // 关闭worker线程
  // audioRecorder.terminate();
  // 关闭语音环境
  // audioContext.close();
  //link.href = url;
  //link.download = 'myRecording' + ((recIndex<10)?'0':'') + recIndex + '.wav' || 'output.wav';
  recIndex++;
  // 开始上传
  // 获取七牛token
  ctx.$store.commit('UPDATE_AUDIO_COMPLETE', false);
  ctx.$store.dispatch('fetchPrepareDraft', {lesson_sn:ctx.lesson_sn}).then((data) => {
    // 音频的上传
    postAudio(data, blob);
  }, (err) => {
    // 异常
    swal({
      title: '错误提醒',
      text: err.message,
      confirmButtonText: "知道了"
    });
    ctx.$store.commit('UPDATE_RECORDING', false);
    ctx.$store.commit('UPDATE_BLOB_RECORDING', null);
  });
}

function postAudio(data, blob) {
  //创建formData对象
  var formData = new FormData();
  formData.append('file', blob);
  formData.append('key', data.key);
  formData.append('token', data.token);
  // 开始上传音频
  ctx.$http.post(data.upload, formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  }).then((json) => {
    if (json.ok) {
      // 更新录音资源
      ctx.$store.commit('UPDATE_BLOB_RECORDING', blob);
      // 音频的上传
      ctx.$store.commit('UPDATE_AUDIO_COMPLETE', {lesson_sn:ctx.lesson_sn,content:data.key});
      return ctx = null;
    }
    new Error('Fetch_Post_Audio failure')
  }).catch((error) => {
    swal({
      title: '错误提醒',
      text: '上传音频失败!',
      confirmButtonText: "知道了"
    },()=>{
      ctx.$store.commit('UPDATE_RECORDING', false);
      ctx.$store.commit('UPDATE_BLOB_RECORDING', null);
      return ctx = null;
    });
  });
}

function gotStream(stream) {
  inputPoint = audioContext.createGain();

  // Create an AudioNode from the stream.
  realAudioInput = audioContext.createMediaStreamSource(stream);
  audioInput = realAudioInput;
  audioInput.connect(inputPoint);

  analyserNode = audioContext.createAnalyser();
  analyserNode.fftSize = 2048;
  inputPoint.connect( analyserNode );

  let cfg=process.env.NODE_ENV=='production'?{workerPath:`/assets/static/teacher/_static/teacher/js/recorderjs/recorderWorker.min.js`}:{};

  audioRecorder = new Recorder( inputPoint, cfg );

  zeroGain = audioContext.createGain();
  zeroGain.gain.value = 0.0;
  inputPoint.connect( zeroGain );
  zeroGain.connect( audioContext.destination );
}

function caculateTime() {
  try {
    //
    let diff = Math.ceil((new Date().getTime() - startTimer)/1000);
    //
    timeMinute = parseInt(diff/60);
    timeSecond = diff%60;
    // 分类
    // diff = diff + timeMinute*5 + parseInt(timeSecond/12);
    // caculate again
    let _timeMinute = parseInt(diff/60);
    let _timeSecond = Math.ceil(diff%60);
    //
    let seconds = _timeSecond < 10 ? `0${_timeSecond}` : _timeSecond;
    // 更新
    ctx.$store.commit('UPDATE_RECORDER_TIMER', `${_timeMinute}:${seconds}`);
  }catch(e){}
}

function inspectRecording(self) {
  if(!starting)return;
  // 计时器
  caculateTime();
  // 取消发送
  if(self.cancleRecord){
    // stop recording
    self.active = false;
    self.$store.commit('UPDATE_RECORDING', false);
    self.$store.commit('UPDATE_CANCLE_RECORD', false);
    // 更新录音资源
    self.$store.commit('UPDATE_BLOB_RECORDING', null);
    self = null;
    starting = false;
    return audioRecorder.stop();
  }
  // 时间大于2分44秒
  if(timeMinute >= 2 && timeSecond >= 46){
    // stop recording
    self.$store.commit('UPDATE_RECORDER_STATUS', true);
    self.active = false;
    starting = false;
    audioRecorder.stop();
    return audioRecorder.getBuffers( gotBuffers );
  }
  // 重新监测
  setTimeout(()=>{
    inspectRecording(self);
  }, 1000);
}

export const toggleRecording = function( self ) {
  //
  if(!ctx)ctx = self;
  //
  if (ctx.active) {
    // stop recording
    ctx.active = false;
    audioRecorder.stop();
    // 结束监测
    starting = false;
    // 录制时间是否过短
    if(new Date().getTime() - startTimer < 3000){
      // 清空
      ctx.$store.commit('UPDATE_RECORDING', false);
      ctx = null;
      /*alert('录制时间过短!');*/
      swal({
        title: '错误提醒',
        text: '录制时间过短',
        confirmButtonText: "知道了"
      });
    }else{
      audioRecorder.getBuffers( gotBuffers );
    }
  } else {
    // start recording
    if (!audioRecorder){
      // 启动录音环境
      return initRecorder();
    }

    if(audioContext && !audioContext.destination){
      // 启动录音环境
      initRecorder();
      // tips
      return swal({
        title: '',
        text: '录音环境重启中',
        confirmButtonText: "知道了"
      });
    }
    // 计算开始时间
    startTimer = new Date().getTime();
    //showTimer = audioRecorder.context.currentTime;
    //
    timeMinute = 0;
    timeSecond = 0;
    ctx.active = true;
    ctx.$store.commit('UPDATE_RECORDING', true);
    audioRecorder.clear();
    audioRecorder.record();
    // 开始监测
    starting = true;
    inspectRecording(ctx);
  }
};

export const initRecorder = function() {
  if (!navigator.getUserMedia)
    navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
  if (!navigator.cancelAnimationFrame)
    navigator.cancelAnimationFrame = navigator.webkitCancelAnimationFrame || navigator.mozCancelAnimationFrame;
  if (!navigator.requestAnimationFrame)
    navigator.requestAnimationFrame = navigator.webkitRequestAnimationFrame || navigator.mozRequestAnimationFrame;

  navigator.getUserMedia(
    {
      //'audio': true,
      "audio": {
        "mandatory": {
          "googEchoCancellation": "false",
          "googAutoGainControl": "false",
          "googNoiseSuppression": "false",
          "googHighpassFilter": "false"
        },
        "optional": []
      },
  }, gotStream, function(e) {
      console.log('您的浏览器暂时不支持录音!');
      console.log(e);
    });
};

// window.addEventListener('load', initRecorder );
