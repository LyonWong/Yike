<template>
  <div class="v-user-datum">
    <div class="profile" v-if="profile">
      <div class="item avatar flex-row">
        <div class="label">头像</div>
        <div class="value flex-row" v-if="teacher">
          <img :src="profile.avatar"/>
          <i class="icon-yike icon-arrow-r" v-if="teacher"></i>
          <input id="uploadAvatar" class="btn flex-grow" type="file" accept="image/*" @change="changeAvatar" v-if="teacher"/>
        </div>
        <div class="value flex-row" v-else>
          <img :src="profile.avatar"/>
        </div>
      </div>
      <div class="item nickname flex-row">
        <div class="label">昵称</div>
        <div class="value" v-if="teacher">
          <input id="inputNickname" type="text" v-model="profile.name" @blur="changeNickname" :disabled="!teacher"/>
          <!--<i class="icon-yike icon-pencle" v-if="teacher"></i>-->
          <i class="icon-yike icon-ok btn"></i>
        </div>
        <div class="value" v-else>
          <span>{{profile.name}}</span>
        </div>
      </div>
      <div class="item usn flex-row">
        <div class="label">账号</div>
        <div class="value" title="点击复制账号" v-clipboard="profile.sn" @success="bus.$emit('toast', {icon: 'ok', text: '复制账号成功', duration: 1000})">{{profile.sn}}</div>
      </div>
    </div>
    <div class="privacy" v-if="privacy">
      <div class="item flex-col subscribe" id="subscribe">
        <div class="item-head flex-row flex-item">
          <div class="label flex-row">
            <img :src="app.linkToAssets('/img/logo/Original_6464@2x.png')"/>
            <span>关注易灵微课</span>
          </div>
          <div class="value">
            <div class="btn disabled flex-row" v-if="privacy.subscribe">已关注</div>
            <div class="btn flex-row" v-else @click="subscribe">
              <span class="icon-add">关注</span>
            </div>
          </div>
        </div>
      </div>
      <div class="item telephone flex-row">
        <div class="label">手机</div>
        <div class="value">
          <div v-if="privacy.telephone">{{privacy.telephone}}</div>
          <div class="btn flex-row" v-else @click="bindTelephone">
            <span class="icon-add">绑定</span>
          </div>
        </div>
      </div>
    </div>
    <div class="teacher" v-if="teacher || $route.path === '/user/apply'">
      <a class="item value flex-row" v-if="teacher" :href="`/home/${profile.sn}/`">
        <div class="label">个人主页</div>
        <div class="value">
          <i class="icon-yike icon-arrow-r"></i>
        </div>
      </a>
      <div class="item about flex-col">
        <div class="label flex-row" @click="editAbout=true">
          <span>简介</span>
          <span v-if="teacher">
            <i class="btn icon-yike icon-ok" v-if="about!==null" @click="saveAbout"></i>
            <i class="btn icon-yike icon-pencle" v-else @click="about=teacher.about"></i>
          </span>
        </div>
        <div class="value flex-row" v-if="about!==null || !teacher">
          <textarea v-model="about" :placeholder="privacy.telephone ? '介绍一下自己吧' : '请先绑定手机'" :disabled="!privacy.telephone"></textarea>
        </div>
        <div class="value flex-row" v-else>
          <c-section class="flex-item" :content="{type: 'markdown', text: teacher.about}"></c-section>
        </div>
        <div class="agreement">
          <label v-if="!teacher">
            <input type="checkbox" id="agreement" v-model="agreement"/>
          </label>
          <span @click="showTeacherAgreement=true">讲师协议</span>
        </div>
      </div>
      <div class="btn apply flex-row" v-if="teacher===null" @click="applyTeacher">
        成为讲师
      </div>
      <popup id="teacher-agreement" :is-open="showTeacherAgreement" v-on:close="showTeacherAgreement=false">
        <div slot="head">讲师协议</div>
        <teacher-policy></teacher-policy>
        <div slot="foot" class="btn btn-apply flex-row" @click="showTeacherAgreement=false">
          <div v-if="teacher">确认</div>
          <div v-if="!teacher" class="btn btn-cancel">取消</div>
          <div v-if="!teacher" class="btn btn-agree" @click="agreement=true">同意</div>
        </div>
      </popup>
    </div>
  </div>
</template>

