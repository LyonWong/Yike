<template>
  <div class="c-create-commit">
    <div class="items">
      <div class="title item icon-yike" v-if="profile.form==='column'">
        <input id="input-title" class="flex-item" type="text" v-model="profile.title" placeholder="专栏名称"/>
      </div>
      <div class="cover item">
        <div class="item-form">
          <div class="item-label" :class="{required: profile.form==='column'}">上传封面</div>
          <div class="item-input item-button" v-if="!profile.cover">
            选择图片
            <input id="upload-cover" class="btn" type="file" accept="image/*" @change="chooseCover">
          </div>
        </div>
        <div class="cover-item flex-row" v-if="profile.cover">
          <div class="img">
            <img :src="`${profile.cover}!cc.750_400`"/>
            <i class="icon-yike icon-cross" @click="removeCover"></i>
          </div>
          <div class="item-input item-button">
            选择图片
            <input id="change-cover" class="btn" type="file" accept="image/*" @change="chooseCover">
          </div>
        </div>
        <div class="item-desc">
          封面尺寸750×400，超过部分将居中剪裁
        </div>
      </div>
      <div class="brief item" v-if="profile.form==='column'">
        <div class="item-form">
          <div class="item-label" :class="{required: profile.form==='column'}">专栏介绍</div>
        </div>
        <div id="input-brief" class="flex-row" v-show="briefing">
          <textarea ref="brief" class="flex-item" v-model="brief" @blur="briefing=false"></textarea>
        </div>
        <div id="preview-brief" v-show="!briefing" @click="briefFocus">
          <c-section :content="{type: 'markdown', text: brief}"></c-section>
        </div>
      </div>
    </div>
    <div class="items">
      <!--是否公开-->
      <div class="overt item" v-if="0">
        <div class="item-form">
          <div class="item-label">公开发表</div>
          <div class="item-switch">
            <switch-capsule v-model="overt" size=".3rem" v-on:check="overt=!overt"></switch-capsule>
          </div>
        </div>
        <div class="item-desc">
          公开后将在个人主页中展示
        </div>
      </div>
      <!--单独付费-->
      <div class="indie item" v-if="profile.category">
        <div class="item-form">
          <div class="item-label">单独付费</div>
          <div class="item-switch">
            <switch-capsule v-model="indie" size=".3rem" tone="prime" v-on:check="indie=!indie"></switch-capsule>
          </div>
        </div>
        <div class="item-desc">
          允许单独付费购买，否则只允许购买专栏
        </div>
      </div>
      <!--可否退款-->
      <div class="refundable item" v-if="!profile.category || indie">
        <div class="item-form">
          <div class="item-label">允许退款</div>
          <div class="item-switch">
            <switch-capsule v-model="refundable" size=".3rem" v-on:check="refundable=!refundable" tone="prime"></switch-capsule>
          </div>
        </div>
        <div class="item-desc">
          是否允许用户一小时无条件退款
        </div>
      </div>
      <!--开启留言-->
      <div class="discuss item" v-if="profile.form==='article'">
        <div class="item-form">
          <div class="item-label">开启留言</div>
          <div class="item-switch">
            <switch-capsule v-model="discuss" size=".3rem" v-on:check="discuss=!discuss" tone="prime"></switch-capsule>
          </div>
        </div>
      </div>
      <!--设置价格-->
      <div class="price item" v-if="!profile.category || indie">
        <div class="item-form">
          <div class="item-label required">{{dict[profile.form]}}价格</div>
          <div class="item-input input-money">
            <input type="number" v-model="price" required @focus="price=null" @blur="price=round(price, 2)"/>
          </div>
        </div>
        <div class="item-desc">
          其中1%将作为平台技术服务费
        </div>
      </div>
    </div>
    <div class="items" v-if="!profile.category || indie">
      <!--授权平台推广-->
      <div class="platshare item" v-if="!profile.category || indie">
        <div class="item-form">
          <div class="item-label">平台推广</div>
          <div class="item-switch">
            <switch-capsule v-model="sharingDict['_']" size=".3rem" v-on:check="sharingDict['_']=!sharingDict['_']" tone="prime"></switch-capsule>
          </div>
        </div>
        <div class="item-desc">
          授权易灵微课平台推广，您的分成金额为(1-1%)×价格×0.5
        </div>
      </div>
      <!--分享奖励比例-->
      <div class="commission item">
        <div class="item-form">
          <div class="item-label">分享奖励</div>
          <div class="item-input input-percent">
            <input type="number" v-model="commission" @blur="testCommision()"/>
          </div>
        </div>
        <div class="item-desc">
          分享购买可获得的奖励￥{{price*commission/100}}
        </div>
      </div>
      <div class="promote-card item" v-if="0">
        <div class="item-form">
          <div class="item-label">邀请卡图</div>
        </div>
        <div class="item-desc">
          可上传自定义邀请卡，缺省则使用系统默认模板
        </div>
      </div>
      <!--渠道推广分成-->
      <div class="sharing item">
        <div class="item-form">
          <div class="item-label">渠道分销</div>
        </div>
        <div class="item-desc">
          配置第三方分销渠道，渠道账号为分销者个人资料页中的账号。您的分成金额为(1-1%)×价格-分享奖励-渠道分成。
        </div>
        <div class="item-list">
          <div class="share-edit flex-row">
            <div class="item-input">
              <input class="share-from flex-item" type="text" size="12" style="width:auto" v-model="sharingDict['+'].usn" placeholder="渠道账号"/>
            </div>
            <div class="flex-row">
              <div class="item-input input-percent">
                <input class="share-ratio" type="number" v-model="sharingDict['+'].ratio" placeholder="分成比例"/>
              </div>
              <div class="btn btn-add" @click="addSharing">＋</div>
            </div>
          </div>
          <div class="share-item flex-row" v-for="(item, idx) in sharingList" :key="idx" v-if="item.ratio">
            <div class="share-info flex-row">
              <span class="share-name">{{item.name}}</span>
              <span class="share-fee">{{item.ratio}}%</span>
              <!--<span class="share-ratio">{{item.ratio}}%</span>-->
            </div>
            <div class="btn share-link" @click="showShareLink(item)">推广链接</div>
            <div class="btn btn-cut" @click="delSharing(idx)">－</div>
          </div>
        </div>
      </div>
    </div>
    <!--避免文章配置出现空白段-->
    <div class="items" v-if="profile.form==='column'">
      <div class="preemptive item" v-if="0">
        <div class="item-form">
          <div class="item-label">限时收费</div>
          <div class="item-switch">
            <switch-capsule v-model="preemptive" size=".3rem" v-on:check="preemptive=!preemptive" tone="prime"></switch-capsule>
          </div>
        </div>
        <div class="item-desc">
          仅在限定时间内收费
        </div>
      </div>
      <div class="notice item" v-if="profile.form==='column'">
        <div class="item-form">
          <div class="item-label">服务号通知</div>
          <div class="item-switch">
            <switch-capsule v-model="inform" size=".3rem" v-on:check="inform=!inform"></switch-capsule>
          </div>
        </div>
        <div class="item-desc">
          开启后可通过【易灵微课】公众号向用户推送更新通知
        </div>
      </div>
    </div>
    <div class="items">
      <div class="delete item btn" @click="remove">
        删除{{dict[profile.form]}}
      </div>
    </div>
    <modal-action width="6rem" :display="shareLink" v-on:close="shareLink=null">
      <div class="share-link-text">{{shareLink}}</div>
      <div slot="foot" class="btn btn-prime" v-clipboard="shareLink" @success="afterCopyShareLink">复制</div>
    </modal-action>
  </div>
