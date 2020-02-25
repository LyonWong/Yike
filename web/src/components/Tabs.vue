<template>
  <div class="c-tabs btn flex-row">
    <slot name="pre-addon"></slot>
    <div class="tab flex-item flex-row click" v-for="item in items" :key="item.key" @click="tab(item.key)"
         :class="{active: item.key === active}">
      <span class="flex-col font-medium">{{item.name}}</span>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'tabs',
    props: ['items', 'active'],
    data() {
      return {}
    },
    created() {
    },
    methods: {
      tab(key) {
        this.$emit('switch', key)
      }
    }
  }
</script>

<style scoped>
  .c-tabs {
    height: 1rem;
    width: 100%;
    background: #fff;
    border-bottom: 1px #DDDDDD solid;
  }

  .tab {
    height: 100%;
  }

  .tab > span {
    box-sizing: border-box;
    height: 100%;
    font-size: .36rem;
    color: #666;
    border-bottom: .04rem solid transparent;
  }

  .tab.active > span {
    color: #2E57DA;
    border-bottom: .04rem solid #2E57DA;
  }
</style>
