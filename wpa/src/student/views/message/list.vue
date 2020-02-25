<template>
  <section class="dicuss-list">
    <ul>
      <li v-for="(item, index) in items">
        <div class="graphic">
          <div class="graphic-img">
            <img :src="item.user.avatar" alt="">
          </div>
          <div class="graphic-info">
            <div class="graphic-name">
              {{item.user.name}}
              <span class="name-specialty" v-if="item.user.label">
                {{item.user.label == 'self'?'自己':item.user.label == 'teacher'?'老师':'管理员'}}
              </span>
            </div>
            <div class="graphic-time">
              {{item.tms_create}}
            </div>
            <div class="graphic-message" v-if="item.message.text" v-html="textFormat(item.message.text)">
            </div>
            <div class="graphic-images clearfix" v-if="item.message.image && item.message.image.length">
              <span class="images preview" v-for="image in item.message.image"  v-if="item.message.image.length == 1" @click="showBigImage(image)">
                <img :src="`${image}!preview`" />
              </span>
              <span class="images" v-for="image in item.message.image"  v-if="item.message.image.length > 1" @click="showBigImage(image)">
                <img :src="`${image}!previews`" />
              </span>
            </div>
            <div class="graphic-file clearfix" v-if="item.message.file">
                <span class="file">
                <a :href="item.message.file" target="_blank">
                  <i class="iconfont icon-fujiawenjian"></i>
                  {{item.message.file | lastPathName }}
                </a>
                </span>
            </div>
            <div class="graphic-reply" @click="referReply(item.refer)" v-if="item.refer">
              {{item.refer.user.name}}:&nbsp;<span v-html="textFormat(item.refer.message.text)" v-if="item.refer.message.text"></span>
              <div class="refer-image clearfix" v-if="item.refer.message.image">
                <span class="image" v-for="img in item.refer.message.image" @click="showReferBigImage(img, $event)">
                  <img :src="`${img}!previews`" />
                </span>
              </div>
            </div>
            <div class="graphic-bottom clearfix">
              <span class="like pull-right" v-if="!item.likeLoading && !item.self">
                <span>
                  <i class="iconfont icon-dianzan" @click="startLike(item, index)"></i>
                  {{item.stats.liked}}
                </span>
              </span>
              <span class="like active pull-right" v-if="!item.likeLoading && item.self">
                <span>
                  <i class="iconfont icon-dianzan" @click="startLike(item, index)"></i>
                  {{item.stats.liked}}
                </span>
              </span>
              <span class="like-load pull-right" v-if="item.likeLoading">
                <span>
                  <i class="iconfont icon-dianzan"></i>
                  {{item.stats.liked}}
                </span>
                <img :src="loadImg" />
              </span>
              <span class="pull-right" @click="startReply(item)" v-if="!mark">
                <i class="iconfont icon-huifu"></i>
                {{item.stats.reply}}
              </span>
              <span class="pull-right" @click="startMarkReply(item)" v-if="mark">
                <i class="iconfont icon-huifu"></i>
                回复
              </span>
            </div>
            <div class="graphic-caidan">
              <i class="iconfont icon-caidan1" @click="toggleCaidanMenu(index)"></i>
              <div class="caidan-menu" v-if="item.bill">
                <span v-for="menu in item.menu">
                  <span @click="menuChange(index, item.cursor, 'chain')" v-if="menu == 'chain'">查看对话</span>
                  <span @click="menuChange(index, item.cursor, 'assoc')" v-if="menu == 'assoc'">相关留言</span>
                  <span @click="deleteMessage(index, item.cursor)" v-if="menu == 'delete'">删除留言</span>
                  <span @click="reportMessage(index, item)" v-if="menu == 'tipoff'">举报留言</span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
    <!--回复、留言框-->
    <dicuss-reply :name="name" :types="type" :genre="genre" :cursor="cursor" @callback="callback" v-if="showReply"></dicuss-reply>
    <!--loading-->
    <loading v-if="showLoading" :show="showLoading"></loading>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  import dicussReply from './reply.vue';
  import Loading from '@student/components/loading';

  export default{
    name: 'dicuss-list',
    props: {
      items: {
        type: Array,
      },
      mark: {
        type: String,
      },
    },
    components: {
      Loading,
      dicussReply,
    },
    data() {
      return {
        genre: 2,
        name: '',
        type: '',
        cursor: '',
        showReply: false,
        showReport: false,
        showLoading: false,
        curReportCursor: '',
        loadImg: `${process.env.ASSETS_HOST?process.env.ASSETS_HOST:'https://assets.sandbox.yike.fm/'}static/student/_static/student/img/lazy-loading.gif`,
      }
    },
    created() {
      this.type = this.types;
    },
    methods: {
      showBigImage(img){
        this.$emit('callback', img);
      },
      showReferBigImage(img, event){
        //event.preventDefault();
        event.stopPropagation();
        this.$emit('callback', img);
      },
      startReply(item) {
        this.$router.push({ name: 'messageDetail', params: { cursor: item.cursor } });
      },
      startMarkReply(item) {
        this.$emit('startReply', item);
      },
      startLike(item, index) {
        // 点赞操作
        this.$emit('startLike', index, true);
        // 开始
        this.$store.dispatch('fetchBoardLike', {cursor: item.cursor}).then((data) => {
          // 点赞成功
          this.$emit('endLike', index, data);
        }, (error) => {
          //
          swal({
            title: '错误提醒',
            text: error,
            confirmButtonText: "知道了"
          });
          // 点赞loading
          this.$emit('startLike', index, false);
          console.log('fail');
        });
      },
      toggleCaidanMenu(index) {
        if(this.mark){
          this.toggleHasMark(index);
        }else{
          this.toggleNoMark(index);
        }
      },
      toggleNoMark(index){
        let items = this.$parent.items ? this.$parent.items : this.$parent.$parent.items;
        // 重置
        this.resetMenu(items, index);
        // 开始
        let temp = items.splice(index, 1)[0];
        temp.bill = !temp.bill;
        items.splice(index, 0, temp);
      },
      toggleHasMark(index){
        let items = this.$parent.landlordItems ? this.$parent.landlordItems : this.$parent.$parent.landlordItems;
        // 重置
        this.resetMenu(items, index);
        // 开始
        let temp = items.splice(index, 1)[0];
        temp.bill = !temp.bill;
        items.splice(index, 0, temp);
      },
      resetMenu(items, _index){
        let index  = '';
        for(let item = 0; item < items.length-1; item++){
          if(items[item].bill){
            index = item;
            break;
          }
        }
        if(index === _index)return;
        // 开始
        if(index || index === 0){
          let temp = items.splice(index, 1)[0];
          temp.bill = false;
          items.splice(index, 0, temp);
        }
      },
      // 菜单改变
      menuChange(index, cursor, type) {
        this.$emit('menuChange', cursor, type);
        // 重设
        if(this.mark){
          this.toggleHasMark(index);
        }else{
          this.toggleNoMark(index);
        }
      },
      // 删除留言
      deleteMessage(index, cursor) {
        swal({
          title: '提醒',
          text: '确定要删除这条留言吗?',
          confirmButtonText: '删除',
          showCancelButton:true,
          closeOnConfirm: false,
          cancelButtonText: '取消',
        },()=>{
          // 开始删除留言
          this.showLoading = true;
          // 关闭窗口
          swal.close();
          this.$store.dispatch('fetchBoardDelete', {cursor:cursor}).then((data) => {
            let items = [];
            // 是否有标记
            if(this.mark){
              items = this.$parent.landlordItems ? this.$parent.landlordItems : this.$parent.$parent.landlordItems;
            }else{
              items = this.$parent.items ? this.$parent.items : this.$parent.$parent.items;
            }
            // 删除表达式
            items.splice(index, 1);
            //
            this.showLoading = false;
            console.log('success');
          }, (err) => {
            this.showLoading = false;
            //
            swal({
              title: '错误提醒',
              text: err.message,
              confirmButtonText: "知道了"
            });
          });
        });
      },
      // 举报留言
      reportMessage(index, item) {
        //
        this.$emit('startReport', item);
        // 重设
        if(this.mark){
          this.toggleHasMark(index);
        }else{
          this.toggleNoMark(index);
        }
      },
      referReply(refer) {
        try{
          if(refer.cursor){
            this.$router.push({ name: 'messageDetail', params: { cursor: refer.cursor } });
          }
        }catch(e){};
      },
      // 替换空格
      textFormat(value){
        return value.replace(/\n/g, '<br>');
      },
    }
  };
