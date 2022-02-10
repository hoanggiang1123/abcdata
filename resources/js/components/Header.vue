<template>
    <div>
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant="miniVariant"
            :clipped="clipped"
            fixed
            app
        >
            <v-list>
                <template v-for="(item, index) in items">
                    <template v-if="screens[item.name]">
                        <v-list-item v-if="!item.sub" :key="index" :to="item.to">
                            <v-list-item-icon>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item>
                        <v-list-group v-else :key="index" :prepend-icon="item.icon" no-action :value="$route.name && $route.name.includes(item.name)">
                            <template v-slot:activator>
                                <v-list-item-title>{{ item.title }}</v-list-item-title>
                            </template>
                            <template v-for="({ icon, title, to, name }, i) in item.sub">
                                <template v-if="screens[item.name].includes(name)">
                                    <v-list-item :key="`sub${i}`" :to="to">
                                        <v-list-item-title v-text="title"></v-list-item-title>
                                        <v-list-item-icon>
                                            <v-icon v-text="icon"></v-icon>
                                        </v-list-item-icon>
                                    </v-list-item>
                                </template>
                            </template>

                        </v-list-group>
                    </template>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar :clipped-left="clipped" fixed app>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-btn icon @click.stop="miniVariant = !miniVariant">
                <v-icon
                    >mdi-{{
                        `chevron-${miniVariant ? "right" : "left"}`
                    }}</v-icon
                >
            </v-btn>
            <v-btn icon @click.stop="clipped = !clipped">
                <v-icon>mdi-application</v-icon>
            </v-btn>
            <v-btn icon @click.stop="fixed = !fixed">
                <v-icon>mdi-minus</v-icon>
            </v-btn>
            <v-toolbar-title v-text="title" />
            <v-spacer />
            <template v-if="userInfo.id">
                <div class="text-body mr-4">{{ userInfo.name }}</div>
                <v-btn small color="primary" @click="handleLogout">Logout</v-btn>
            </template>
        </v-app-bar>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            clipped: true,
            drawer: true,
            fixed: false,
            items: [
                {
                    icon: "mdi-view-dashboard",
                    title: "Dashboard",
                    name: 'banner',
                    to: "/"
                },
                {
                    icon: "mdi-image-area",
                    title: "Banners",
                    name: 'banner',
                    sub: [
                        {
                            icon: 'mdi-image-edit',
                            title: 'Banners',
                            name: 'banner',
                            to: '/banner'
                        },
                        {
                            icon: 'mdi-folder-multiple-image',
                            title: 'Categories',
                            name: 'category',
                            to: '/category'
                        },
                        // {
                        //     icon: 'mdi-xml',
                        //     title: 'Link Embed',
                        //     to: '/link-embed'
                        // }
                    ]
                },
                {
                    icon: "mdi-link-box-variant",
                    title: "Links",
                    name: 'link',
                    sub: [
                        {
                            icon: 'mdi-link-variant',
                            title: 'Links',
                            name: 'link',
                            to: '/link'
                        },
                        {
                            icon: 'mdi-vector-link',
                            title: 'Folder',
                            name: 'folder',
                            to: '/folder'
                        }
                    ]
                },
                {
                    icon: "mdi-camera-image",
                    title: "Media",
                    name: 'media',
                    to: "/media"
                },
                {
                    icon: "mdi-cog-outline",
                    title: "Settings",
                    name: 'settings',
                    sub: [
                        {
                            icon: 'mdi-shield-check',
                            title: 'Role & Permision',
                            name: 'roleandpermision',
                            to: '/role-permision'
                        },
                        {
                            icon: 'mdi-account-supervisor',
                            title: 'User',
                            name: 'user',
                            to: '/user'
                        }
                    ]
                },
                {
                    icon: "mdi-connection",
                    title: "Plugin Repo",
                    to: '/plugin-repository',
                    name: 'plugin'
                },
                {
                    icon: 'mdi-card-account-phone',
                    title: 'Data',
                    name: 'telesale',
                    sub: [
                        {
                            icon: 'mdi-headphones',
                            title: 'Data',
                            name: 'telesale',
                            to: '/data'
                        },
                        {
                            icon: 'mdi-clipboard-multiple',
                            title: 'Line',
                            name: 'line',
                            to: '/line'
                        },
                        {
                            icon: 'mdi-face-agent',
                            title: 'Agent',
                            name: 'agent',
                            to: '/agent'
                        },
                        {
                            icon: 'mdi-account-tie',
                            title: 'Vip Level',
                            name: 'vip',
                            to: '/vip'
                        },
                        {
                            icon: 'mdi-folder',
                            title: 'Category',
                            name: 'category',
                            to: '/tele-category'
                        },
                        {
                            icon: 'mdi-record-rec',
                            title: 'Record',
                            name: 'record',
                            to: '/record'
                        }
                    ]
                },
                {
                    icon: "mdi-note-text",
                    title: "Posts",
                    to: '/post',
                    name: 'post'
                },
                {
                    icon: 'mdi-text-box-search',
                    title: 'Search Traffic',
                    name: 'keyword',
                    sub: [
                        {
                            icon: 'mdi-merge',
                            title: 'Keyword',
                            name: 'keyword',
                            to: '/keyword'
                        },
                        {
                            icon: 'mdi-web',
                            title: 'Satilite',
                            name: 'satilite',
                            to: '/satilite'
                        },
                        {
                            icon: 'mdi-archive-eye',
                            title: 'Tracking',
                            name: 'tracking',
                            to: '/keyword-tracking'
                        }
                    ]
                },
                {
                    icon: 'mdi-graph',
                    title: 'FbStat',
                    name: 'leagues',
                    sub: [
                        {
                            icon: 'mdi-folder-open',
                            title: 'Leagues',
                            name: 'leagues',
                            to: '/fbstat-leagues'
                        }
                    ]
                },
                {
                    icon: 'mdi-adjust',
                    title: 'Popup',
                    to: '/popup',
                    name: 'popup',
                },
            ],
            miniVariant: false,
            right: true,
            title: "Admin Management"
        };
    },
    computed: {
        ...mapGetters('user', {
            userInfo: 'getUserLogInfo',
            screens: 'getScreen'
        })
    },
    methods: {
        ...mapActions('user', ['userLogout']),
        handleLogout () {
            this.userLogout()
                .then(resp => {
                    this.$router.push('/login')
                })
                .catch(err => {
                    localStorage.removeItem('token');
                    localStorage.removeItem('screen');
                });
        }
    }
};
</script>

<style></style>
