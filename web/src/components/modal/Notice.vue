<template>
  <div class="c-modal-notice flex-col" v-if="isShow">
    <div class="mask" @click="close"></div>
    <div class="frm-modal" :style="{width: width}">
      <div class="profile flex-col">
        <slot name="title" class="title">{{title}}</slot>
      </div>
      <div class="body">
        <div class="frm-notice">
          <div class="box-notice">
            <ul class="modal-list">
              <slot></slot>
            </ul>
          </div>
        </div>
        <div class="frm-button flex-row">
          <div class="box-button">
            <button class="btn btn-primary" @click="close">{{confirm}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'modal-notice',
    props: ['width', 'title', 'confirm', 'isShow'],
    methods: {
      close: function () {
        this.$emit('close')
      }
    }
  }
</script>

<style scoped>
  .c-modal-notice {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
  }

  .frm-modal {
    z-index: 999;
    width: 70%;
  }

  .frm-button {
  }

  .profile {
    height: 1rem;
    color: #222;
    font-size: .3rem;
    font-weight: bold;
    letter-spacing: .05rem;
    background: #e9edff;
    border-radius: .12rem .12rem 0 0;
  }

  .body {
    flex-grow: 1;
    background: #fff;
    color: #666;
    font-size: .24rem;
    border-radius: 0 0 .12rem .12rem;
  }

  ul.modal-list {
    margin: 0;
    list-style-type: none;
    padding: .1rem .3rem;
  }

  .modal-list > li {
    display: flex;
    margin: .21rem 0;
  }

  .modal-list > li:before {
    padding: 0 .1rem;
    content: "◆";
    color: #5774ed;
  }

  .box-button {
    padding: .36rem;
  }

  .btn-primary {
    height: .6rem;
    padding: 0 .3rem 0 .4rem;
    border-radius: .3rem;
    letter-spacing: .03rem;
    font-size: .3rem;
    text-align: center;
    color: #fff;
    background: #5774ed;
  }

</style>
