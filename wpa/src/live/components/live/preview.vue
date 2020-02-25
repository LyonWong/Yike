<template>
  <!-- popular -->
  <!-- show-image -->
  <div class="preview">
    <div id="previewBox" class="img-box" @click="hideImg">
      <img :src="preview" />
    </div>
    <div class="origin-img">
      <button @click="viewOriginImg" v-if="!originShow">
        查看原图
      </button>
      <button @click="viewPreviewImg" v-if="originShow">
        恢复预览
      </button>
    </div>
    <div class="left-slide" @click="prevImg">
      <i class="iconfont icon-open-left"></i>
    </div>
    <div class="right-slide" @click="nextImg">
      <i class="iconfont icon-open-right"></i>
    </div>
  </div>
</template>

<script>
  import {mapState} from 'vuex';

  export default
  {
    name: 'v-preview',
    components: {
    },
    props: {
      preview: {
        type: String
      }
    },
    data(){
      return {
        originShow: false,
      }
    },
    computed: {
    },
    mounted(){
      let previewBox = document.getElementById('previewBox');
      let previewBoxImg = previewBox.querySelector('img');
      // 加载完
      previewBoxImg.onload = ()=>{
        if(document.body.clientHeight <= previewBoxImg.offsetHeight){
          previewBox.className = 'img-box style2';
        }
      };
    },
    methods: {
      hideImg(){
        // 恢复样式
        document.getElementById('previewBox').className = 'img-box';
        // 切换按钮
        this.viewPreviewImg();
        this.$parent.previewImg = '';
      },
      viewOriginImg() {
        let previewBoxImg = document.getElementById('previewBox').querySelector('img');
        previewBoxImg.style.width = 'auto';
        // 切换按钮
        this.originShow = true;
      },
      viewPreviewImg(){
        let previewBox = document.getElementById('previewBox');
        let previewBoxImg = previewBox.querySelector('img');
        previewBoxImg.style.width = '50%';
        // 切换按钮
        this.originShow = false;
      },
      prevImg() {
        // 恢复样式
        document.getElementById('previewBox').className = 'img-box';
        // 切换按钮
        this.viewPreviewImg();
        this.$emit('prevPreviewImg');
      },
      nextImg() {
        // 恢复样式
        document.getElementById('previewBox').className = 'img-box';
        // 切换按钮
        this.viewPreviewImg();
        this.$emit('nextPreviewImg');
      },
    }
  };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .preview
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    z-index: 12;
    .img-box
      display: flex;
      height: 100%;
      justify-content: center;
      -webkit-justify-content: center;
      align-items:center;
      -webkit-align-items:center;
      overflow: auto;
      &.style2
        display: block;
        text-align: center;
      img
        width: 50%;
        min-width: 50%;
        max-width: 100%;
    .origin-img
      position: absolute;
      left: 0;
      right: 0;
      bottom: 30px;
      text-align: center;
      button
        padding: 6px 15px;
        color: #fff;
        border: 0 none;
        background: #000;
        font-size: 18px;
        cursor: pointer;
        border-radius: 10px;
        -webkit-border-radius: 10px;
    .left-slide, .right-slide
      position: absolute;
      top: 50%;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background-color: rgba(0,0,0,.5);
      cursor: pointer;
      font-size: 0;
      text-align: center;
      .iconfont
        color: #fff;
        font-size: 48px;
    .left-slide
      left: 100px;
      .iconfont
        padding-right: 2px;
    .right-slide
      right: 100px;
  .is-pc
    .show-image
      .img-box
        overflow-y: auto;
        img
          width: auto;
          min-width: 300px;
</style>
