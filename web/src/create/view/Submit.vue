<template>
  <div class="v-create-submit">
    <div class="head flex-row">
      <div class="btn back" @click="$router.back()">返回</div>
      <div class="heading flex-item flex-row" v-if="profile.form==='article' && profile.step==='finish'">
        <a :href="`/home/${profile.teacher.sn}/${profile.form}?sn=${sn}`" target="_blank">{{profile.title}}</a>
      </div>
      <div class="heading flex-item flex-row" v-else>{{dict[profile.form]}}设置</div>
      <div class="btn submit" @click="save">{{hintSubmit}}</div>
    </div>
    <commit ref="commit" :sn="sn" v-on:submit="onSubmit"></commit>
  </div>
</template>

<script>
  const Commit = r => require.ensure([], () => r(require('../components/Commit')), 'create/release')
  export default {
    name: 'create-submit',
    components: {Commit},
    data() {
      return {
        sn: this.$route.params.sn,
        profile: {},
        dict: {
          article: '文章',
          column: '专栏'
        },
        dialog: null
      }
    },
    created() {
      this.api.get('/api/create-profile', {
        sn: this.sn
      }).then((res) => {
        this.profile = res.data
      }, (err) => {
        alert(err.message)
        location.href = `/create/posts`
      })
    },
    mounted() {
    },
    methods: {
      save() {
        this.$refs.commit.submit()
      },
      onSubmit() {
        if (this.profile.form === 'article' && this.profile.step === 'submit') {
          this.bus.$emit('dialog', {
            info: {
              head: '发布确认',
              body: '发布后可修改设置，但不能再修改文章内容'
            },
            btn: {
              prime: '确认',
              vice: '取消'
            },
            call: {
              prime: this.doSubmit
            }
          })
        } else {
          this.doSubmit()
        }
      },
      doSubmit() {
        this.api.post(`/api/create-release-${this.profile.form}`, {
          sn: this.sn
        }).then((res) => {
          switch (this.profile.form) {
            case 'article':
              this.submitArticle(res.data)
              break
            case 'column':
              this.submitColumn(res.data)
              break
          }
        })
      },
      submitArticle(fresh) {
        this.bus.$emit('dialog', {
            info: {
              body: (fresh ? '发布' : '修改') + '成功'
            },
            btn: {
              prime: '前往查看',
              vice: '返回列表'
            },
            call: {
              prime: () => {
                location.href = `/home/${this.profile.teacher.sn}/article?sn=${this.sn}#submit`
              },
              vice: () => {
                // 新发布的文章需要返回三层
                this.$router.go(fresh ? -3 : -1)
              }
            }
          })
      },
      submitColumn(fresh) {
        if (fresh) {
          // location.href = `/create/column/${this.sn}`
          location.href = `/create/posts#column`
        } else {
          this.bus.$emit('dialog', {
            info: {
              head: '提交成功'
            },
            btn: {
              prime: '前往查看',
              vice: '返回列表'
            },
            call: {
              prime: () => {
                location.href = `/home/${this.profile.teacher.sn}/column?sn=${this.sn}`
              },
              vice: () => {
                this.$router.back()
              }
            }
          })
        }
      }
    },
    computed: {
      hintSubmit() {
        if (this.profile.form==='column' && this.profile.step==='submit') {
          return '下一步'
        } else {
          return '提交'
        }
      }
    }
  }
</script>

<style scoped>
  .v-create-submit {
    background: #f0eff5;
  }
  .head {
    position: sticky;
    top: 0;
    z-index: 9;
    height: 1rem;
    background: #fff;
    border-bottom: 1px solid #ddd;
  }
  .head > .back {
    font-size: .28rem;
    padding: 0 1em;
    color: #999;
  }
  .head > .heading {
    font-size: .36rem;
    color: #222;
  }
  .head > .heading > a{
    color: #2F57DA;
    max-width: 4rem;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    /*border-bottom: 1px solid #2F57DA;*/
  }
  .head > .submit {
    font-size: .28rem;
    padding: 0 1em;
    color: #3C55D8;
  }

</style>