</script>

<style>
  .graphic-file .file{
    background: #eee;
  }
  .graphic-file  a {
    text-decoration: none;
    color: #333;
  }
  .graphic-file i {
    color: #333;
  }
</style>

<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";
  .iconfont
    px2px(font-size, 32px);
    color: #777;
  .dicuss-list
    background: transparent;
    ul
      margin: 0;
      padding: 0 30px;
      list-style: none;
      li
        position: relative;
        background: transparent;
        padding: 32px 0 16px;
        border-bottom: 1px solid #ddd;
        &:last-child
          border-bottom: 0 none;
        .graphic
          position: relative;
          padding-left: 70px;
        .graphic-img
          position: absolute;
          left: 0;
          top: 5px;
          width: 60px;
          height: 60px;
          overflow: hidden;
          img
            width: 60px;
            height: 60px;
            border-radius: 50%;
            -webkit-border-radius: 50%;
        .graphic-info
          position: relative;
          .graphic-name
            color: #666;
            px2px(font-size, 28px);
            .name-specialty
              display: inline-block;
              padding: 2px 8px 0;
              color: #fff;
              background: #4da9eb;
              border-radius: 10px;
              -webkit-border-radius: 10px;
              px2px(font-size, 24px);
          .graphic-time
            padding-top: 5px;
            color: #aaa;
            px2px(font-size, 24px);
          .graphic-message
            padding: 10px 0;
            word-break: break-all;
            px2px(font-size, 32px);
          .graphic-reply
            margin: 18px 0 18px;
            padding: 20px 15px;
            color: #666;
            background: #F1F3F7;
            .refer-image
              padding-top: 15px;
              .image
                position: relative;
                display: flex;
                display: -webkit-flex;
                float: left;
                margin-right: 15px;
                width: 160px;
                height: 160px;
                border: 1px solid #e6eaf2;
                align-items: center;
                justify-content: center;
                -webkit-align-items: center;
                -webkit-justify-content: center;
                px2px(font-size, 32px);
                &:last-child
                  margin: 0;
                img
                  max-width: 100%;
                  max-height: 100%;
          .graphic-caidan
            position: absolute;
            right: 0;
            top: 0;
            .iconfont
              position: relative;
              color: #999;
              z-index: 1;
              px2px(font-size, 34px);
            .caidan-menu
              position: absolute;
              right: 0;
              width: 200px;
              border: 1px solid #ddd;
              background: #fff;
              z-index: 2;
              -moz-box-shadow: 0px 3px 30px #C7C7C7;
              -webkit-box-shadow: 0px 3px 30px #C7C7C7;
              box-shadow: 0px 3px 30px #C7C7C7;
              px2px(top, 60px);
              >*
                display: block;
                padding: 20px 0;
                color: #333;
                text-align: center;
                &+span
                  border-top: 1px solid #ddd;
          .graphic-images
            padding: 10px 0;
            .images
              position: relative;
              display: flex;
              display: -webkit-flex;
              float: left;
              margin-right: 15px;
              width: 160px;
              height: 160px;
              border: 1px solid #e6eaf2;
              align-items: center;
              justify-content: center;
              -webkit-align-items: center;
              -webkit-justify-content: center;
              px2px(font-size, 32px);
              &.preview
                width: 240px;
                height: 240px;
              &:last-child
                margin: 0;
              img
                max-width: 100%;
                max-height: 100%;
          .graphic-bottom
            padding-top: 6px;
            px2px(font-size, 30px);
            >
              display: inline-block;
              color: #666;
              &.like
                width: 160px;
                text-align: right;
                &.active
                  color: #4da9eb;
            .iconfont
              vertical-align: middle;
              px2px(font-size, 36px);
            .icon-dianzan
              px2px(font-size, 34px);
            .like-load
              position: relative;
              width: 160px;
              overflow: hidden;
              >span
                position: relative;
                visibility: hidden;
              img
                position: absolute;
                left: 0;
                top: 0;
                transform: scale(1.2) translate(50px,-20px);
                -webkit-transform: scale(1.2) translate(50px,-20px);
  .body-pc
    .dicuss-list
      ul
        li
          .graphic-info
            .graphic-images
              .images
                width: 100px;
                height: 100px;
            .graphic-caidan
              .caidan-menu
                width: 100px;
                -moz-box-shadow: 0px 3px 15px #C7C7C7;
                -webkit-box-shadow: 0px 3px 15px #C7C7C7;
                box-shadow: 0px 3px 15px #C7C7C7;
                >*
                  padding: 15px 0;
            .graphic-file
              a
                display: inline-block;
                text-decoration none !important;
                background: #eee;
                color: #333 !important;
                padding: 5px 10px;
                border-radius: 5px;
                i
                  color: #777;
                  ps2px(font-size: 32px);
            .graphic-name
              .name-specialty
                padding: 1px 3px;
                font-size: 14px;
                border-radius: 5px;
                -webkit-border-radius: 5px;
            .graphic-reply
              .refer-image
                .image
                  width: 100px;
                  height: 100px;
          .graphic-bottom
            .like-load
              img
                transform: scale(0.5) translate(0px,-80px);
                -webkit-transform: scale(0.5) translate(0px,-80px);
</style>