</template>

<script>
  import Vue from "vue";
  import SwitchCapsule from "../../components/unit/SwitchCapsule";
  import Filters from "@/assets/js/filters"
  import SectionMarkdown from "../../components/section/Markdown";
  import CSection from "../../components/section/index";
  import ClipBoards from "vue-clipboards"
  import ModalAction from "../../components/modal/Action";
  Vue.use(ClipBoards)
  export default {
    name: 'create-commit',
    props: ['sn'],
    components: {ModalAction, CSection, SectionMarkdown, SwitchCapsule},
    filters: {
      round: Filters.round
    },
    data() {
      return {
        profile: {},
        form: null,
        cover: null,
        brief: null,
        briefing: false,
        price: 0,
        indie: null,
        commission: null,
        refundable: true,
        preemptive: false,
        discuss: false,
        inform: false,
        overt: false,
        sharingDict: {
          '_': true,
          '+': {
            usn: null,
            fee: null
          }
        },
        sharingList: [],
        shareLink: null,
        dict: {
          'course': '课程',
          'article': '文章',
          'column': '专栏'
        }
      }
    },
    created() {
      this.api.get('/api/create-profile', {
        sn: this.sn
      }).then((res) => {
        this.profile = res.data
        this.flush()
      })
    },
    mounted() {},
    methods: {
      briefFocus() {
        this.briefing = true
        setTimeout(() => {
          this.$refs['brief'].focus()
        }, 100)
      },
      submit() {
        if (this.check()) {
          console.log('save start')
          this.save(() => {
            this.$emit('submit', this.profile)
          })
        }
      },
      check() {
        console.log('check start')
        if (this.price<0) {
          this.bus.$emit('dialog', {
            info: {body: '价格不能小于0'}
          })
          return false
        }
        if (this.profile.form === 'column') {
          let checklist = [
            {item: this.profile.title, hint: '请填写专栏名称'},
            {item: this.cover, hint: '请上传专栏封面'},
            {item: this.brief, hint: '请填写专栏介绍'}
          ]
          for (let v of checklist) {
            if (!v.item) {
              this.bus.$emit('dialog', {
                info: { body: v.hint }
              })
              return false
            }
          }
          if (this.profile.title.split('').length>14) {
            this.bus.$emit('dialog', {
              info: {body: '专栏名称不超过14字'}
            })
            return false
          }
        }
        console.log('check end')
        return true
      },
      save(call) {
        if (this.price<0) {
          this.bus.$emit('dialog', {
            info: {body: '价格不能小于0'}
          })
          return
        }
        if (this.profile.form === 'column') {
          let checklist = [
            {item: this.profile.title, hint: '请填写专栏名称'},
            {item: this.cover, hint: '请上传专栏封面'},
            {item: this.brief, hint: '请填写专栏介绍'}
          ]
          for (let v of checklist) {
            if (!v.item) {
              this.bus.$emit('dialog', {
                info: { body: v.hint }
              })
              return
            }
          }
          if (this.profile.title.split('').length>14) {
            this.bus.$emit('dialog', {
              info: {body: '专栏名称不超过14字'}
            })
            return
          }
        }
        let sharing = {
          _: this.sharingDict._
        }
        for (let share of this.sharingList) {
          sharing[share.usn] = share.ratio/100
        }
        this.api.post('/api/create-commit', {
          sn: this.sn,
          form: this.profile.form,
          title: this.profile.title,
          cover: this.cover,
          price: this.price,
          brief: this.brief,
          discuss: this.discuss,
          commission: this.commission/100,
          preemptive: this.preemptive,
          refundable: this.refundable,
          inform: this.inform,
          overt: this.overt,
          indie: this.indie,
          sharing: sharing
        }).then((res) => {
          this.profile = res.data
          this.flush()
          call && call()
        }).catch((err) => {
          alert(err.message)
        })
      },
      flush() {
        this.api.get('/api/lesson-introduce', {
          sn: this.sn
        }).then((res) => {
          this.brief = res.data
        })
        this.price = this.profile.price
        this.cover = this.profile.cover
        this.brief = this.profile.brief
        this.overt = this.profile.overt
        let conf = this.profile.extra.conf || {}
        this.commission = conf.commission*100 || 30
        this.refundable = conf.refundable
        this.discuss = conf.discuss === undefined ? true : conf.discuss
        this.inform = conf.inform
        this.indie = conf.indie || !this.profile.category // 从属内容默认不开独立售卖
        for (let sn in conf.sharing) {
          if (sn==='_') {
            this.sharingDict['_'] = Boolean(conf.sharing['_'])
          } else if (!this.sharingDict[sn]) {
            this.api.get('/api/user-profile', {
              usn: sn
            }).then((res) => {
              this.sharingList.push({
                usn: res.data.sn,
                name: res.data.name,
                ratio: conf.sharing[sn]*100
              })
            })
          }
        }
      },
      remove() {
        this.bus.$emit('dialog', {
          info: {
            body: '确认删除吗？'
          },
          call: {
            prime: () => {
              this.api.post('/api/create-delete', {
                sn: this.sn
              }).then(() => {
                location.href = `/create/posts#${this.profile.form}`
              }, (e) => {
                alert(e.message)
              })
            }
          },
          btn: {
            prime: '确认',
            vice: '取消'
          }
        })
      },
      chooseCover(e) {
        this.api.get('/api/prepare-draft', {sn: this.sn}).then((res) => {
          let qiniu = res.data
          let file = e.target.files[0]
          let form = new FormData()
          form.append('key', qiniu.key)
          form.append('token', qiniu.token)
          form.append('file', file, qiniu.key)
          let option = {}
          this.axios.post(qiniu.upload, form, option).then((r) => {
            this.type = 'image'
            this.cover = qiniu.key
            this.profile.cover = qiniu.url
          })
        })
      },
      removeCover() {
        this.cover = false
        this.profile.cover = null
      },
      addSharing() {
        let item  = this.sharingDict['+']
        console.log(item, this.sharingDict)
        if (!item.usn) {
          this.bus.$emit('dialog', {
            info: {body: '渠道账号不能为空'}
          })
          return
        }
        if (this.sharingDict[item.usn] && this.sharingDict[item.sn].ratio) {
          this.bus.$emit('dialog', {
            info: {body: '渠道账号不能重复'}
          })
          return
        }
        if (!item.ratio  || item.ratio <=0 || item.fee > 99) {
          this.bus.$emit('dialog', {
            info: {
              body: `分成比例须在0~99%之间`
            }
          })
          return
        }
        this.api.get('/api/user-profile', {
          usn: item.usn
        }).then((res) => {
          item.name = res.data.name
          this.sharingList.push(item)
          this.sharingDict[item.usn] = item.ratio
          this.sharingDict['+'] = {
            usn: null,
            fee: null
          }
          this.save()
        }).catch(() => {
          this.bus.$emit('dialog', {
            info: {
              head: '渠道错误',
              body: '请填写正确的渠道账号'
            }
          })
        })
      },
      delSharing(idx) {
        // this.sharingList.splice(idx, 1)
        this.sharingList[idx]['ratio'] = 0
        this.save()
      },
      showShareLink(item) {
        this.shareLink = `${location.origin}/home/${item.usn}/${this.profile.form}?sn=${this.sn}`
      },
      afterCopyShareLink() {
        this.shareLink = null
        this.bus.$emit('toast', {icon: 'ok', text: '复制成功', duration: 1000})
      },
      testCommision() {
        if (this.commission < 0 || this.commission > 99) {
          this.bus.$emit('dialog', {
            info: {
              body: `分享奖励须在0~99%之间`
            }
          })
          this.commission = 30
        }
      },
      round(v) {
        return Filters.round(v, 2)
      }
    },
    computed: {
    }
  }
