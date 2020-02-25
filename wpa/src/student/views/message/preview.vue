<template>
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
  </div>
</template>

<script>
  import {mapState} from 'vuex';

  export default
  {
    name: 'message-preview',
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
  .body-pc
    .show-image
      .img-box
        overflow-y: auto;
        img
          width: auto;
          min-width: 300px;
</style>