<script>
  import vue from 'vue'
  import CSection from "../../components/section/index";
  import Popup from "../../components/Popup";
  // import TeacherAgreement from "../components/TeacherAgreement";
  import TeacherPolicy from "../components/TeacherPolicy";
  import ModalDialog from "../../components/modal/Dialog";
  import ClipBoards from "vue-clipboards"
  vue.use(ClipBoards)

  export default {
    name: 'user-datum',
    components: {ModalDialog, TeacherPolicy, Popup, CSection},
    data() {
      return {
        profile: null,
        privacy: null,
        teacher: false,
        uploading: true, // 正在上传
        about: null, // 个人简介内容
        editAbout: null, // 编辑个人简介
        agreement: false,
        showTeacherAgreement: false, // 弹出讲师协议
        showTeacherPolicy: false, // 展示讲师须知
        subscribing: 0 // 关注易灵微课检测,
      }
    },
    created() {
      this.api.get('/api/user-profile').then((res) => {
        this.profile = res.data
      }, this.api.onErrorSign)
      this.api.get('/api/user-privacy').then((res) => {
        this.privacy = res.data
      })
      this.api.get('/api/user-teacher').then((res) => {
        this.teacher = res.data // 非讲师为null
      })
    },
    methods: {
      refreshInfo() {
        this.api.post('/api/user-info-refresh', null).then((res) => {
          this.user = res.data
        })
      },
      subscribe() {
        if (this.subscribing) {
          clearInterval(this.subscribing)
          this.subscribing = 0
          this.bus.$emit('dialog', null)
        } else {
          if (this.app.env(2) === 'wx') { // 微信环境直接跳转详情页
            location.href="https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzUyMDAzNDYxNw==#wechat_redirect"
          } else { // 弹出扫码窗口
            this.bus.$emit('dialog', {
              info: {
                head: '扫码关注',
                body: `<img style="max-width:100%;" src="${this.app.linkToAssets('/img/qrcode/yike-fm.png')}"/>`
              },
              btn: {
                prime: '关闭'
              }
            })
          }
          this.subscribing = setInterval(() => {
            this.api.get('/api/user-privacy', null, {loading: false}).then((res) => {
              this.privacy = res.data
              if (this.privacy.subscribe) { // 检测到关注成功
                this.subscribe() // 停止轮询检测
              }
            })
          }, 1000)
        }
      },
      applyTeacher() {
        // 检查是否关注公众号
        if (!this.privacy.subscribe) {
          this.bus.$emit('dialog', {
            info: { body: '请关注易灵微课公众号' },
            btn: { prime: '去关注', vice: '知道了' },
            call: {
              prime: () => {
                setTimeout(() => {
                  this.subscribe()
                }, 0)
              }
            }
          })
          return
        }
        // 检查是否绑定手机
        if (!this.privacy.telephone) {
          this.bus.$emit('dialog', {
            info: { body: '请绑定手机' },
            btn: {
              prime: '去绑定', vice: '知道了' },
            call: {
              prime: () => {
                this.$router.push('/user/bind/telephone')
              }
            }
          })
          return
        }
        // 简介不能为空
        if (!this.about) {
          this.bus.$emit('dialog', {
            info: {body: '简介不能为空'}
          })
          return
        }
        // 检查是否同意讲师协议
        if (this.agreement===false) {
          this.bus.$emit('dialog', {
            info: {body: '请阅读并同意讲师协议'},
            btn: {
              prime: '查看',
              vice: '取消'
            },
            call: {
              prime: () => {
                this.showTeacherAgreement=true
              }
            }
          })
          return
        }
        this.api.post('/api/user-teacher-apply', {
          'about': this.about
        }).then(() => {
          // 跳转至讲师须知页
          location.href = '/user/teacher-notice'
          /*
          this.api.get('/api/user-teacher').then((res) => {
            // 更新讲师信息
            this.teacher = res.data
            // 提示开始创作
            this.bus.$emit('dialog', {
              info: {
                body: '注册成功'
              },
              btn: {
                prime: '写文章',
                vice: '知道了'
              },
              call: {
                prime: () => {
                  location.href = '/create/posts'
                },
                vice: () => {
                  location.href = '/my/home'
                }
              }
            })
          })
          */
        })
      },
      changeAvatar(e) {
        let form = new FormData()
        form.append('avatar', e.target.files[0])
        this.api.post('/api/user-update-avatar', form, {loading: '正在上传头像'}).then(() => {
          this.api.get('/api/user-profile').then((r) => {
            this.profile = r.data
          })
        })
      },
      changeNickname() {
        this.api.post('/api/user-update-name', {
          'name': this.profile.name
        }).then((res) => {
          this.profile = res.data
        })
      },
      bindTelephone() {
        if (this.privacy.telephone) {
          this.bus.$emit('dialog', {
            info: {
              body: '是否前往更换绑定的手机？'
            },
            btn: {
              prime: '确认',
              vice: '取消'
            },
            call: {
              prime: () => {
                this.$router.push('/user/bind/telephone')
              }
            }
          })
        } else {
          this.$router.push('/user/bind/telephone')
        }
      },
      saveAbout() {
        if (!this.about) {
          this.bus.$emit('dialog', {
            info: {body: '简介不能为空'}
          })
          return
        }
        this.api.post('/api/user-teacher-update', {
          'about': this.about
        }).then(() => {
          this.teacher.about = this.about
          this.about = null
        })
      }
    },
    computed: {}
  }
