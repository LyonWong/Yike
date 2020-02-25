<template>
  <block :title="data.title">
    <!--<router-link slot="more" :to="`/lesson/search?title=${data.title}&tag=${data.tag}`">-->
    <span  slot="more" @click="go" class="font-medium">查看更多</span>
    <!--<i class="icon-yike icon-arrow-r"></i>-->
    <!--</router-link>-->
    <course-cell :profile="profile" v-for="(profile,index) in profiles" :key="index"></course-cell>
    <slot name="another"></slot>
  </block>
</template>

<script>
  import Block from "../../../components/Block";
  import CourseCell from "../unit/CourseCell";

  export default {
    name: 'lesson-home-block',
    components: {Block, CourseCell},
    props: ['data'],
    data() {
      return {
        profiles: []
      }
    },
    created() {
      if (this.data.list.length) {
        for (let item of this.data.list) {
          this.profiles.push(item.profile)
        }
      }
    },
    computed: {},
    methods: {
      go() {
        // this.$router.push(`/lesson/search?title=${this.data.title}&tag=${this.data.tag}`)
        location.href = `/lesson/list?title=${this.data.title}&tag=${this.data.tag}`
      }
    }
  }
</script>

<style scoped>
  span {
    font-size: .27rem;
  }
</style>
