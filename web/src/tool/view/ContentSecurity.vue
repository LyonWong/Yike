<template>
  <div class="v-content-security" :class="{'flex-col': !result}">
    <div class="title flex-row">
      微信违禁文本检查
    </div>
    <div class="input flex-col flex-item" v-show="!result">
      <textarea id="text-input" class="flex-item" v-model="text" placeholder="请输入待检查内容"></textarea>
      <button class="btn btn-submit" @click="textCheck">{{checking ? '检测中...' : '提交检测'}}</button>
    </div>
    <div class="result" v-if="result">
      <div class="submary">
        <span>
          共发现{{result.list.length}}处违规内容
        </span>
        <ul>
          <li v-for="(val, idx) in result.list" :key="idx">{{val}}</li>
        </ul>
      </div>
      <c-section class="" :content="{type: 'markdown', text: result.content}"></c-section>
      <button class="btn btn-submit" @click="result=null">返回编辑</button>
    </div>
  </div>
</template>

<script>
  import CSection from "../../components/section/index";
  export default {
    name: 'content-security',
    components: {CSection},
    data() {
      return {
        text: '',
        result: null,
        checking: false
      }
    },
    methods: {
      textCheck() {
        this.checking = true
        this.api.post('/api/tool-textSecurity', {
          content: this.text
        }).then((res) => {
          this.checking = false
          this.result = {
            list: res.data,
            content: this.text
          }
          for (let v of res.data) {
            console.log(this.result.content, v)
            this.result.content = this.result.content.replace(new RegExp(`(${v})`, 'g'), '~~$1~~')
          }
        })
      }
    }
  }
</script>

<style scoped>
  .v-content-security {
    height: 100%;
    background: #fff;
  }
  .title {
    height: 1rem;
    width: 100%;
    background: #fff;
    font-size: .4rem;
    letter-spacing: .02rem;
    border-bottom: 1px solid #ccc
  }
  .input, .result {
    background: #fff;
  }
  .result {
  }
  #text-input {
    width: 95%;
    margin: .3rem 0;
    outline: none;
    border: none;
    resize: none;
    font-size: .3rem;
    line-height: 1.5;
  }
  .submary {
    padding: .3rem;
    font-size: .24rem;
  }
  .c-section {
    padding: .3rem;
    margin-bottom: 1rem;
  }
  .btn-submit {
    background: #2F57DA;
    color: #fff;
    height: 1rem;
    font-size: .4rem;
    letter-spacing: .02rem;
    width: 7.5rem;
  }
  .result .btn-submit {
    position: fixed;
    bottom: 0;
    box-shadow: 0 -.2rem .4rem #fff;
  }

</style>
<style>
  .c-section s {
    color: darkred;
    background: #fcd6e2;
  }
</style>
