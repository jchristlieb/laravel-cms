import PagesIndex from '../../views/Pages/Index';
import ShowPage from '../../views/Pages/Show';
import EditPage from '../../views/Pages/Edit';
import PostsIndex from '../../views/Posts/Index';
import MenusIndex from '../../views/Menus/Index';
import ShowMenu from '../../views/Menus/Show';
import OptionsIndex from '../../views/Options/Index';
import UsersIndex from '../../views/Users/Index';
import ShowUser from '../../views/Users/Show';
import EditUser from '../../views/Users/Edit';
import ProfileShow from '../../views/Profile/Show';
import ProfileEdit from '../../views/Profile/Edit';

const NotFound = {template: '<div>not found</div>'};
const Admin = {template: '<div>Admin index</div>'};

export default [
    {
        path: '/admin',
        name: 'dashboard',
        component: Admin
    },
    {
        path: '/admin/pages',
        name: 'pages.index',
        component: PagesIndex
    },
    {
        path: '/admin/pages/:id',
        name: 'pages.show',
        component: ShowPage,
        props: true
    },
    {
        path: '/admin/pages/:id/edit',
        name: 'pages.edit',
        component: EditPage,
        props: true
    },
    {
        path: '/admin/posts',
        name: 'posts.index',
        component: PostsIndex
    },
    {
        path: '/admin/menus',
        name: 'menus.index',
        component: MenusIndex
    },
    {
        path: '/admin/menus/:name',
        name: 'menus.show',
        component: ShowMenu,
        props: true
    },
    {
        path: '/admin/options',
        name: 'options.index',
        component: OptionsIndex
    },
    {
        path: '/admin/users',
        name: 'users.index',
        component: UsersIndex
    },
    {
        path: '/admin/users/:id',
        name: 'users.show',
        component: ShowUser,
        props: true
    },
    {
        path: '/admin/users/:id/edit',
        name: 'users.edit',
        component: EditUser,
        props: true
    },
    {
        path: '/admin/profile',
        name: 'profile.show',
        component: ProfileShow
    },
    {
        path: '/admin/profile/edit',
        name: 'profile.edit',
        component: ProfileEdit
    },
    {
        path: '*',
        name: 'catch-all',
        component: NotFound
    }
];
