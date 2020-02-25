<template>
  <div class="c-board">
    <div class="board-head flex-row">
      <div class="board-tabs flex-row">
        <a class="icon-yike icon-course-selected" :href="`/lesson/detail/${sn}`"></a>
        <div class="board-tab font-bold">交流讨论</div>
      </div>
      <div class="btn board-write" @click="writeBoard">写留言</div>
    </div>
    <div class="board-body">
      <board-list ref="list" :sn="$route.query.sn" :type="$route.params.type"></board-list>
    </div>
  </div>
</template>

<script>
  import BoardList from "../components/board/List";
  export default {
    name: 'board',
    components: {BoardList},
    data() {
      return {
        sn: this.$route.query.sn || this.$route.params.sn
      }
    },
    created() {
      this.api.get('/api/lesson-profile', {
        sn: this.sn
      }).then((res) => {
        this.app.setTitle(res.data.title)
      })
      this.api.get('/api/individual-lesson', {
        sn: this.sn
      }).then(() => {}, this.api.onErrorSign)
    },
    methods: {
      writeBoard() {
        this.$refs.list.onPost()
      }
    }
  }
</script>

<style scoped>
  .board-head {
    justify-content: space-between;
    height: 1rem;
    line-height: 1rem;
    border-bottom: 1px solid #ccc;
    background: #fff;
  }
  .board-tabs {
    justify-content: flex-start;
  }
  .board-tabs .icon-yike {
    padding: .2rem .3rem;
    font-size: .4rem;
    color: #576B95;
  }
  .board-tab {
    font-size:.3rem;
  }
  .board-write {
    color: #576B95;
    font-size: .3rem;
    padding: .2rem .3rem;
  }
  .board-body {
    background: #fff;
    padding: 0 .3rem;
  }
</style>
