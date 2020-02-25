<template>
  <div class="c-my-fields">
    <ul>
      <li class="flex-row" v-for="(item,index) in list" :key="index" @click="turnTo(item)">
        <div class="flex-row">
          <i :class="dict[item].icon" class="icon icon-yike"></i>
          <span class="font-medium" v-text="dict[item].name"></span>
        </div>
        <i class="icon-yike icon-arrow-r"></i>
      </li>
    </ul>
  </div>
</template>

<script>

  export default {
    name: "my-fields",
    props: ['list', 'custom'],
    data() {
      return {
        dict: {
          'money': {name: '账户资金', icon: 'icon-money', path: '/user/money'},
          'exchange': {name: '兑换卡券', icon: 'icon-discount', path: this.app.linkToStudent('/?v=2#/user/money/coupon')},
          'apply': {name: '成为讲师', icon: 'icon-enrolled-teacher', path: '/user/apply'},
          'posts': {name: '我的文集', icon: 'icon-enrolled-course', path: '/create/posts'},
          'feedback': {name: '建议与反馈', icon: 'icon-feedback', path: this.app.linkToStudent('/?v=2#/user/advise')},
          'aboutus': {name: '关于我们', icon: 'icon-aboutus', path: '/user/about-us'}
        }
      }
    },
    created() {
      // 若有自定义内容，覆盖默认dict
      let custom = this.custom || {}
      for (let item in custom) {
        for (let key of ['name', 'icon', 'path']) {
          if (custom[item][key]) {
            this.dict[item][key] = custom[item][key]
          }
        }
      }
    },
    methods: {
      turnTo(item) {
        window.location.href = this.dict[item].path
      }
    }
  }
</script>

<style scoped>
  ul {
    margin: .4rem 0;
    padding: 0 .37rem 0 .32rem;
    list-style: none;
    border-top: 0.01rem solid #D9D9D9;
    border-bottom: 0.01rem solid #D9D9D9;
    background: white;
  }

  li {
    border-bottom: 0.01rem solid #D9D9D9;
    padding: .165rem 0;
    justify-content: space-between;
    cursor: pointer;
  }

  li:last-child {
    border-bottom: 0;
    padding: .17rem 0;
  }

  .icon-arrow-r {
    color: #808080;
  }

  .icon-enrolled-course {
    background: linear-gradient(to right, #2C51F2, #8585FE);
    font-size: .5rem;
  }

  .icon-enrolled-teacher {
    background: linear-gradient(to right, #B3A2FF, #E1B9FF);
    font-size: .5rem;
  }

  .icon-money {
    background: linear-gradient(to right, #FF9E04, #FFD027);
    font-size: .5rem;
  }

  .icon-discount {
    background: linear-gradient(to top, #FF7CA4, #FEACB2 60%);
    font-size: .5rem;
  }

  .icon-person {
    background: linear-gradient(to right, #2C51F2, #8585FE);
    font-size: .5rem;
  }

  .icon-feedback {
    background: linear-gradient(to right, #42C25F, #54E269);
    font-size: .5rem;
  }

  .icon-enroll-teacher {
    background: linear-gradient(to right, #8C61F7, #AD99F7);
    font-size: .5rem;
  }

  .icon-aboutus {
    background: linear-gradient(to right, #659AFA, #71C2FF);
    font-size: .5rem;
  }

  .icon {
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    padding-right: .36rem;
  }

  .icon + span {
    font-size: .32rem;
    color: #0D0D0D;
    padding-bottom: .03rem;
  }

</style>
