<template>
  <div class="v-home-my">
    <div class="frm-user flex-col">
      <img class="logo" :src="app.linkToAssets('/img/logo/logo@3x.png')">
      <div class="userinfo flex-col">
        <div class="avatar">
          <img :src="user.avatar">
        </div>
        <div class="nickname font-medium" v-text="user.name"></div>
      </div>
    </div>
    <MyFields v-for="(list, index) of fields" :key="index" :list="list"></MyFields>
    <home-navigation :home="home"></home-navigation>
  </div>
</template>

<script>
  import HomeNavigation from "../components/Navigation";
  import Tabs from "../../components/Tabs";
  import MyFields from "../../user/components/MyFields";
  export default {
    name: 'home-my',
    components: {MyFields, Tabs, HomeNavigation},
    data() {
      return {
        home: this.$route.params.home,
        user: {},
        fields: [
          ['money', 'exchange'],
          ['feedback', 'aboutus']
        ]
      }
    },
    created() {
      this.api.get('/api/user-profile').then((res) => {
        this.user = res.data
        window.localStorage.setItem('usn', res.data.sn)
      }, this.api.onErrorSign)
    },
    methods: {
    }
  }
</script>

<style scoped>
  .v-home-my {
    margin-bottom: 1rem;
    background: #f0eff5;
  }
  .frm-user {
    width: 100%;
    height: 3rem;
    background: #565AD1;
    background-image: linear-gradient(50deg, transparent 20%, rgba(106, 213, 255, .1) 20%, rgba(106, 213, 255, .04) 55%, rgba(82, 87, 208, 0.01) 55%),
    linear-gradient(125deg, rgba(106, 213, 255, .1) 20%, transparent 20%, transparent 60%, rgba(106, 213, 255, .08) 60%, rgba(106, 213, 255, .08) 97%, transparent 97%),
    linear-gradient(-130deg, rgba(255, 255, 255, .8) 0%, rgba(255, 255, 255, .1) 80%, transparent 80%);
    /*linear-gradient(to right, transparent -10%, #fff 155%);*/
  }

  .logo {
    position: absolute;
    top: .3rem;
    left: .3rem;
    width: 1.8rem;
    height: .42rem;
  }

  .userinfo {
    height: 100%;
    padding-top: .7rem;
  }

  .avatar {
    width: 1.28rem;
    height: 1.28rem;
    border: .01rem solid #c1c1c1;
    border-radius: 50%;
  }

  .avatar > img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
  }

  .nickname {
    padding-top: .27rem;
    text-align: center;
    color: #fff;
    font-size: .32rem;
  }

</style>
