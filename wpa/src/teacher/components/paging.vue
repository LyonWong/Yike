<template>
  <div class="paging clearfix">
    <span v-if="page == 1">首页</span>
    <span v-if="page == 1">上一页</span>
    <router-link v-if="page != 1" :to="`${url}/1`">首页</router-link>
    <router-link v-if="page != 1" :to="`${url}/${page-1}`">上一页</router-link>
    <router-link class="item" v-for="t in total" :to="`${url}/${t}`" :key="t">{{t}}</router-link>
    <router-link v-if="page != total" :to="`${url}/${page+1}`">下一页</router-link>
    <router-link v-if="page != total" :to="`${url}/${total}`">尾页</router-link>
    <span v-if="page == total">下一页</span>
    <span v-if="page == total">尾页</span>
  </div>
</template>

<script>
    export default{
      name: 'v-paging',
      props: {
        page: {
          type: Number,
        },
        total: {
          type: Number,
        },
        url: {
          type: String,
        },
      },
      methods: {
      },
    };
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  .paging {
    padding: 4px 20px;
    font-size: 12px;
    background: #fff;
    text-align: center;
    >* {
      display: inline-block;
      vertical-align: middle;
    }
    span {
      color: #aaa;
    }
    a {
      color: #3C4A55;
    }
    .item {
      padding: 3px 6px;
      background: #fff;
      border-radius: 50%;
      -webkit-border-radius: 50%;
      &.active {
        color: #fff;
        background: #12b7f5;
      }
    }
  }
</style>
