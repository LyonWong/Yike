<template>
  <div class="c-invite">
    <div class="content flex-col">
      <div class="frm-canvas flex-col" id="frm-canvas">
        <canvas id="canvas" v-show="!cover"></canvas>
        <img :src="cover" id="cover" v-show="cover">
        <div id="tips" @click="showNotice">
          <i class="icon-yike icon-information"></i>
          <span>分享规则</span>
        </div>
      </div>
      <div class="frm-notice flex-row">
        <div class="box-notice">
          <span>长按保存图片，邀请好友可获￥{{benefits.commission}}鼓励金</span>
          <a :href="`${studentDomain}/?v=1#course/rank/${this.$route.query.sn}`">查看邀请榜</a>
        </div>
      </div>
      <div class="frm-picker flex-row">
        <div class="box-picker" v-for="(card, idx) in cards" :key="idx">
          <div class="btn-picker" :class="{active: active === idx}" @click="drawCard(idx)">
            <img :src="card.icon"/>
            <div class="btn-on flex-col">
              <i class="icon-yike icon-select"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <modal-notice width="5.1rem" title="佣金规则" confirm="知道了" :isShow="isShowNotice" v-on:close="showNotice(false)">
      <li>每成功邀请1名好友报名并听课，可获得￥{{benefits.commission}}佣金</li>
      <li>佣金将在好友听课（未退款）72小时后结算，可通过【账户资金】提现至微信零钱</li>
    </modal-notice>
  </div>
</template>

<script>
  import ModalNotice from '@/components/modal/Notice'

  export default {
    name: 'invite',
    components: {ModalNotice},
    data() {
      return {
        isShowNotice: false,
        cards: [],
        benefits: {},
        active: 0,
        cover: '',
        drawing: 0,
        quality: 0.7
      }
    },
    computed: {
      studentDomain: () => {
        return `${window.location.protocol}/student.${window.location.host}`
      }
    },
    created() {
    },
    mounted() {
      this.api.get('/api/promote-invitation', {
        // 本地环境
        sn: this.$route.query.sn,
        origin: this.$route.query.origin
      })
        .then(res => {
          if (res.error === '0') {
            this.cards = res.data.cards
            this.benefits = res.data.benefits
            this.drawCard(0);
          }
        }, this.api.onErrorSign)
      window.onresize = this.canvasResize
    },
    methods: {
      showNotice(force) {
        this.isShowNotice = force || !this.isShowNotice
      },
      drawCard(idx) {
        this.drawing = 3
        let the = this
        this.active = idx
        let card = this.cards[idx]
        let canvas = document.getElementById('canvas');
        let context = canvas.getContext('2d');
        canvas.width = card.base.size[0]
        canvas.height = card.base.size[1]
        context.save();
        let img = new Image();
        img.crossOrigin = 'Anonymous';
        img.src = card.base.src
        img.onload = function () {
          // base
          context.drawImage(img, 0, 0, card.base.size[0], card.base.size[1]);
          // desc
          let desc = card.desc || []
          desc.forEach((v) => {
            context.font = v.font || '10px sans-serif'
            context.fillStyle = v.style || '#000'
            context.textAlign = v.align || 'start'
            context.textBaseline = v.bsline || 'alphabetic'
            if (v.rotate) {
              context.rotate(parseFloat(v.rotate))
              context.fillText(v.text, v.offset[0], v.offset[1], v.width)
              context.rotate(-parseFloat(v.rotate))
            } else {
              context.fillText(v.text, v.offset[0], v.offset[1], v.width)
            }
          })
          // avatar
          if (card.avatar) {
            let avatar = card.avatar
            avatar.img = new Image();
            avatar.img.crossOrigin = "Anonymous";
            avatar.img.src = avatar.src
            avatar.round = avatar.round || (avatar.size[0] + avatar.size[1]) / 4 // 默认圆角
            avatar.img.onload = () => {
              context.arc(avatar.offset[0] + avatar.size[0] / 2, avatar.offset[1] + avatar.size[1] / 2, avatar.round, 0, 2 * Math.PI)
              context.clip()
              context.drawImage(avatar.img, avatar.offset[0], avatar.offset[1], avatar.size[0], avatar.size[1])
              context.restore()
              the.drawing--
            }
          } else {
            the.drawing--
          }
          // cover
          if (card.cover) {
            let cover = card.cover
            cover.img = new Image();
            cover.img.crossOrigin = "Anonymous";
            cover.img.src = cover.src
            cover.img.onload = () => {
              context.drawImage(cover.img, cover.offset[0], cover.offset[1], cover.size[0], cover.size[1])
              context.restore()
              the.drawing--
            }
          } else {
            the.drawing--
          }
          // qrcode
          let qrcode = card.qrcode
          qrcode.img = new Image();
          qrcode.img.crossOrigin = "Anonymous";
          qrcode.img.src = card.qrcode.src;
          qrcode.img.onload = () => {
            context.restore();
            context.drawImage(qrcode.img, qrcode.offset[0], qrcode.offset[1], qrcode.size[0], qrcode.size[1]);
            the.drawing--
            this.quality = card.base.quality || this.quality
            this.cover = canvas.toDataURL("image/jpeg",  this.quality)
          }
        }
        this.canvasResize()
      },
      canvasResize() {
        let frm = document.getElementById('frm-canvas')
        let cas = document.getElementById('canvas')
        let cover = document.getElementById('cover')
        let pw = cas.style.width
        let ph = cas.style.height
        if (frm.clientWidth / cas.clientWidth < frm.clientHeight / cas.clientHeight) {
          cas.style.width = frm.clientWidth * 0.8 + 'px'
          cover.style.width = frm.clientWidth * 0.8 + 'px'
        } else {
          cas.style.height = frm.clientHeight * 0.9 + 'px';
          cover.style.height = frm.clientHeight * 0.9 + 'px'
        }
        if (pw !== cas.style.width || ph !== cas.style.height) {
          this.canvasResize()
        }
      }
    },
    watch: {
      'drawing': function(v) {
        if (v <= 1) {
          this.cover = document.getElementById('canvas').toDataURL("image/jpeg", this.quality)
        }
      }
    }
  }
