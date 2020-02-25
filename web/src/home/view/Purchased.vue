<template>
  <my-purchased :home="$route.params.home"></my-purchased>
</template>

<script>
  const MyPurchased = r => require.ensure([], () => r(require("../../my/view/Purchased")), 'my/purchased');
  export default {
    name: 'v-purchased',
    components: {MyPurchased}
  }
</script>

<style scoped>

</style>