</script>

<style scoped>
  .c-create-commit {
    position: relative;
    font-size: .3rem;
  }

  .items {
    margin-bottom: .2rem;
    background: #fff;
  }
  .items:last-child {
    margin-bottom: 0;
  }
  .items > .item:last-child {
    border: none;
  }
  .item {
    display: flex;
    flex-direction: column;
    border-bottom: 1px solid #ddd;
    padding: .35rem .3rem;
  }
  .item-form {
    display: flex;
    justify-content: space-between;
    padding: .05rem 0;
    align-items: center;
  }
  .item-label {
    color: #222;
  }
  .item-input {
    position: relative;
    display: flex;
    color: #2F57DA;
    font-size: .36rem;
    align-items: center;
    letter-spacing: .1em;
    background: #f2f2f2;
    border-radius: 0.08rem;
    border: 1px solid #c9c9c9;
  }
  .item-input > input {
    font-size: .36rem;
    width: 1.6rem;
    padding: .06rem;
    background: #f2f2f2;
    border: none;
  }
  .item .icon-yike {
    font-size: .3rem;
  }
  .item-switch {
  }
  .item-desc {
    font-size: .24rem;
    color: #999;
    padding: .15rem 0;
  }
  .item-button {
    background: #f2f2f2;
    color: #2F57DA;
    border-radius: .08rem;
    font-size: .28rem;
    padding: .11rem;
  }
  .btn-add, .btn-cut {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: 1em;
    width: .36rem;
    height: .36rem;
    text-align: center;
    color: #2F57DA;
    font-size: .24rem;
    border: 2px solid #2F57DA;
    font-weight: bold;
    border-radius: 50%;
  }
  .item-list button {
    font-size: .3rem;
    width: 1em;
  }
  .title {
    font-size: .36rem;
    background: #fff;
    height: 1.44rem;
    padding: 0 .3rem;
    color: #999;
  }
  #input-title {
    font-size: .36rem;
    outline: none;
    border: none;
    background: transparent;
  }
  #input-title:focus {
    background: #fff;
  }
  .item-input.icon-photo {
    justify-content: flex-end;
    font-size: .3rem;
    text-align: right;
  }
  .input-percent input{
    text-align: right;
  }
  .input-money:before {
    content: '￥';
    color: #2F57DA;
  }
  .input-percent:after {
    content: '%';
    color: #2F57DA;
  }
  .share-edit {
    height: .6rem;
    justify-content: space-between;
  }
  .share-edit > .share-from {
    margin-right: 1em;
  }
  .share-edit > .share-ratio {
    width: 1.7rem;
    text-align: center;
  }
  .share-item {
    margin: .2rem 0;
    display: flex;
    justify-content: space-between;
  }
  .share-info {
    border: 1px solid #ddd;
    border-radius: .08rem;
    background: #f2f2f2;
    flex-grow: 1;
    height: .6rem;
    margin-right: .2rem;
    justify-content: space-between;
    padding: 0 .06rem;
  }
  .btn.share-link {
    border: 1px solid #2F57DA;
    border-radius: .3rem;
    background: #D8E1FD;
    color: #2F57DA;
    height: .6rem;
    padding: 0 .8em;
    line-height: .6rem;
  }
  .share-fee {
    color: #2F57DA;
  }
  .cover-item {
    margin-top: .2rem;
    justify-content: space-between;
    align-items: flex-end;
    width: 100%;
    height: 2rem;
  }
  .cover-item > .img {
    position: relative;
    width: 3.75rem;
    height: 2rem;
  }
  .cover-item img {
    width: 100%;
    height: 100%;
  }
  .cover-item  i {
    position: absolute;
    top: 0;
    right: 0;
    background: #999;
    color: #fff;
    border-radius: 50%;
    margin: .05rem;
    padding: .05rem;
  }
  #upload-cover, #change-cover {
    cursor: pointer;
    position: absolute;
    right: 0;
    opacity: 0;
    overflow: hidden;
  }
  #input-brief > textarea{
    font-size: 1em;
    min-height: 2rem;
    font-family: sans-serif;
    padding: 0;
    border: none;
    outline: none;
    resize: none;
  }
  #preview-brief, #input-brief {
    width: 100%;
    background: #fff;
    min-height: 2rem;
    border: 1px solid #ddd;
    margin-top: .2rem;
  }
  .item-label.required:after {
    content: "*";
    color: #2F57DA;
  }
  .share-link-text {
    word-break: break-all;
  }
  .delete {
    color: #e64340;
    text-align: center;
    /*font-size: .3rem;*/
  }

</style>
