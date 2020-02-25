<template>
  <div class="c-prepare-editor">
    <div class="edit-head">
      NO.{{editing.idx+1}}
    </div>
    <div class="edit-body">
      <div class="edit-image"></div>
      <div class="edit-audio"></div>
      <div class="edit-video"></div>
      <div class="edit-text">
        {{editing}}
        <textarea v-model="content_.text" v-on:input="onInput"></textarea>
      </div>
    </div>
    <div class="edit-foot">
      {{idx}}
    </div>
  </div>
</template>

<script>
  export default {
    name: 'prepare-editor',
    props: ['editing'],
    data() {
      return {
        content_: this.editing.content,
        idx: this.editing.idx
      }
    },
    created() {
      console.log('created prepare-editor', this.editing)
    },
    mounted() {},
    methods: {
      save() {},
      onInput() {
        // todo set local storage
        // emit content
        console.log('onInput', this.content_)
        this.content_.src = 'abc'
        this.$emit('edit', {
          'idx': this.editing.idx,
          'edited': true,
          'content': this.content_
        })
      }
    },
    watch: {
    }
  }
</script>

<style scoped>

</style>
