<template>
  <div class="c-create-draft">
    <div class="list">
      <div class="section" v-for="(seqno, idx) in list" :key="seqno" :id="`seqno-${seqno}`">
        <!--分割线，点击插入新段落-->
        <div class="btn pointer flex-row" v-show="!editing || seqno!==pointer" @click="insert(seqno)">
          <span class="insert-line"></span>
          <i class="icon-yike icon-pen"></i>
          <span class="insert-line"></span>
        </div>
        <!--插入段落的预览-->
        <div class="editing" v-if="editing && seqno===pointer">
          <div class="section-content">
            <c-section :content="dict.editing" v-if="editing && dict.editing"></c-section>
          </div>
        </div>
        <!--段落控制栏-->
        <div class="control flex-row">
          <div class="ctrl-left flex-row">
            <span class="ctrl-seqno font-bold">No.{{idx+1}}</span>
            <span class="ctrl-label free btn" v-if="dict[seqno].free" @click="freeset(seqno)">免费</span>
            <span class="ctrl-label paid btn" v-else @click="freeset(seqno)">付费</span>
          </div>
          <div class="ctrl-right flex-row">
            <i @click="modify(seqno)" class="btn icon-yike icon-post"></i>
            <i @click="remove(seqno)" class="btn icon-yike icon-delete"></i>
          </div>
        </div>
        <!--段落内容(可转换为编辑预览状态)-->
        <div class="section-content" :class="{editing: editing && seqno===cursor && 0}">
          <c-section :content="(editing && seqno === cursor && 0) ? dict.editing : dict[seqno]"></c-section>
        </div>
      </div>
      <!--新增段落的预览-->
      <div class="editing" v-if="editing && !cursor && !pointer">
        <div class="section-content">
          <c-section :content="dict.editing" v-if="dict.editing"></c-section>
        </div>
      </div>
      <div id="pending" class="btn flex-row" @click="pending" v-show="!editing || orientation==='landscape'">
        <!--<div>{{cursor}}|{{pointer}}</div>-->
        <!--<i class="icon-yike icon-post"></i>-->
        <div>＋添加新段落</div>
      </div>
      <div id="guide">
        <ul>
          <li>分段落编辑内容，可独立设置免费试读部分</li>
          <li>支持文字、图片、语音、视频</li>
          <li>文本内容支持<span class="btn" @click="showMarkdownGuide=true">markdown</span></li>
          <li>视频大小不超过500M</li>
        </ul>
      </div>

      <div class="census" v-if="!editing">
        免费部分 <strong>{{census.text[1]}}</strong> 字，付费部分 <strong>{{census.text[0]}}</strong> 字
      </div>

      <div class="foot-bar flex-row" v-if="0" v-show="!editing || !dict.editing || (dict.editing && !dict.editing.text && !dict.editing.src)">
        <div class="btn backward flex-row" @click="$router.back()">≡</div>
        <div class="btn preview flex-item" @click="$emit('preview')">预览</div>
        <div class="btn release flex-item" @click="$emit('release')">提交</div>
      </div>
    </div>

    <div id="frm-editor">
      <transition name="fade">
        <div v-show="editing" class="gap" @click="stopEditing" @touchmove.prevent></div>
      </transition>
      <transition name="slide">
        <create-editor v-if="editing" id="editor"  ref="editor" :sn="sn" :pointer="pointer" :cursor="cursor" :number="number" :content="dict.editing" v-on:update="onUpdate" v-on:editing="onEditing"></create-editor>
      </transition>
    </div>

    <modal-dialog v-if="dialog" width="5rem" :dialog="dialog" v-on:close="dialog=null"></modal-dialog>

    <popup id="markdown-guide" :is-open="showMarkdownGuide" v-on:close="showMarkdownGuide=false">
      <div slot="head">Markdown语法简介</div>
      <markdown-guide></markdown-guide>
      <div slot="foot" class="btn btn-close flex-row" @click="showMarkdownGuide=false">知道了</div>
    </popup>
  </div>
</template>