</script>

<style scoped>

  .c-invite {
    height: 100%;
    width: 100%;
  }

  .content {
    justify-content: flex-end;
    height: 100%;
    width: 100%;
    background: rgba(51,51,51, 0.9);
  }

  .frm-canvas {
    position: relative;
    flex-grow: 1;
    width: 100%;
    top: .5rem;
    overflow: hidden;
    border-radius: .12rem;
  }

  #canvas {
    border-radius: .12rem;
  }

  #cover {
    border-radius: .12rem;
  }

  #tips {
    display: flex;
    align-items: center;
    position: absolute;
    top: 8%;
    right: 0;
    width: 1.5rem;
    height: .48rem;
    padding-left: .12rem;
    background: linear-gradient(45grad, rgba(151, 169, 219, 0.9), rgba(128, 150, 229, 0.9));
    border-radius: .24rem 0 0 .24rem;
    color: #fff;
    font-size: .24rem !important;
    letter-spacing: .01rem;
    box-shadow: 0 .02rem .05rem rgba(0, 0, 0, 0.25);
  }

  #tips > span {
    margin-left: .12rem;
  }

  #tips > i {
    font-size: .24rem;
  }

  .frm-notice {
    height: 1.26rem;
    width: 100%;
  }

  .box-notice {
    font-size: .24rem;
    height: .36rem;
    letter-spacing: 1px;
    color: #fff;
  }

  .box-notice > span {
    font-weight: bold;
  }

  .box-notice > a {
    margin-left: .24rem;
    width: 1.36rem;
    padding: .02rem .1rem;
    border-radius: .04rem;
    background: #5774ed;
    color: #fff;
    text-decoration: none;
  }

  .frm-picker {
    display: -webkit-box;
    -webkit-box-align: center;
    -webkit-box-pack: center;
    overflow-x: scroll;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    width: 100%;
    height: 2rem;
    background: #fff;
  }

  .box-picker {
    padding: .15rem;
  }

  .box-picker:first-child {
    margin-left: .15rem;
  }

  .btn-picker {
    position: relative;
    height: 1.28rem;
    width: 1.28rem;
    border-radius: .08rem;
  }

  .btn-picker > img {
    width: 100%;
    height: 100%;
    border-radius: .08rem;
  }

  .btn-on {
    display: none;
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    border-radius: .08rem;
  }

  .btn-on > i {
    font-size: .6rem;
    color: #fff;
  }

  .active .btn-on {
    display: flex;
  }
</style>
