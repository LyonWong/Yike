<template>
  <block class="c-detail-invite" title="邀请达人榜" @click="go">
    <a slot="more" class="flex-row" :href="app.linkToStudent(`/?v=2#/course/rank/${target}`)">
      <img class="avatar" v-for="(item,index) in tops" :key="index" :src="item.avatar"/>
      <i class="icon-yike icon-arrow-r"></i>
    </a>
  </block>
</template>

<script>
  import Block from "../../components/Block";
  export default {
    name: 'lesson-detail-invite',
    components: {Block},
    props: ['target'],
    data() {
      return {
        'tops': []
      }
    },
    created() {
      this.api.get('/api/promote-rank-slice', {
        target: this.target,
        limit: 3
      }).then((res) => {
        this.tops = res.data
      }).catch(() => {
      })
    },
    methods: {
      go() {
        location.href = this.app.linkToStudent(`/?v=2#course/rank/${this.target}`)
      }
    }
  }
</script>

<style scoped>
  .c-detail-invite {
    margin-top: .3rem;
  }
  .avatar {
    width: .5rem;
    height: .5rem;
    border-radius: .5rem;
    margin-left: .1rem;
  }

</style>
