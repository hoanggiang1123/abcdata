import axiosInstance from '../axios';
import User from './user';
import Link from './link';
import Category from './category';
import Media from './media';
import EnterLink from './enterLink';
import RoleAndPermision from './roleAndPermision';
import PluginRepo from './pluginRepo';
import TeleSale from './teleSale';
import Post from './post';
import Keyword from './keyword';
import FbStat from './fbStat';
import Popup from './popup'

export default {
    user: User(axiosInstance),
    link: Link(axiosInstance),
    category: Category(axiosInstance),
    media: Media(axiosInstance),
    enterLink: EnterLink(axiosInstance),
    roleAndPermision: RoleAndPermision(axiosInstance),
    pluginRepo: PluginRepo(axiosInstance),
    teleSale: TeleSale(axiosInstance),
    post: Post(axiosInstance),
    keyword: Keyword(axiosInstance),
    fbStat: FbStat(axiosInstance),
    popup: Popup(axiosInstance)
}
