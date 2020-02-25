<template>
  <div class="w-create-prepare flex-col">
    <transition name="slide">
      <div class="banner" v-if="banner">
        <span> 请使用<a href="https://www.google.cn/intl/zh-CN/chrome/" target="_blank">最新版Chrome浏览器</a>以获得最佳体验 </span>
        <i class="icon-yike icon-cross btn" @click="banner=null"></i>
      </div>
    </transition>
    <div class="head flex-row">
      <div class="head-left flex-col">
        <div id="title" class="flex-row">
          <input v-if="profile" v-model="profile.title" @blur="onTitleBlur" placeholder="请输入标题（14字以内）"/>
        </div>
        <div id="census" class="flex-row" v-if="census">
          <div v-if="census.free">
            免费部分
            <span v-if="census.free.text">字数<span class="highlight">{{census.free.text}}</span></span>
            <span v-if="census.free.image">图片<span class="highlight">{{census.free.image}}</span>张</span>
            <span v-if="census.free.audio">音频<span class="highlight">{{census.free.audio | durationTime}}</span></span>
            <span v-if="census.free.video">视频<span class="highlight">{{census.free.video | durationTime}}</span></span>
          </div>
          <div v-if="census.lock">
            付费部分
            <span v-if="census.lock.text">字数<span class="highlight">{{census.lock.text}}</span></span>
            <span v-if="census.lock.image">图片<span class="highlight">{{census.lock.image}}张</span></span>
              <span v-if="census.lock.audio">音频<span class="highlight">{{census.lock.audio | durationTime}}</span></span>
              <span v-if="census.lock.video">视频<span class="highlight">{{census.lock.video | durationTime}}</span></span>
          </div>
        </div>
      </div>
      <div class="head-right flex-col">
        <div id="preview" class="btn btn-prime" @click="preview">预览</div>
      </div>
    </div>
    <div class="body flex-row">
      <div id="list" :class="{'hide-scroll': !listScroll, 'show-scroll': listScroll}">
        <div class="section" v-for="(item, idx) in list" :key="idx" :id="`section-${idx}`" :class="{editing: idx===editing}">
          <!--分割线，点击插入新段落-->
          <div class="btn pointer flex-row" :class="{disabled: isEmpty(idx) || isEmpty(idx-1)}" @click.stop="insert(idx)">
            <div class="pointer-thumb flex-row icon-yike icon-plus">
            </div>
          </div>
          <!--段落控制栏-->
          <div class="control flex-row" @click="modify(idx)">
            <div class="ctrl-left flex-row">
              <span class="ctrl-seqno font-bold">No.{{idx+1}}</span>
              <span class="ctrl-label free btn" v-if="item.content.free" @click="switchFree(idx)">免费</span>
              <span class="ctrl-label paid btn" v-else @click="switchFree(idx)">付费</span>
            </div>
            <div class="ctrl-right flex-row">
              <!-- <i @click="modify(idx)" class="btn icon-yike icon-post"></i> -->
              <i @click.stop="remove(idx)" class="btn icon-yike icon-delete"></i>
            </div>
          </div>
          <div class="section-content" @click.prevent="modify(idx)">
            <div class="c-section image" v-if="item.content.type==='image'">
              <img :src="item.content.src"/>
            </div>
            <div class="c-section video flex-row" v-else-if="item.content.type==='video'">
              <img :src="`${item.content.src}-preview.jpg`"/>
              <i class="icon-yike icon-triangle-r"></i>
            </div>
            <c-section :content="item.content" v-else></c-section>
          </div>
        </div>
        <div class="section">
          <div class="btn pointer flex-row" :class="{disabled: isEmpty(list.length-1)}" @click="insert(list.length)">
            <div class="pointer-thumb flex-row icon-yike icon-plus">
            </div>
          </div>
        </div>
        <div class="tips">
          <ul>
            <li>文本内容支持<a href="/create/guide/markdown" target="_blank">markdown</a></li>
            <li>每段可插入一个多媒体素材</li>
            <li>上传视频不超过500M</li>
          </ul>
        </div>
      </div>
      <div id="edit" class="flex-col" :class="{listScroll: listScroll}">
        <div class="edit-head flex-row">
          <div class="edit-head-left flex-row">
            <div class="edit-number">NO.{{editing+1}}</div>
            <div class="edit-media flex-row" :class="{addon: (list[editing] && list[editing].content.src) || recordHandle}">
              <div class="edit-media-image flex-row">
                <i class="icon-yike icon-photo"></i>
                <span>图片</span>
                <label>
                  <input type="file" accept="image/*"  @change="chooseImage"/>
                </label>
              </div>
              <div class="edit-media-record flex-row" :class="{recording: recordHandle}" @click="handleRecord">
                <i class="icon-yike icon-micphone"></i>
                <span>录音</span>
              </div>
              <div class="edit-media-video flex-row">
                <i class="icon-yike icon-video"></i>
                <span>视频</span>
                <label>
                  <input type="file" accept="video/*" @change="chooseVideo"/>
                </label>
              </div>
              <div class="edit-media-audio flex-row">
                <i class="icon-yike icon-music"></i>
                <span>音频</span>
                <label>
                  <input type="file" accept="audio/*" @change="chooseAudio"/>
                </label>
              </div>
            </div>
          </div>
          <div class="edit-head-right flex-row">
            <span> 免费试读 </span>
            <label v-if="list[editing]" class="switch-check" :class="{checked: list[editing].content.free}">
              <i class="switch-thumb"></i>
              <input type="checkbox" v-model="list[editing].content.free" @change="onSwitchFree">
            </label>
            <!--<switch-check v-if="list[editing]" v-model="list[editing].content.free" size="40px" tone="prime"></switch-check>-->
          </div>
        </div>
        <div class="edit-hint" v-if="editor.hint">{{editor.hint}}</div>
        <div class="edit-body" v-if="editing===null"></div>
        <div class="edit-body flex-col" v-else>
          <div v-show="recordHandle" class="edit-recorder flex-row">
            <div class="btn stop" @click="stopRecord">■</div>
            <img :src="app.linkToAssets('/img/perform/recording.gif')"/>
            <span class="time">{{recording | transTime}}</span>
            <!--<i class="icon-yike icon-cross" @click="removeDraft"></i>-->
          </div>
          <div v-show="uploading!==null" class="edit-uploading flex-row">
            <div>{{uploading === true ? '检测中' : '上传中'}}</div>
            <div class="slider" :style="uploadingStyle"></div>
          </div>
          <div v-if="list[editing].content.type === 'image'" class="edit-image">
            <img :src="list[editing].content.src"/>
            <i class="icon-yike icon-cross" @click="removeDraft"></i>
          </div>
          <div v-if="['audio', 'video'].indexOf(list[editing].content.type)>=0" :class="[`edit-${list[editing].content.type}`]">
            <c-section :content="{type: list[editing].content.type, src: list[editing].content.src}"></c-section>
            <i class="icon-yike icon-cross" @click="removeDraft"></i>
          </div>
          <div class="edit-text flex-row">
            <textarea ref="writer" v-model="list[editing].content.text" @input="onEdit" autofocus @keydown="onKeydown" @paste="onPaste" placeholder="请输入..."></textarea>
          </div>
        </div>
        <div class="edit-foot flex-row">
          <div class="edit-foot-left">
            <div v-if="!isEmpty(editing)">
              <span> 当前字数：{{editingTextLength}} </span>
              <span v-if="editor.caching===false"> | 存草稿失败</span>
              <span v-else-if="editor.caching===0"> | 草稿已保存</span>
              <span v-else-if="editor.caching===true"> | 内容已保存</span>
              <span v-else-if="editor.caching"> | 输入中...</span>
            </div>
            <div v-else>
              <span>当前字数：0</span>
            </div>
          </div>
          <div class="edit-foot-right">
            <div class="btn btn-prime" :class="{disabled: isEmpty(editing)}" @click="commit" title="Ctrl+Enter或Alt+S">完成</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  // import SectionCtrl from "@/components/section/control"
  import Filters from '@/assets/js/filters';
  const CSection = r => require.ensure([], () => r(require("@/components/section/index")), 'section');
  require('@/assets/js/recorder/recorder')
  export default {
    name: 'w-create-prepare',
    components: {CSection},
    props: [],
    data() {
      return {
        sn: this.$route.params.sn || this.$route.query.sn,
        banner: null,
        listScroll: 0,
        checked: true,
        profile: null,
        list: [], // 内容列表，
        editor: { // 编辑器
          caching: null, // 编辑缓存
          census: null,
          hint: null
        },
        census: null,
        editing: null, // 正在编辑
        uploading: null, // 正在上传
        recorder: null, // 录音机
        recording: null, // 录音时间
        recordHandle: null // 录音句柄
      }
    },
    created() {
      document.body.style.maxWidth = '960px'
      let agent = navigator.userAgent.match(/Chrome\/(\d+)/)
      if (!agent || parseInt(agent[1])<74) {
        this.banner = true
      }
      this.api.get('/api/create-profile', {
        sn: this.sn
      }).then((res) => {
        this.profile = res.data
      }, this.api.onErrorSign)
      this.api.get('/api/prepare-slice', {
        sn: this.sn,
        limit: -1
      }).then( (res) => {
        for (let item of res.data) {
          this.list.push(item)
        }
        this.initEdit()
      })
    },
    mounted() {
      this.initRecord()
      document.getElementById('list').addEventListener('scroll', () => {
        if (this.listScroll) {
          clearTimeout(this.listScroll)
        }
        this.listScroll = setTimeout( () => {
          this.listScroll = 0;
        }, 2000)
      })
    },
    methods: {
      insert(idx) {
        if (this.isEmpty(idx) || this.isEmpty(idx-1)) {
          return false
        }
        if (this.list[idx] && !this.list[idx].cursor) {
          this.update(idx, () => { this.insert(idx) })
          return
        }
        this.list.splice(idx, 0, {
          pointer: this.list[idx] ? this.list[idx].cursor : null,
          content: {
            type: 'markdown',
            free: this.list[idx-1] ? this.list[idx-1].content.free : false
          }
        })
        if (idx === this.editing) {
          this.editing++
        }
        setTimeout(() => {
          this.modify(idx)
          // let domList = document.getElementById('list')
          // domList.scrollTop += 80;
        })
      },
      modify(idx) {
        if (idx === this.editing) {
          return
        }
        if (this.editing !== null) {
          if (this.list[this.editing].edited) { // 有过编辑，保存
            this.update(this.editing, () => {
              let item = Object.assign({idx: this.editing}, this.list[this.editing])
              localStorage.setItem(`edit-${this.sn}`, JSON.stringify(item))
            })
          } else if (!this.list[this.editing].cursor) { // 没有编辑，无指针的空段落删除
            this.remove(this.editing)
            if (idx > this.editing) {
              idx--
            }
          } else {
            let item = Object.assign({idx: idx}, this.list[idx])
            localStorage.setItem(`edit-${this.sn}`, JSON.stringify(item))
          }
        }
        this.editing = idx
        this.editor.caching = null
        // this.editor.census = {text: (this.list[idx].content.text || '').replace(/\s/g, '').length}
        // if (!this.editor.census.text && !this.list[idx].content.src) {
        //   this.editor.census = null
        // }
        setTimeout( () => {
          this.$refs.writer.focus()
        })
      },
      update(idx, callback) {
        let section = this.list[idx].content
        let duration = null;
        switch (section.type) {
          case 'audio':
          case 'video':
            duration = document.querySelector(`#edit ${section.type}`).duration
            break;
        }
        this.api.post('/api/prepare-update', {
          sn: this.sn,
          type: section.type || 'markdown',
          note: section.text,
          free: section.free,
          draft: section.draft,
          length: (section.text || '').replace(/\s/g, '').length, // 字数统计
          duration: Math.round(duration), // 音视频市场
          cursor: this.list[idx].cursor,
          pointer: this.list[idx].pointer
        }, {loading: '正在保存　'}).then((res) => {
          this.list[idx] = res.data
          this.flushCensus()
          this.$forceUpdate()
          callback && callback()
        }).catch((e) => {
          if (e.error === '2') {
            this.bus.$emit('dialog', {
              info: {
                head: '含有违规内容',
                body: '<ul><li>' + e.data.join('</li><li>') + '</li></ul>'
              },
              btn: {prime: '知道了'}
            })
            this.modify(idx)
          } else {
            this.bus.$emit('dialog', {
              info: { body: e.message },
              btn: { prime: '知道了' }
            })
          }
        })
      },
      remove(idx) {
        localStorage.removeItem(`edit-${this.sn}`)
        if (this.list[idx].cursor || this.list[idx].edited) {
          this.bus.$emit('dialog', {
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
                if (this.editing === idx) { // 清理缓存
                  localStorage.removeItem(`edit-${this.sn}`)
                }
                if (this.list[idx].cursor) {
                  this.api.post('/api/prepare-delete', {
                    sn: this.sn,
                    cursor: this.list[idx].cursor
                  }).then(() => {
                    this.remove_(idx)
                  }).catch( (err) => {
                    alert(err.message)
                  })
                } else {
                  this.remove_(idx)
                }
              }
            }
          })
        } else {
          this.remove_(idx)
        }
      },
      remove_(idx) {
        if (this.list.length === 1) { // 当列表只有一段时，用重置代替删除，避免出现列表无内容的情况
          this.list = [ { content: {} } ]
        } else {
          this.list.splice(idx, 1)
          if (this.editing >= this.list.length) {
            this.editing = this.list.length-1
          }
        }
        this.flushCensus()
      },
      commit() {
        if (this.isEmpty(this.editing)) { // 无内容，不操作
          return
        }
        if (this.list[this.editing].cursor && this.editing<this.list.length-1) { // 修改已有内容，仅保存
          this.update(this.editing, () => {
            this.editor.caching = true
          })
        } else { // 开启新段落
          this.insert(this.editing+1)
          setTimeout( () => {
            let domList = document.getElementById('list')
            // let domSection = document.getElementById(`section-${this.editing+1}`)
            let domSection = document.querySelector(".section.editing")
            if (domSection.offsetTop+domSection.clientHeight > domList.clientHeight+domList.offsetTop+domList.scrollTop) {
              domList.scrollTop = domSection.offsetTop - domList.clientHeight - domList.offsetTop + domSection.clientHeight
            }
          })
        }
      },
      locate() { // 定位编辑段落
      },
      preview() { // 预览
        if (!this.profile.title.length) {
          this.bus.$emit('dialog', {
            info: {
              body: '请拟定标题'
            }
          })
          return
        }
        if (this.profile.title.length>14) {
          this.bus.$emit('dialog', {
            info: {
              body: '标题不能超过14字'
            }
          })
          return
        }
        let item = this.list[this.editing]
        if (item && !item.content.text && !item.content.src) {
          this.remove(this.editing)
          // window.open(`/create/preview/${this.sn}`, '_blank')
          location.href = `/create/preview/${this.sn}`
        } else {
          this.update(this.editing, () => {
            localStorage.removeItem(`edit-${this.sn}`)
            // window.open(`/create/preview/${this.sn}`, '_blank')
            location.href = `/create/preview/${this.sn}`
          })
        }
      },
      removeDraft() { // 移除多媒体素材
        document.querySelector(`.edit-media-${this.list[this.editing].content.type} input`).value = null
        this.list[this.editing].content = {
          type: 'markdown',
          text: this.list[this.editing].content.text
        }
        this.list[this.editing].illegal = null
        if (this.recordHandle) {
          this.recorder.pause()
        }
        this.$forceUpdate()
        this.onEdit()
      },
      switchFree(idx) {
        this.list[idx].content.free = !this.list[idx].content.free
        this.$forceUpdate()
        this.update(idx)
      },
      onSwitchFree() {
        this.$forceUpdate()
      },
      onEdit() {
        this.list[this.editing].edited = true
        this.list[this.editing].content.length = (this.list[this.editing].content.text || '').length
        let item = Object.assign({idx: this.editing}, this.list[this.editing])
        localStorage.setItem(`edit-${this.sn}`, JSON.stringify(item))
        /* todo setTimeout 会导致中文输入法下内容编辑异常
        if (this.editor.caching) {
          clearTimeout(this.editor.caching)
        }
        this.editor.caching = setTimeout(() => {
          let item_ = JSON.stringify(Object.assign({idx: this.editing}, this.list[this.editing]))
          console.log('timeout', item_, this.editor)
          let _item = localStorage.getItem(`edit-${this.sn}`)
          if (_item === item_) {
            this.editor.caching = 0
          } else {
            this.editor.caching = false
          }
        }, 1000)
        */
        // this.$forceUpdate()
      },
      initEdit() { // 从缓存回复编辑
        let item = JSON.parse(localStorage.getItem(`edit-${this.sn}`))
        if (item && item.idx<=this.list.length) {
          item.edited = null
          if (!item.pointer) { // 修改
            this.list[item.idx] = item
          } else { // 插入
            this.list.splice(item.idx, 0, item)
          }
          this.modify(item.idx)
        } else {
          this.insert(this.list.length)
        }
        setTimeout( () => {
          this.scrollList(this.editing)
          this.editor.census = {text: (this.list[this.editing].content.text || '').replace(/\s/g, '').length}
          this.flushCensus()
          if (!this.editor.census.text && !this.list[this.editing].content.src) {
            this.editor.census = null
          }
          // SectionCtrl.init()
        })
      },
      scrollList(idx) {
        let domList = document.getElementById('list')
        let domSection = document.getElementById(`section-${idx}`)
        domList.scrollTop = domSection.offsetTop - domList.offsetTop
      },
      chooseImage(e) {
        this.uploading = 0
        this.api.get('/api/prepare-draft', {sn: this.sn}).then((res) => {
          let qiniu = res.data
          let file = e.target.files[0]
          let form = new FormData()
          form.append('key', qiniu.key)
          form.append('token', qiniu.token)
          form.append('file', file, qiniu.key)
          let option = {
            onUploadProgress: (e) => {
              if (e.lengthComputable) {
                this.uploading = e.loaded/e.total
              }
            }
          }
          this.axios.post(qiniu.upload, form, option).then(() => {
            this.uploading = true
            this.api.post('/api/tool-imageSecurity', {
              url: `${qiniu.url}!preview`
            }).then((r) => {
              this.uploading = null
              if (r.data) {
                this.bus.$emit('dialog', {
                  info: {body: '图片违规，请重新上传'}
                })
              } else {
                this.list[this.editing].content.type = 'image'
                this.list[this.editing].content.draft = qiniu.key
                this.list[this.editing].content.src = qiniu.url
                this.$set(this.list[this.editing].content, {
                  type: 'image',
                  draft: qiniu.key,
                  src: qiniu.url
                })
                this.onEdit()
                this.$refs.writer.focus()
              }
            })
          })
        })
      },
      chooseAudio(e) {
        this.uploading = 0
        this.api.get('/api/prepare-draft', {sn: this.sn}).then((res) => {
          let qiniu = res.data
          let file = e.target.files[0]
          let form = new FormData()
          form.append('key', qiniu.key)
          form.append('token', qiniu.token)
          form.append('file', file, qiniu.key)
          let option = {
            onUploadProgress: (e) => {
              if (e.lengthComputable) {
                this.uploading = e.loaded/e.total
              }
            }
          }
          this.axios.post(qiniu.upload, form, option).then((r) => {
            this.uploading = null
            this.list[this.editing].content.type = 'audio'
            this.list[this.editing].content.draft = qiniu.key
            this.list[this.editing].content.src = qiniu.url
            this.$set(this.list[this.editing].content, {
              type: 'audio',
              draft: qiniu.key,
              src: qiniu.url
            })
            this.onEdit()
            this.$refs.writer.focus()
          })
        })
      },
      chooseVideo(e) {
        let file = e.target.files[0]
        if (file.size > 500*1024*1024) {
          this.bus.$emit('dialog', {
            info: {body: '上传视频大小不能超过500M'}
          })
          return
        }
        this.api.get('/api/prepare-draft', {sn: this.sn}).then((res) => {
          let qiniu = res.data
          let form = new FormData()
          form.append('key', qiniu.key)
          form.append('token', qiniu.token)
          form.append('file', file, qiniu.key)
          this.uploading = 0
          let option = {
            onUploadProgress: (e) => {
              if (e.lengthComputable) {
                this.uploading = e.loaded/e.total
              }
            }
          }
          this.axios.post(qiniu.upload, form, option).then((r) => {
            this.uploading = null
            this.list[this.editing].content.type = 'video'
            this.list[this.editing].content.draft = qiniu.key
            this.list[this.editing].content.src = qiniu.url
            this.$set(this.list[this.editing].content, {
              type: 'video',
              draft: qiniu.key,
              src: qiniu.url
            })
            this.onEdit()
            this.$refs.writer.focus()
          })
        })
      },
      initRecord() {
        this.recorder = new window.MP3Recorder({
          bitRate: 64,
          WORKER_PATH: this.app.config.env==='dev' ? this.app.linkToAssets('/js/recorder/recorder-worker.js') : '/assets/source/js/recorder/recorder-worker.js',
          complete: (data, type) => {
            let blob = new Blob(data, { type: type })
            // this.src = URL.createObjectURL(blob)
            if (blob.size < 200) {
              this.bus.$emit('dialog', {
                info: {
                  head: '录音失败',
                  body: `请前往重置浏览器设置中的站点权限，并刷新页面后尝试<br/>chrome://settings/content/siteDetails?site=${location.origin}<br/>(复制到新窗口打开)`
                }
              })
            } else {
              let e = {
                target: {
                  files: [blob]
                }
              }
              this.chooseAudio(e)
            }
          }
        })
      },
      startRecord() {
        if (this.list[this.editing].content.src) {
          return
        }
        this.recording = 0
        this.recordHandle = setInterval(() => {
          this.recording++
        }, 1000)
        try {
          this.recorder.start(() => {
          }, (e) => {
            alert(e.code+'#'+e.name+':'+e.message)
          })
        } catch (e) {
          this.bus.$emit('dialog', {
            info: {
              head: '录音失败',
              body: `请检查浏览器设置中的站点权限中，麦克风是否开启<br/>chrome://settings/content/siteDetails?site=${location.origin}<br/>(复制到新窗口打开)`
            }
          })
          this.stopRecord()
        }
      },
      stopRecord() {
        clearInterval(this.recordHandle)
        this.recorder.stop()
        this.recordHandle = null
        this.recording = 0
      },
      handleRecord() {
        if (this.recordHandle) {
          this.stopRecord()
        } else {
          this.startRecord()
        }
        this.onEdit()
      },
      isEmpty(idx) {
        if (!this.list[idx]) {
          return false
        }
        if (this.list[idx].content.text || this.list[idx].content.src) {
          return false
        } else {
          return true
        }
      },
      onTitleBlur() {
        this.api.post('/api/create-modify', {
          sn: this.sn,
          title: this.profile.title
        }).catch((e) => {
          this.bus.$emit('dialog', {
            info: {body: e.message}
          })
        })
      },
      onKeydown(e) {
        if (e.ctrlKey && e.keyCode === 13) { // Ctrl+Enter
          this.commit();
        } else if (e.altKey && e.keyCode === 83) { // Alt+S
          this.commit();
        }
        if (!e.ctrlKey && !e.altKey) {
          if (this.editor.caching) {
            clearTimeout(this.editor.caching)
          }
          this.editor.caching = setTimeout(() => {
            let content_ = JSON.stringify(this.list[this.editing].content)
            let _item = localStorage.getItem(`edit-${this.sn}`)
            if (_item && content_ === JSON.stringify(JSON.parse(_item).content)) {
              this.editor.caching = 0
            } else {
              this.editor.caching = false
            }
          }, 1000)
        }
      },
      onPaste(e) {
        let items = (e.clipboardData || window.clipboardData).items;
        for (let item of items) {
          if (item.type.indexOf('image')>=0) {
            let event = {
              target: {
                files: [item.getAsFile()]
              }
            }
            this.chooseImage(event)
          }
        }
      },
      flushCensus() {
        let census = {}
        for (let idx in this.list) {
          let content = this.list[idx].content
          let group = content.free ? 'free' : 'lock'
          if (!census[group]) {
            census[group] = {}
          }
          census[group].text = (census[group].text || 0) + (content.length || 0)
          switch (content.type) {
            case 'image':
              census[group].image = (census[group].image || 0) + 1
              break
            case 'video':
            case 'audio':
              census[group][content.type] = (census[group][content.type] || 0) + (content.duration || 0)
              break;
          }
        }
        this.census = census
      }
    },
    computed: {
      editingTextLength() {
        if (this.list[this.editing]) {
          return (this.list[this.editing].content.text||'').replace(/\s/g, '').length
        } else {
          return 0
        }
      },
      census_() {
        let census = {}
        // console.log(this.list[this.editing].content)
        for (let idx in this.list) {
          let content = this.list[idx].content
          let group = content.free ? 'free' : 'lock'
          if (!census[group]) {
            census[group] = {}
          }
          census[group].text = (census[group].text || 0) + (content.length || 0)
          switch (content.type) {
            case 'image':
              census[group].image = (census[group].image || 0) + 1
              break
            case 'video':
            case 'audio':
              census[group][content.type] = (census[group][content.type] || 0) + (content.duration || 0)
              break;
          }
        }
        return census
      },
      uploadingStyle() {
        if (this.uploading === true) {
          return null
        } else if (this.uploading) {
          let percent = this.uploading * 100
          return {
            background: `linear-gradient(to right, #2f57da ${percent}%, #fff ${percent}%)`,
            animation: 'none'
          }
        } else {
          return null
        }
      }
    },
    watch: {
    },
    filters: Filters
  }
