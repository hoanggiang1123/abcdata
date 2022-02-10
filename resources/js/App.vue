<template>
    <v-app dark>
    <Header v-if="isRenderHeaderFooter" />
    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
    <Footer v-if="isRenderHeaderFooter" />
  </v-app>
</template>

<script>
import { mapActions } from 'vuex';

import Header from './components/Header.vue';
import Footer from './components/Footer.vue'
export default {
    components:{
        Header,
        Footer
    },
    computed: {
        isRenderHeaderFooter() {
			var arrRouter = ['login', 'register'];
			var routerName = this.$route.name;
			if(arrRouter.indexOf(routerName) !== -1) return false;
			return true;
		},
    },
    created() {
        this.checkLogin().catch(err => {
            localStorage.removeItem('token');
            localStorage.removeItem('screen');
            if (this.$route.name !== 'login') window.location.reload();
        })
    },
    mounted () {
        this.autoRefresh();
    },
    methods: {
        ...mapActions('user', ['checkLogin', 'autoRefresh'])
    }
}
</script>

<style>

</style>