<script>
  // import CreateEditor from "./Editor"
  import SwitchCapsule from "../../components/unit/SwitchCapsule";
  import MarkdownGuide from "./MarkdownGuide";

  const ModalDialog = r => require.ensure([], () => r(require('../../components/modal/Dialog')), 'common')
  const SwitchSpan = r => require.ensure([], () => r(require("../../components/unit/SwitchSpan")), 'common');
  const Popup = r => require.ensure([], () => r(require("../../components/Popup")), 'common');
  const CSection = r => require.ensure([], () => r(require("../../components/section/index")), 'section');
  const CreateEditor = r => require.ensure([], () => r(require('./Editor')), 'editor')
  export default {
    name: 'create-draft',
    components: {MarkdownGuide, SwitchCapsule, SwitchSpan, ModalDialog, CSection, Popup, CreateEditor},
    props: ['sn'],
    data() {
      return {
        editing: false, // 编辑状态
        pointer: null, // 插入点
        cursor: null, // 当前编辑
        list: [], // 内容列表，
        dict: {}, // 内容字典
        orientation: 'portrait', // portrait | landscape
        showMarkdownGuide: false,
        dialog: null
      }
    },
    created() {
      this.api.get('/api/prepare-slice', {
        sn: this.sn,
        limit: -1
      }).then( (res) => {
        for (let item of res.data) {
          this.list.push(item.cursor)
          this.dict[item.cursor] = item.content
        }
      })
      if (window.screen.width > window.screen.height) {
        this.orientation = 'landscape'
        // this.pending()
      }
    },
    methods: {
      pending() { // 在末尾添加
        this.cursor = null
        this.pointer = null
        this.editing = true
      },
      insert(seqno) { // 在原有段落间插入新的
        if (this.cursor) { // 清理修改暂存内容
          this.dict.editing = null
          setTimeout(() => { // 延迟清理，否则会因监听到onEdit事件而写入内容
            this.dict.editing = null
          }, 100)
        }
        this.cursor = null
        this.pointer = seqno
        this.editing = true
        // this.scrollTo(seqno)
      },
      modify(seqno) { // 修改内容
        console.log('modify', seqno, this.cursor, this.dict.editing)
        if (this.dict.editing && !this.cursor) { // 有新编辑的未保存内容
          this.dialog = {
            info: {
              head: '内容覆盖',
              body: '您有编辑内容未保存'
            },
            btn: {
              prime: '继续',
              vice: '取消'
            },
            call: {
              prime: () => {
                this.$set(this.dict, 'editing', this.dict[seqno])
                // this.dict.editing = this.dict[seqno]
                this.cursor = seqno
                this.editing = true
                this.scrollTo(seqno)
              }
            }
          }
        } else {
          if (seqno !== this.cursor) { // 更换编辑段落时，切换内容
            // this.dict.editing = this.dict[seqno]
            this.$set(this.dict, 'editing', this.dict[seqno])
          }
          this.cursor = seqno
          this.editing = true
          this.pointer = null
          this.scrollTo(seqno)
        }
      },
      freeset(seqno) {
        // this.dict[seqno].free = !this.dict[seqno].free
        let content = this.dict[seqno]
        content.free = !content.free
        this.cursor = seqno
        this.api.post('/api/prepare-update', {
          sn: this.sn,
          type: content.type,
          free: content.free,
          note: content.text,
          cursor: this.cursor
        }).then((res) => {
          this.onUpdate(res.data)
        })
      },
      remove(seqno) {
        this.dialog = {
          info: {
            head: '确认删除',
            body: '删除后不可恢复'
          },
          btn: {
            prime: '确认',
            vice: '取消'
          },
          call: {
            prime: () => {
              this.api.post('/api/prepare-delete', {
                sn: this.sn,
                cursor: seqno
              }).then(() => {
                for (let i in this.list) {
                  if (seqno === this.list[i]) {
                    delete this.dict[this.list[i]]
                    this.list.splice(i, 1)
                    break
                  }
                }
              }).catch( (err) => {
                alert(err.message)
              })
            }
          }
        }
      },
      onUpdate(res) {
        console.log('on update', res)
        // this.dict[res.cursor] = res.content
        this.$set(this.dict, res.cursor, res.content)
        this.dict.editing = null
        if (res.cursor === this.cursor) { // 编辑更新后关闭输入框
          if (this.orientation === 'landscape') {
            this.insert(this.list[this.list.indexOf(res.cursor)+1])
          } else {
            this.editing = false
            this.cursor = null
          }
        } else { // 将新段落加入列表
          // this.editing = false
          for (let i in  this.list) {
            if (res.cursor < this.list[i]) {
              this.list.splice(i, 0, res.cursor)
              res.joined = true
              break
            }
          }
          if (!res.joined) {
            this.list.push(res.cursor)
          }
          this.scrollTo(res.cursor)
        }
        this.$forceUpdate()
        console.log(this.census)
      },
      onEditing(content) { // 监听Editor的内容变化，实时预览
        console.log('onEdting', content)
        this.$set(this.dict, 'editing', content)
        // this.dict.editing = content
        let editing = document.querySelector('.editing')
        let editor = document.querySelector('.editor')
        let domList = document.querySelector('.list')
        if (editing) {
          console.log(editing.offsetTop, editing.offsetHeight, document.body.clientHeight, domList.clientHeight, domList.scrollTop)
          if (editing.offsetTop + editing.offsetHeight > document.body.clientHeight + domList.scrollTop - 100) {
            window.scrollTo({
              top: editing.offsetTop - (document.body.clientHeight - editor.offsetHeight - editing.offsetHeight)+10,
              behavior: "smooth"
            })
            /*
            domList.scrollTo({
              top: editing.offsetTop - 20,
              behavior: "smooth"
            })
            */
          }
          // 预览区和编辑区滚动同步
          editing.scrollTop =  editing.scrollHeight * content.scroll
        }
      },
      stopEditing() {
        this.editing = false
        if (this.cursor && this.dict.editing===this.dict[this.cursor]) {
          this.dict.editing = null
        }
      },
      scrollTo(seqno) {
        setTimeout(() => {
          // location.hash= `#section-${seqno}`
          // 改为scroll
          let editor = document.querySelector('.editor')
          let section = document.getElementById(`seqno-${seqno}`)
          // 横屏全屏时，滚动到底线；其余情况滚动到editor上边沿
          let fix = (this.orientation==='landscape' && editor.classList.contains('area-full')) ? 0 : 1
          let offset = section.offsetTop+section.offsetHeight+(editor.clientHeight*fix)-document.body.clientHeight
          window.scrollTo({top: offset})
        }, 100)
      }
    },
    computed: {
      number() {
        let t = this.cursor || this.pointer
        if (t) {
          return this.list.indexOf(t)+1
        } else {
          return this.list.length+1
        }
      },
      census() {
        let census = { // [付费，免费]
          'text': [0, 0],
          'image': [0, 0],
          'audio': [0, 0],
          'video': [0, 0],
          'cursor': this.cursor // let census watch change
        }
        for (let i of this.list) {
          let item = this.dict[i]
          let free = item.free ? 1 : 0
          census.text[free] += item.length || 0
          census.image[free] += item.type === 'image'
          census.audio[free] += (item.type==='audio') * (item.duration || 0)
          census.video[free] += (item.type==='video') * (item.duration || 0)
        }
        return census
      }
    },
    filters: {
      duration(seconds) {
        return Math.floor(seconds/60) + "'" + seconds%60
      }
    }
  }
