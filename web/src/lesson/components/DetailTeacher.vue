<template>
  <div class="c-detail-teacher" v-if="teacher">
    <div class="flex-row" >
      <div class="flex-col avatar">
        <img :src="teacher.avatar"/>
      </div>
      <div class="flex-col flex-item" @click="fold=!fold">
        <div class="name font-medium">{{teacher.name}}</div>
        <u-cuttle class="about text-desc font-medium" upper-height="2rem" v-if="0">
          <div>one</div>
          <div>I'm a teacher.oneoneone</div>
          <div>I'm a teacher.oneoneone</div>
          <div>I'm a teacher.oneoneone</div>
          <div>I'm a teacher.oneoneone</div>
          <div>I'm a teacher.oneoneone</div>
        </u-cuttle>
        <div class="about text-desc font-medium" :class="{fold: fold}" v-html="markdown(teacher.about)"></div>
        <div class="shield" v-show="0 && fold"></div>
      </div>
      <!--<div :class="{followed:teacher.followed}" class="follow font-24" @click="follow">{{teacher.followed?'已关注':'+ 关注'}}</div>-->
    </div>
  </div>
</template>

<script>
  import UCuttle from "../../components/unit/Cuttle";
  const markdown = require('markdown-it')({html: true, breaks: true})
  export default {
    name: 'lesson-detail-teacher',
    components: {UCuttle},
    props: ['tusn'],
    data() {
      return {
        teacher: null,
        fold: true
      }
    },
    created() {
      this.api.get('/api/teacher-datum', {
        usn: this.tusn
      }).then( (res) => {
        this.teacher = res.data
      })
    },
    methods: {
      markdown(text) {
        return markdown.render(text || '');
      },
      follow() {
        this.api.post('/api/follow-teacher', {
          usn: this.tusn
        }).then( (res) => {
          this.teacher.followed = res.data.isFollow
        }, this.api.onErrorSign)
      }
    }
  }
</script>

<style scoped>
  .c-detail-teacher {
    border-bottom: 1px #DDDDDD solid;
    padding: .1rem 0 .4rem 0;
  }

  .flex-row {
    justify-content: flex-start;
    align-items: flex-start;
  }

  .flex-col {
    /*justify-content: flex-start;*/
    /*align-items: flex-start;*/
  }

  .flex-item {
    padding: 0 .21rem;
    justify-content: flex-start;
    align-items: flex-start;
    position: relative;
  }

  .avatar {
    width: .72rem;
    height: .72rem;
  }
  img {
    width: .72rem;
    height: .72rem;
    border-radius: 50%;
  }

  .name {
    font-size: .3rem;
    color: #0D0D0D;
  }

  .about {
    color: #808080;
    line-height: .36rem;
    text-align: justify;
    word-break: break-all;
  }
  .about.fold {
    max-height: 3rem;
    overflow: hidden;
  }
  .shield {
    width: 100%;
    height: 1rem;
    background: linear-gradient(to bottom, transparent, #fff);
    position: absolute;
    bottom: 0;
  }

  .follow {
    width: 1.2rem;
    height: .48rem;
    line-height: .48rem;
    border: 1px solid #2A4EC4;
    border-radius: .24rem;
    color: #2A4EC4;
    text-align: center;
  }

  .followed {
    width: 1.2rem;
    height: .48rem;
    line-height: .48rem;
    border: 1px solid #ccc;
    border-radius: .24rem;
    color: #ccc;
    text-align: center;
  }
</style>
