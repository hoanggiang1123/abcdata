import store from './store'

const isAuthenticated = (to, from, next) => {
    // console.log(store.getters['user/isLogin']);
    if (store.getters['user/isLogin']) {
        next();
    } else {
        next({
            name: 'login'
        })
    }
}

const isNotAuthenticated = (to, from, next) => {

    if (!store.getters['user/isLogin']) {
        next();
    } else {
        next({
            name: 'home'
        })
    }
}

const isAuthAndCorrectScreen = (to, from, next) => {

    let name = (to.name.split('.'))[0];

    if (name === 'home') name = 'banner';

    const screens = store.getters['user/getScreen'];

    if (!Object.keys(screens).length) {
        next({
            name: 'login'
        })
    }
    let check = false;

    for (let i in screens) {
        if (screens[i].includes(name)) {
            check = true;
            break;
        }
    }

    if (check) {
        next();
    } else {
        next({
            name: 'welcome'
        })
    }
}

const isNotAuthAndCorrectScreen = (to, from, next) => {
    if (!store.getters['user/getScreen'].length) {
        next();
    }
    else {
        next({ name: from.name ? from.name : 'home' })
    }
}


export {
    isAuthenticated, isNotAuthenticated, isAuthAndCorrectScreen, isNotAuthAndCorrectScreen
}
