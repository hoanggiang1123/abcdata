<template>
    <v-row>
        <v-col cols="12">
            <v-toolbar>
                <v-toolbar-title>
                    Dashboard
                </v-toolbar-title>
            </v-toolbar>
            <v-card>
                <v-card-title>
                    Welcome: {{ userInfo.name }}
                </v-card-title>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import {  mapGetters } from 'vuex'
export default {
    computed: {
        ...mapGetters('user', {
            userInfo: 'getUserLogInfo'
        })
    }
}
</script>

<style>

</style>
