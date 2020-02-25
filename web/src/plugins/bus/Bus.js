/**
 * Author: LyonWong
 * Date: 2019-04-03
 */
import Bus from 'vue'
import Index from './Index'
// import Dialog from 'components/modal/Dialog'
// import Toast from 'components/Toast'

const intall = {
  install: (Vue) => {
    Vue.prototype.bbs = new Bus({
        el: '#bus',
        components: {Index},
        created() {
          alert('bus')
        }
      }
    )
  }
}

export default {intall}