</script>

<style scoped>
  .v-user-datum {
  }

  .avatar, .profile, .privacy {
    margin-bottom: .3rem;
  }

  .item {
    justify-content: space-between;
    padding: 0 .3rem;
    border-bottom: 1px solid #ddd;
    background: #fff;
    font-size: .3rem;
    color: #222;
  }
  .item:last-child {
    border: 0;
  }
  .item-head {
    width: 100%;
    justify-content: space-between;
  }
  .item .label {
    height: 1.1rem;
    line-height: 1.1rem;
  }

  .item .label img {
    margin-right: .2rem;
    width: .45rem;
    height: .45rem;
  }

  .item .value {
    color: #aaa;
  }

  .item .value .btn {
    height: .5rem;
    border-radius: .25rem;
    border: 1px solid #2F57DA;
    font-size: .26rem;
    padding: 0 1em;
    color: #2F57DA;
  }
  .item .value .btn.disabled {
    border-color: #aaa;
    color: #aaa;
  }

  .item .value .icon-yike {
    font-size: .3rem;
    color: #aaa;
  }

  .item .value .icon-pencle {
    font-size: .22rem;
    color: #aaa;
  }

  .item .value textarea {
    font-size: .3rem;
    min-height: 3rem;
    flex-grow: 1;
    border: 1px solid #ddd;
    resize: none;
  }
  .avatar {
    height: 2rem;
    border: none;
  }
  .avatar img {
    margin: 0 .3rem;
    border-radius: 0.08rem;
    width: 1.2rem;
    height: 1.2rem;
  }
  #subscribe .item-body {
    padding: .3rem 0;
  }
  #subscribe .item-body a {
    color: #2F57DA;
  }
  #subscribe .item-body img {
    max-width: 50vw;
    max-height: 50vw
  }
  #subscribe .icon-wechat {
    font-size: .24rem;
    padding: .1rem;
  }
  #subscribe .icon-wechat::before {
    color: green;
    margin-right: .5em;
  }
  .about {
    padding-bottom: .3rem;
    border: none;
    min-height: 5rem;
  }
  .about .value {
    position: relative;
    width: 100%;
  }

  .about .c-section {
    font-size: .3rem;
    color: #666;
    overflow: hidden;
    min-height: 3rem;
  }

  .about .label {
    width: 100%;
    justify-content: space-between;
  }

  .about .label .icon-pencle {
    font-size: .3rem;
    color: #aaa;
  }
  .about .label .icon-ok {
    color: #2F57DA;
  }

  .about .folded {
    height: 3rem;
  }

  .about .shield {
    position: absolute;
    width: 100%;
    height: 1rem;
    background: linear-gradient(to bottom, transparent, #fff);
    bottom: 0;
  }

  .item .value i.icon-ok {
    color: #2F57DA;
    border: none;
    padding: 0;
  }
  .btn.apply {
    position: sticky;
    bottom: 0;
    height: 1rem;
    font-size: .4rem;
    color: #fff;
    background: #2F57DA;
  }

  #uploadAvatar {
    opacity: 0;
    position: absolute;
    left: 0;
    width: 100%;
    height: 1.2rem;
    padding: 0;
    border: none;
  }

  #inputNickname {
    border: none;
    outline: none;
    font-size: .3rem;
    text-align: right;
    width: 10em;
    background: transparent;
    color: #999;
  }
  #inputNickname:focus {
    color: #333;
  }
  #inputNickname ~ .icon-ok {
    display: none;
  }
  #inputNickname:focus ~ .icon-ok {
    display: inline;
    color: #2F57DA;
  }
  #inputNickname:focus ~ .icon-pencle {
    display: none;
  }

  .btn-close, .btn-apply {
    font-size: .4rem;
    background: #2F57DA;
    color: #fff;
    flex-grow: 1;
    height: 100%;
  }
  .btn-apply .btn {
    text-align: center;
    height: 1rem;
    line-height: 1rem;
    flex-grow: 1;
  }
  .btn-apply .btn-cancel {
    background: #fff;
    color: #2F57DA;
  }

  .c-teacher-policy {
    max-height: 60vh;
    overflow-y: scroll;
    padding: .3rem;
    color: #666;
  }
  .icon-add:before {
    content: "＋";
  }
  .agreement {
    margin-top: .3rem;
  }
  .agreement input {
    width: .25rem;
    height: .25rem;
  }
  .agreement span {
    font-size: .3rem;
    color: #2F57DA;
    border-bottom: 1px solid #2F57DA;
  }

</style>