</script>

<style scoped>
  .c-create-draft {
    /*background: #ccf;*/
  }
  .c-popup {
    background: rgba(0,0,0,0.1) !important;
  }

  .list {
    /*padding: 1em;*/
  }
  .control {
    justify-content: space-between;
    padding: .3rem .3rem .1rem .3rem;
    background: #fff;
    font-size: .34rem;
    /*border-bottom: 1px solid #ccc;*/
  }
  .ctrl-seqno {
    color: #2F57DA;
    font-size: .34rem;
  }
  .ctrl-label {
    font-size: .22rem;
    color: #fff;
    border-radius: .08rem;
    margin-left: 1em;
    padding: .1em .5em;
  }
  .ctrl-label.free {
    background: #8EC44F;
  }
  .ctrl-label.paid {
    background: #F79757;
  }
  .ctrl-right {
    color: #999;
  }
  .control .icon-yike {
    font-size: .4rem;
    padding: 0.5em;
  }
  .section-content {
    padding: .1rem .3rem .3rem .3rem;
    background: #fff;
    color: #222;
  }
  .pointer {
    height: 2em;
    background: #fff;
  }
  .pointer > .icon-pen {
    color: #666;
    padding: 0 0.5em;
  }
  .insert-line {
    background: #ccc;
    flex-grow: 1;
    height: 1px;
  }
  .blink {
    animation: blink 2s infinite;
  }
  .btn-free {
    border: 1px solid #2F57DA;
    padding: 0 .2em;
  }
  .editing > .section-content {
    display: none;
  }
  .editing {
    opacity: 0;
    animation: glow 700ms ease-out infinite alternate;
    overflow-y: scroll;
    /*height: 50vh;*/
    /*position: fixed;*/
    /*top: 0;*/
    /*width: 100%;*/
    max-height: 45vh;
    margin: 2vh 0;
    /*min-height: 2rem;*/
  }
  .section-content.editing {
    margin: 0;
  }
  #pending {
    margin: .3rem;
    border: 1px dashed #888;
    border-radius: .13rem;
    text-align: center;
    height: 1.3rem;
    font-size: .24rem;
    color: #ccc;
  }
  #pending > .icon-yike {
    font-size: .3rem;
    margin-right: .1rem;
  }
  .icon-yike.icon-add::before {
    content: '+';
    font-size: .36rem;
  }
  #guide {
    /*margin-bottom: 2rem;*/
    font-size: .24rem;
    color: #888;
    min-height: 50vh;
    padding: 0 .3rem;
  }
  #guide ul{
    padding: 0 .3rem;
  }
  #guide .btn {
    color: #2F57DA;
  }
  #frm-editor .gap{
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    position: fixed;
    background: rgba(0,0,0, 0.15)
  }
  #editor {
    position: fixed;
    bottom: 0;
    background: #ddd;
    width: 7.5rem;
    z-index: 10;
  }
  .foot-bar {
    position: fixed;
    bottom: 0;
    background: #fff;
    text-align: center;
    font-size: .3rem;
    color: #2F57DA;
    box-shadow: 0 0 5px #ccc;
    height: 1rem;
    width: 100%;
  }
  .foot-bar .flex-item{
    height: 1rem;
    line-height: 1rem;
  }
  .backward {
    padding: 0 1em;
    height: 100%;
    border-right: 1px solid #ccc;
  }
  .census {
    position: fixed;
    bottom: 0;
    width: 7rem;
    background: #eee;
    color: #333;
    padding: .25rem;
  }

  .c-markdown-guide{
    max-height: 70vh;
    overflow-y: scroll;
  }

  #markdown-guide .btn-close {
    background: #2F57DA;
    color: #fff;
    width: 100%;
    height: 100%;
    font-size: .3rem;
  }

  .slide-enter-active, .slide-leave-active {
    transition: all .5s;
  }

  .slide-enter, .slide-leave-to {
    transform: translateY(100%);
  }

  .fade-enter-active, .fade-leave-active {
    transition: opacity .5s;
  }
  .fade-enter, .fade-leave-to {
    opacity: 0;
  }

  @keyframes blink {
    from {
      opacity: 1.0;
    }
    50% {
      opacity: 0.1;
    }
    to {
      opacity: 1.0;
    }
  }
  @keyframes glow {
    0% {
      border: 1px solid #ccc;
    }
    100% {
      border: 1px solid #2F57DA;
    }
  }

</style>