</script>

<style>
 .w-create-prepare #list .c-section .section-markdown {
   line-height: 1.9em;
 }
 .w-create-prepare #list .c-section img {
   max-height: 80px;
 }
 .w-create-prepare #edit .edit-video video,
 .w-create-prepare #edit .edit-video .video,
 .w-create-prepare #edit .edit-video .video-js,
 .w-create-prepare #edit .edit-video .vjs-poster {
   max-width: 600px;
   max-height: 450px;
 }
 .w-create-prepare #list .audio .rate {
   display: none;
 }
 .w-create-prepare #list video,
 .w-create-prepare #list .video,
 .w-create-prepare #list .video-js
 {
   width: 200px;
   height: 80px;
 }
 .w-create-prepare #list .vjs-volume-panel,
 .w-create-prepare #list .vjs-playback-rate {
   display: none;
 }
</style>
<style scoped>
  .w-create-prepare {
    height: 100%;
    max-width: 960px;
    margin: 0 auto;
    background: #fff;
  }
  .head {
    width: 100%;
    height: 100px;
    justify-content: space-between;
    border-bottom: 1px solid #d9d9d9;
  }
  .head-left {
    position: relative;
    align-items: flex-start;
    flex-grow: 1;
    padding: 0 20px;
  }
  .head-right {
    padding: 0 20px;
  }
  #title {
    width: 100%;
    height: 20px;
    margin: 40px 0;
  }
  #title > input {
    flex-grow: 1;
    font-size: 18px;
    border: none;
    outline: none;
  }
  #census {
    position: absolute;
    left: 20px;
    bottom: 15px;
    justify-content: flex-start;
    font-size: 12px;
    color: #666;
  }
  #census .highlight {
    color: #2F57DA;
  }
  #census > div:after {
    content: '；'
  }
  #census > div:last-child:after {
    content: ''
  }
  #census > div > span:after {
    content: '，'
  }
  #census > div > span:last-child::after {
    content: ''
  }
  .body {
    width: 100%;
    max-height: calc(100% - 100px);
    flex-grow: 1;
  }
  #list {
    border-right: 1px solid #d9d9d9;
    height: 100%;
    width: 240px;
    /* resize: horizontal; */
    overflow-x: hidden;
    overflow-y: scroll;
  }
  #list.show-scroll {
    width: 257px;
    border-right: none;
  }
  #edit.listScroll {
    margin-left: -16px;
  }
  #list.hide-scroll::-webkit-scrollbar {
    display: none;
  }
  #list.show-scroll::-webkit-scrollbar {
    border-left: 1px solid #d9d9d9;
    display: inherit;
  }
  #list::-webkit-scrollbar-thumb {
    background:#e9ecf8;
    position: relative;
    height: 10px;
  }
  #list::-webkit-scrollbar-track {
  }
  .section {
    position: relative;
    border-bottom: 1px solid #d9d9d9;
  }
  .section.editing {
    background: rgba(47,87,281, 0.15)
  }
  .pointer {
    position: absolute;
    z-index: 2;
    width: 100%;
    height: 20px;
    top: -10px;
    font-size: 16px;
  }
  .pointer:hover {
    font-size: 20px ;
  }
  .pointer:hover .icon-plus {
    font-size: 20px;
  }
  .pointer-thumb {
    color: #fff;
    background: #2F57DA;
    width: 1em;
    height: 1em;
    border-radius: 50%;
  }
  .pointer.disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  .pointer-thumb > i {
    /*font-size: 12px;*/
  }
  .section:first-child .pointer {
    display: none;
  }
  .tips ul {
    margin: 1.5em 0;
  }
  .tips li {
    margin: .5em 0;
  }
  .control {
    position: relative;
    justify-content: space-between;
    padding: 20px;
    font-size: 12px;
    /*border-bottom: 1px solid #ccc;*/
  }
  .ctrl-seqno {
    color: #2F57DA;
    font-size: 12px;
  }
  .ctrl-label {
    display: flex;
    align-items: center;
    font-size: 12px;
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
    position: absolute;
    font-size: 16px;
    right: 10px;
    padding: 10px;
    color: #2F57DA;
  }
  .section-content {
    position: relative;
    padding: 0 20px;
    margin-bottom: 20px;
    color: #222;
    font-size: 12px;
    height: 80px;
    max-height: 80px;
    overflow: hidden;
  }
  .section-content .video {
    background: #000;
    color: #fff;
  }
  .section-content .video .icon-yike {
    position: absolute;
    background: rgba(0,0,0,0.5);
    padding: 4px 12px;;
    font-size: 12px;
    border-radius: 5px;
    border: 1px solid #fff;
  }

  #edit {
    flex-grow: 1;
    height: 100%;
    padding: 0 36px;
  }
  .edit-head {
    width: 100%;
    justify-content: space-between;
    height: 50px;
    font-size: 12px;
    border-bottom: 1px solid #d9d9d9;
  }
  .edit-head span {
    padding: 0 .5em;
  }
  .edit-hint {
    width: 100%;
    background:#e9ecf8;
    line-height: 2em;
    color: #2f57da;
    padding: 8px;
    border: 1px solid #d9d9d9;
    border-top: 0;
    border-radius: 8px;
  }
  .edit-body {
    width: 100%;
    flex-grow: 1;
    justify-content: flex-start;
    align-items: flex-start;
  }
  .edit-foot {
    justify-content: space-between;
    width: 100%;
    padding: 30px 0;
  }
  .edit-body > div{
    margin-top: 30px;
  }
  .edit-media .icon-yike {
    font-size: 16px;
  }
  .edit-media > div {
    position: relative;
    cursor: pointer;
    padding: 15px;
  }
  .edit-media.addon {
    color: #d9d9d9;
  }
  .edit-media.addon label {
    display: none;
  }
  .edit-media.addon.edit-media > div {
    cursor: no-drop;
  }
  .edit-media input {
    display: none;
  }
  .edit-media label {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
  }
  .edit-number {
    border-right: 1px solid #d9d9d9;
    padding-right: 30px;
    margin-right: 15px;
  }
  .edit-media-record.recording {
    color: #e9514d;
  }
  .edit-recorder, .edit-uploading {
    position: relative;
    justify-content: space-between;
    background: #EAEAF8;
    width: 370px;
    height: 54px;
    border-radius: 27px;
  }
  .edit-recorder .stop {
    position: relative;
    z-index: 2;
    background: #2F57DA;
    color: #fff;
    width: 54px;
    height: 54px;
    border-radius: 50%;
    font-size: 30px;
    text-align: center;
    line-height: 44px;
  }
  .edit-recorder img {
    position: absolute;
    left: 0;
  }
  .edit-recorder .time {
    padding: 0 1em;
  }
  .edit-uploading > div{
    margin: 0 20px;
    color: #2F57DA;
  }
  .edit-uploading .progress {
    margin-left: 0;
    width: 250px;
    height: 3px;
  }
  .edit-uploading .slider {
    margin-left: 0;
    width: 250px;
    height: 3px;
    background: linear-gradient( to right, #fff, #2f57da);
    animation: 1.5s linear 0s infinite normal none running slide;
  }
  @keyframes slide {
    0% {}
    100% {
      background-position:  250px 0;
    }
  }
  .edit-body .icon-cross {
    position: absolute;
    right: -40px;
    top: 0;
    padding: 4px;
    border-radius: 50%;
    color: #fff;
    background: #97999E;
    cursor: pointer;
  }
  .edit-image, .edit-video, .edit-audio {
    position: relative;
    max-width: 600px;
    max-height: 600px;
  }
  .edit-audio {
    width: 420px;
  }
  .edit-audio .icon-cross, .edit-recorder .icon-cross {
    top: calc(50% - 12px);
  }
  .edit-image > img {
    max-width: 600px;
    max-height: 450px;
  }
  .edit-text {
    flex-grow: 1;
    width: 100%;
  }
  .edit-text textarea {
    outline: none;
    border: none;
    resize: none;
    padding: 0;
    width: 100%;
    height: 100%;
    font-size: 16px;
    overflow-y: scroll;
  }
  .edit-text textarea::-webkit-scrollbar {
    display: none;
  }
  .btn-prime {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #2F57DA;
    color: #fff;
    font-size: 14px;
    height: 2em;
    padding: 0 1em;
    border-radius: 1em;
  }
  .btn-prime.disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  .switch-check {
    display: flex;
    position: relative;
    background: lightgray;
    height: 2em;
    width: 4em;;
    border-radius: 1em;
  }
  .switch-check input {
    position: absolute;
    opacity: 0;
    top: 50%;
    left: 50%;
  }
  .switch-check .switch-thumb {
    width: 2em;
    height: 2em;
    border-radius: 50%;
    background: #666;
  }
  .switch-check.checked {
    background: #cfd7f3;
    justify-content: flex-end;
  }
  .switch-check.checked .switch-thumb {
    background: #2F57DA;
  }
  .banner {
    background: rgba(0,0,0,0.7);
    position: absolute;
    width: 100%;
    padding: 2em 0;
    font-size: 20px;
    top: 0;
    z-index: 2;
    text-align:center;
    color: #fff;
  }
  .banner .icon-cross {
    border: 1px solid #fff;
    border-radius: 50%;
    padding: .3em;
  }
  .banner a {
    color: #fff;
    text-decoration: underline;
  }
  .slide-enter-active, .slide-leave-active {
    transition: all .5s;
  }

  .slide-enter, .slide-leave-to {
    transform: translateY(-100%);
  }
</style>
