<template>
  <section class="calendar">
    <input class="input" size="50" type="text" @click.stop="open($event,'picker3')" v-model="calendarInfo.value" readonly />
    <v-calendar
      :type="calendar.type"
      :value.sync="calendar.value"
      :x="calendar.x"
      :y="calendar.y"
      :begin.sync="calendar.begin"
      :end.sync="calendar.end"
      :range.sync="calendar.range"
      :weeks="calendar.weeks"
      :months="calendar.months"
      :sep="calendar.sep">
    </v-calendar>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex'
  import vCalendar from '@teacher/components/calendar.vue'

  export default{
    name: 'c-calendar',
    components: {
      vCalendar,
    },
    data() {
      return {
        // 数据绑定
        calendar:{
          x:0,
          y:0,
          picker:"",
          type:"date",
          value:"",
          begin:"",
          end:"",
          sep:"/",
          weeks:[],
          months:[],
          range:false,
          items:{
            // 单选模式
            picker1:{
              type:"date",
              begin:"2016-08-20",
              end:"2016-08-25",
              value:"2016-08-21",
              sep:"-",
              weeks:['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
              months:['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            },
            // 2个日期模式
            picker2:{
              type:"date",
              value:"",
              range:true,
              sep:".",
            },
            // 日期时间模式
            picker3:{
              type:"datetime",
              value:"",
              sep:"-",
            },
            // 日期时间模式
            picker4:{
              type:"time",
              value:"",
            },
          }
        }
      }
    },
    // 处理值的传递
    watch:{
      'calendar.value': function (value) {
        this.calendar.items[this.calendar.picker].value=value
      }
    },
    computed: {
      ...mapGetters({
        calendarInfo: 'getCalendar',
      })
    },
    created() {
      this.$store.commit('CHANGE_CALENDAR', {value: '',show:false});
    },
    methods: {
      // 打开显示选择器
      open(e,type) {
        // 设置类型
        this.calendar.picker=type
        this.calendar.type=this.calendar.items[type].type
        this.calendar.range=this.calendar.items[type].range
        this.calendar.begin=this.calendar.items[type].begin
        this.calendar.end=this.calendar.items[type].end
        this.calendar.value=this.calendar.items[type].value
        // 可不用写
        this.calendar.sep=this.calendar.items[type].sep
        this.calendar.weeks=this.calendar.items[type].weeks
        this.calendar.months=this.calendar.items[type].months

        this.calendar.x=e.target.offsetLeft
        this.calendar.y=e.target.offsetTop+e.target.offsetHeight+8
        //
        this.$store.commit('CHANGE_CALENDAR', {show:true});
      }
    },
  }
</script>

<style scoped lang="stylus" rel="stylesheet/stylus">
  input
    padding: 6px;
    width: 167px;
    border: 1px solid #e6eaf2;
</style>
