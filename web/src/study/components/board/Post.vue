<template>
  <div class="c-board-post" :style="{height: height}">
    <div class="box flex-col">
      <div class="head flex-row">
        <div v-if="post" class="flex-row">
          <span>{{postWays[post.way]}}</span>
          <!--<i v-show="post.way" class="icon-yike icon-triangle-r"></i>-->
          <span>&nbsp;</span>
          <span v-show="post.way">{{post.name}}</span>
        </div>
        <div v-else>新留言</div>
        <i class="btn icon-yike icon-unfold" @click="changeArea('full')" v-show="area !== 'full'"></i>
        <i class="btn icon-yike icon-fold" @click="changeArea('fold')" v-show="area !== 'fold'"></i>
      </div>
      <div class="body flex-col">
        <div class="text flex-col">
          <textarea v-model="text" autofocus placeholder="写下你的留言···" @blur="onBlur"></textarea>
        </div>
        <div class="images flex-row" v-if="image.length">
          <div class="image-item" v-for="(key,idx) in image" :key="idx">
            <img :src="images[key]"/>
            <i class="icon-yike icon-close" @click="removeImage(idx)"></i>
          </div>
        </div>
      </div>
      <div class="foot flex-row">
        <div class="ctrl">
          <div class="btn ctrl-image icon-yike icon-photo" v-show="image.length<3">
            <input id="inputImage" type="file" accept="image/*" @change="chooseImage"/>
          </div>
        </div>
        <div class="btn submit font-medium" @click="release">提交</div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'board-post',
    props: ['sn', 'type', 'post'],
    data() {
      return {
        area: 'fold',
        text: '',
        file: '',
        audio: '',
        image: [
          // 'board/comment/5bf646f191299'
        ],
        images: {
          'board/comment/5bf646f191299': 'http://storage.sandbox.yike.fm/board/comment/5bf646f191299'
        },
        postWays: {
          'reply': '回复',
          'tipoff': '举报',
          undefined: '新留言'
        }
      }
    },
    created() {
    },
    watch: {
      post: function(n, o) {
        if (n && !o) { // 自动聚焦
          setTimeout(() => {
            this.$el.querySelector('textarea').focus()
          }, 100)
        }
      }
    },
    computed: {
      height() {
        switch (this.area) {
          case 'fold':
            return '3rem'
          case 'full':
            return '100vh'
          default:
            return 'auto'
        }
      }
    },
    methods: {
      focus() {
        if (this.app.os() !== 'iOS') { // 非iOS自动聚焦，iOS下会触发软键盘上浮错误
          this.$el.querySelector('textarea').focus()
        }
      },
      onBlur() {
        // iOS软键盘【完成】动作可触发blur事件，借此调整屏幕滚动，可解决输入区漂移问题
        window.scrollTo({top: document.body.scrollHeight})
      },
      release() {
        if (this.post) {
          switch (this.post.way) {
            case 'tipoff':
              this.postTipoff() // 举报
              break;
            case 'reply':
              if (this.checkEmpty()) {
                this.postReply() // 回复
              }
              break;
            default:
              if (this.checkEmpty()) {
                this.postComment() // 发新
              }
              break;
          }
        }
      },
      postComment() {
        this.api.post('/api/board-comment', {
          sn: this.sn,
          type: this.type,
          text: this.text,
          file: this.file,
          audio: this.audio,
          image: this.image
        }).then((res) => {
          this.$emit('release', res.data)
          this.reset()
        }, (e) => {
          alert(e.message)
        })
      },
      postReply() {
        this.api.post('/api/board-reply', {
          cursor: this.post.cursor,
          text: this.text,
          file: this.file,
          audio: this.audio,
          image: this.image
        }).then((res) => {
          this.$emit('release', res.data, this.post.cursor)
          this.reset()
        }, (e) => {
          alert(e.message)
        })
      },
      postTipoff() {
        this.api.post('/api/board-tipoff', {
          cursor: this.post.cursor,
          text: this.text,
          file: this.file,
          audio: this.audio,
          image: this.image
        }).then(() => {
          // todo 增加举报成功的闪窗
          this.$emit('release', null)
          this.reset()
        }, (e) => {
          alert(e.message)
        })
      },
      checkEmpty() {
        let isEmpty = this.text === '' && this.file === '' && this.audio === '' && this.image.length === 0
        if (isEmpty) {
          alert('内容不能为空')
        }
        return !isEmpty
      },
      reset() {
        this.text = ''
        this.file = ''
        this.audio = ''
        this.image = []
      },
      changeArea(size) {
        this.area = size
        this.focus()
      },
      chooseImage(e) {
        this.api.get('/api/board-draft').then((res) => {
          let qiniu = res.data
          let file = e.target.files[0]
          let form = new FormData()
          form.append('key', qiniu.key)
          form.append('token', qiniu.token)
          form.append('file', file, qiniu.key)
          let option = {}
          this.axios.post(qiniu.upload, form, option).then((r) => {
            console.log(r)
            this.image.push(qiniu.key)
            this.images[qiniu.key] = qiniu.url
          })
        })
      },
      removeImage(i) {
        this.image.splice(i, 1)
      }
    }
  }
</script>

<style scoped>
  .c-board-post {
    background: #fff;
    width: 100%;
    transition: height 0.5s;
    min-height: 2rem;
  }
  .head {
    padding: .2rem .3rem;
    font-size: .3rem;
    color: #808080;
    justify-content: space-between;
  }
  .head i {
    font-size: .3rem;
  }
  .box {
    align-items: stretch;
    height: 100%;
  }
  .body {
    align-items: stretch;
    flex-grow: 1;
  }
  .text {
    padding: .1rem .3rem;
    flex-grow: 1;
  }
  .text > textarea {
    outline: none;
    resize: none;
    width: 100%;
    height: 100%;
    border: none;
    flex-grow: 1;
    font-size: .3rem;
    font-family: sans-serif;
  }
  .text > textarea::-webkit-input-placeholder {
    color: #999;
  }
  .foot {
    padding: 0.1rem 0 .2rem 0;
    justify-content: space-between;
  }
  .ctrl .icon-yike {
    position: relative;
    font-size: .4rem;
    color: #808080;
    padding: 0.1rem 0.3rem;
  }
  .ctrl-image > input {
    position: absolute;
    left: 0;
    opacity: 0;
    width: .3rem;
    height: .3rem;
    overflow: hidden;
    padding: 0.1rem 0.3rem;
    border: 1px solid #f00;
  }
  .images {
    padding: .1rem .3rem;
    justify-content: flex-start;
  }
  .image-item {
    margin-right: .1rem;
    position: relative;
  }
  .image-item .icon-close {
    position: absolute;
    right: 0;
    top: 0;
    opacity: 0.7;
  }
  .image-item .icon-yike {
    font-size: .3rem;
    color: #666;
  }
  .images img {
    width: 1rem;
    height: 1rem;
  }
  .btn.submit {
    color: #2F57DA;
    font-size: .3rem;
    padding: 0.1rem 0.3rem;
  }
  .head .icon-triangle-r {
    color: #2F57DA;
    font-size: .12rem;
    padding: 0 .1rem;
  }
</style>
