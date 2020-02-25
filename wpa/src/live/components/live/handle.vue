<template>
  <div class="handle" v-if="show">
    <div class="handle-box">
      <div class="handle-title">请选择您要的操作！</div>
      <div class="handle-btn">
        <span @click="startComment" v-if="lessonInfo.step!='repose'">进入课后交流</span>
        <span @click="endLesson">结束授课</span>
        <span @click="close">关闭操作</span>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
  import { mapState } from 'vuex';
  import swal from 'sweetalert';
  import { removeStore } from '@lib/js/mUtils';

  export default
  {
    name: 'v-handle',
    props: {
      show: {
        type: Boolean,
      },
      callBack: {
        type: null,
      }
    },
    components: {
    },
    data() {
      return {
        refunding: false,
      };
    },
    computed: {
      ...mapState([
        'lessonInfo',
      ])
    },
    methods: {
      startComment() {
        // 确定进入学员交流
        swal({
          title: '',
          text: '学员和讲师在3天内可继续发言交流',
          confirmButtonText: '确定交流',
          showCancelButton:true,
          closeOnConfirm: false,
          cancelButtonText: '取消交流',
        }, ()=>{
          let opt = {
            lesson_sn:this.lessonInfo.sn,
          };
          this.$store.dispatch('fetchStartComment', opt).then(() => {
            removeStore(opt.lesson_sn);
            swal({
              title: '',
              text: '可以开始与学员交流!',
              confirmButtonText: "知道了"
            }, ()=>{
              window.location.reload();
            });
          }, () => {
            console.log('fail');
          });
        });
        // 关闭
        this.callBack(false);
      },
      endLesson() {
        swal({
            title: '',
            text: '课程结束，学员讲师均不可继续发言，课程回放开启',
            confirmButtonText: '确定结束',
            showCancelButton:true,
            closeOnConfirm: false,
            cancelButtonText: '取消结束',
        }, ()=>{
          let opt = {
            lesson_sn:this.lessonInfo.sn,
          };
          this.$store.dispatch('fetchEndLesson', opt).then(() => {
            removeStore(opt.lesson_sn);
            swal({
              title: '',
              text: '课程已结束!',
              confirmButtonText: "知道了",
            }, ()=>{
              window.location.reload();
            });
          }, () => {
            console.log('fail');
          });
        });
        // 关闭
        this.callBack(false);
      },
      close() {
        this.callBack(false);
      },
    },
  };
</script>
<style scoped lang="stylus" rel="stylesheet/stylus">
  @import "index.styl";

  .handle
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100% !important;
    background-color: rgba(0,0,0,0.5);
    z-index: 10;
    display: flex;
    flex-direction: column;
    justify-content: center;
    .handle-box
      position: relative;
      margin: 0 auto;
      width: 300px;
      background: #fff;
      border-radius: 6px;
      -webkit-border-radius: 6px;
      .handle-title
        padding: 20px 10px;
        text-align: center;
        border-bottom: 1px solid #D2D3D5;
        font-size: 20px;
        color: #575757;
      .handle-btn
        >*
          display: block;
          padding: 15px 10px;
          font-size: 16px;
          text-align: center;
          color: #12b7f5;
          cursor: pointer;
        span+span
          border-top: 1px solid #D2D3D5;
          &:last-child
            color: #aaa;

</style>
