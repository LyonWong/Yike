<template>
  <div class="teacher-info" v-if="teacher">
    <loading :show="showLoading"></loading>
    <div class="t-tit" @click="goToTeacher(teacher.sn)">
      <span>讲师</span>
      <span class="focus" :class="{'is-focus':isFollow}" @click="focusTeacher(teacher.sn, $event)">
        {{isFollow?'已关注':'+关注'}}
      </span>
    </div>
    <div class="t-con clearfix">
      <div class="t-img">
        <img :src="teacher.avatar" />
      </div>
      <div class="t-text">
        <div v-text="teacher.name"></div>
        <div class="t-word" :class="{'word-fold':wordFold}">
          <div class="t-word-con break-word" v-html="textFormat(teacher.about)" ref="t-word" :class="{'fold':(!showWord && wordFold)}"></div>
          <!--<span class="unfold" @click="toggleFold" v-if="wordFold">-->
            <!--<i class="iconfont icon-chevron-down" v-if="!showWord"></i>-->
            <!--<i class="iconfont icon-chevron-up" v-if="showWord"></i>-->
            <!--&lt;!&ndash;{{showWord?'收起':'展开'}}&ndash;&gt;-->
          <!--</span>-->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Loading from '@student/components/loading';

  export default{
    name: 'teacher-info',
    components: {
      Loading,
    },
    props: {
      teacher: {
        type: Object
      }
    },
    data() {
      return {
        showLoading: false,
        showWord: true,
        wordFold: false,
        isFollow: this.teacher.isFollow,
      };
    },
    mounted() {
      this.wordFold = (this.$refs['t-word'].offsetHeight>115?true:false);
    },
    methods: {
      goToTeacher(series_sn) {
        this.$router.push({ name: 'seriesTeacherSingle', params: { series_sn: series_sn }});
      },
      textFormat(value){
        if(value){
          return value.replace(/\n/g, '<br>');
        }else{
          return '';
        }

      },
      toggleFold() {
        this.showWord = !this.showWord;
      },
      focusTeacher(sn, event) {
        // 阻止事件冒泡
        event.stopPropagation();
        // 开始
        this.showLoading = true;
        this.$store.dispatch('fetchTeacherFollow', {tusn: sn}).then((data) => {
          try{
            this.isFollow = data.isFollow;
          }catch(e){};
          // 结束loading
          this.showLoading = false;
        }, (error) => {
          // 结束loading
          this.showLoading = false;
          //
          swal({
            title: '错误提醒',
            text: error,
            confirmButtonText: "知道了"
          });
          console.log('fail');
        });
      },
    },
  };

</script>

<style lang="stylus" rel="stylesheet/stylus">
  @import '~@lib/css/index.styl';

  .teacher-info {
    background: #fff;
    px2px(font-size, 36px);

    .t-tit {
      position: relative;
      margin-bottom: 30px;
      padding: 30px;
      border-bottom: 1px solid #d9d9d9;

      :first-child {
        color: #333;
        px2px(font-size, 36px);
      }
      .focus {
        position: absolute;
        padding: 12px 18px;
        background: #f7f9fc;
        border-radius: 50px;
        -webkit-border-radius: 50px;
        color: #4DA9EB;
        px2px(top, 20px);
        px2px(right, 30px);
        px2px(font-size, 32px);
        &.is-focus {
          color: #999;
        }
      }
    }

    .t-con {
      padding: 0 30px 15px;
      overflow: hidden;

      >* {
        float: left;
      }
      .t-img {
        margin-right: 40px;

        img{
          width: 80px;
          height: 80px;
          border-radius: 50%;
          -webkit-border-radius: 50%;
        }
      }
      .t-text {
        width: 550px;
        >:first-child {
          color: #333;
          px2px(font-size, 36px);
        }
        >:last-child {
          color: #666;
          margin: 10px 0 10px;
          px2px(font-size, 32px);
        }
        .word-fold {
          padding-bottom: 35px;
        }
      }
    }
    .t-word {
      position: relative;
      .unfold {
        px2px(left, -55px);
        px2px(bottom, -15px);
      }
      .t-word-con {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: initial;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #666;
        px2px(font-size, 30px);
        px2px(line-height, 38px);
        &.fold{
          -webkit-line-clamp: 3;
        }
      }
    }
  }
  /*.teacher-info + .lists {
    border-top: 1px solid #d9d9d9;
  }*/
  .body-pc {
    .teacher-info {
      .t-tit {
        .focus {
          top: 20px;
          right: 30px;
        }
      }
      .t-con {
        .t-text {
          width: 440px;
        }
      }
    }
  }
</style>
