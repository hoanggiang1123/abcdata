import Vue from 'vue';
import VueRouter from 'vue-router';

import HomePage from './pages/HomePage';
import Login from './pages/Login';
import Link from './pages/Link';
import LinkDetail from './pages/LinkDetail';
import LinkDirectory from './pages/LinkDirectory';
import LinkDirectoryDetail from './pages/LinkDirectoryDetail';
import Media from './pages/Media.vue';
import EnterLink from './pages/EnterLink.vue';
import Folder from './pages/Folder.vue';
import EnterLinkDetail from './pages/EnterLinkDetail.vue';
import FolderDetail from './pages/FolderDetail.vue';
import RoleAndPermision from './pages/RoleAndPermision.vue';
import User from './pages/User.vue'
import Welcome from './pages/Welcome.vue';
import PluginRepo from './pages/PluginRepo.vue';
import TeleSale from './pages/TeleSale.vue';
import Line from './pages/Line.vue';
import Post from './pages/Post.vue';
import Keyword from './pages/Keyword.vue';
import NotFound from './pages/NotFound.vue';
import SatiliteDomain from './pages/SatiliteDomain.vue';
import KeywordTracking from './pages/KeywordTracking.vue';
import TeleSaleAgent from './pages/TeleSaleAgent.vue';
import FbStatLeague from './pages/FbStatLeague.vue';
import TeleSaleVip from './pages/TeleSaleVip.vue';
import TeleSaleCategory from './pages/TeleSaleCategory.vue';
import Popup from './pages/Popup.vue';
import TeleSaleRecord from './pages/TeleSaleRecord.vue';
// import LinkEmbed from './pages/LinkEmbed'

import { isAuthenticated, isNotAuthenticated, isAuthAndCorrectScreen, isNotAuthAndCorrectScreen } from './authenticate';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomePage,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        beforeEnter: isNotAuthAndCorrectScreen
    },
    {
        path: '/banner',
        name: 'banner',
        component: Link,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/banner/:id',
        name: 'banner.detail',
        component: LinkDetail,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/category',
        name: 'category',
        component: LinkDirectory,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/category/:id',
        name: 'category.detail',
        component: LinkDirectoryDetail,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/media',
        name: 'media',
        component: Media,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/link',
        name: 'link',
        component: EnterLink,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/link/:id',
        name: 'link.detail',
        component: EnterLinkDetail,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/folder',
        name: 'folder',
        component: Folder,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/folder/:id',
        name: 'folder.detail',
        component: FolderDetail,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path:'/role-permision',
        name: 'roleandpermision',
        component: RoleAndPermision,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path:'/user',
        name: 'user',
        component: User,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path:'/welcome',
        name: 'welcome',
        component: Welcome,
        beforeEnter: isAuthenticated
    },
    {
        path: '/plugin-repository',
        name: 'plugin',
        component: PluginRepo,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/data',
        name: 'telesale',
        component: TeleSale,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/line',
        name: 'telesale.line',
        component: Line,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/agent',
        name: 'telesale.agent',
        component: TeleSaleAgent,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/post',
        name: 'post',
        component: Post,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/keyword',
        name: 'keyword',
        component: Keyword,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/satilite',
        name: 'keyword.satilite',
        component: SatiliteDomain,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/keyword-tracking',
        name: 'keyword.tracking',
        component: KeywordTracking,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/fbstat-leagues',
        name: 'leagues',
        component: FbStatLeague,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/vip',
        name: 'telesale.vip',
        component: TeleSaleVip,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/tele-category',
        name: 'telesale.category',
        component: TeleSaleCategory,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/record',
        name: 'telesale.record',
        component: TeleSaleRecord,
        beforeEnter: isAuthAndCorrectScreen
    },
    {
        path: '/popup',
        name: 'popup',
        component: Popup,
        beforeEnter: isAuthAndCorrectScreen
    },

    {
        path: '*',
        name: 'notfound',
        component: NotFound
    }
    // {
    //     path: '/link-embed',
    //     name: 'link.embed',
    //     component: LinkEmbed,
    //     beforeEnter: isAuthenticated
    // },
];

const router = new VueRouter({
    mode: 'history',
    // base: `/${window._adminPath}/`,
    routes
});

export default router;
