<template>
  <div class="container">
    <img src="@/assets/phalcon.jpg">
    <img src="@/assets/vuejs.png">
    <p class="title">{{message}}</p>
    <p>Phalcon is a high Performance PHP Framework</p>
    <p>Vuejs is a Progressive JavaScript Framework</p>
    <p>It is amazing to work with Phalcon & vuejs</p>
    <p>Enjoy this framework, enjoy codeing!</p>
    <button type="button" @click="getDemoName" v-show="showButton">获取</button>
  </div>
</template>

<script>
  import tools from '@/lib/tools.js';
  export default {
    data: function () {
      return {
        showButton: false,
        message: 'This is a phalcon & Vuejs demo'
      }
    },
    methods: {
      getDemoName() {
        let url = '/demo/index/index';
        let params = {
          name: 'phvue'
        }
        let successFun = (res) => {
          let response = res.data;
          if (response.success) {
            alert(response.data)
          } else {
            alert(response.msg)
          }
        }
        let errorFun = (err) => {
          alert(err)
        }
        tools.axiosPost(url, params, successFun, errorFun);
      }
    }
  }

</script>

<style lang="stylus" scoped>
  @import "../style/login.styl";

</style>
