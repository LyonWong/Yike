<template>
  <div class="c-navigation flex-row">
    <a class="nav btn flex-col" v-for="(nav, idx) in navs"
       :key="idx"
       :href="nav.path"
       :class="{active: nav.path === $route.path}">
      <i class="icon-yike" :class="[nav.path === $route.path ? `icon-${nav.icon}-selected` : `icon-${nav.icon}`]"></i>
      <span class="nav-name font-ragular">{{nav.name}}</span>
    </a>
  </div>
</template>

<script>
  export default {
    name: 'navigation',
    data() {
      return {
        navs: [
          {
            'icon': 'home',
            'name': '首页',
            'path': '/lesson/home'
          },
          {
            'icon': 'course',
            'name': '已购',
            'path': '/my/lesson'
          },
          {
            'icon': 'my',
            'name': '我的',
            'path': '/my/home'
          }
        ]
      }
    },
    created() {
    },
    computed: {
    }
  }
</script>

<style scoped>
  .c-navigation {
    position: fixed;
    bottom: 0;
    height: 1rem;
    width: 7.5rem;
    background: #fff;
    box-shadow: 0 0 10px #ddd;
  }
  .nav {
    font-size: .2rem;
    flex-grow: 1;
    color: #666;
    height: 100%
  }
  .nav i {
    font-size: .4rem;
    color: #2A4EC4
  }
  .nav.active i {
    color: #2A4EC4
  }

</style>
