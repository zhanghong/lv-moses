import Layout from '@/layout';
// 店铺后台
const shopRoutes = {
  path: '/shop',
  component: Layout,
  redirect: '/shop/index',
  name: 'shop',
  meta: {
    title: '店铺管理',
    icon: 'star',
  },
  children: [
    {
      path: 'index',
      component: () => import('@/views/shop/base/config/Index'),
      name: 'index',
      meta: { title: '店铺设置', icon: 'list' },
    },
    {
      path: 'agent',
      component: () => import('@/views/shop/store/agent/List'),
      name: 'AgentManage',
      meta: { title: '经销商管理', icon: 'list' },
    },
    {
      path: 'agent/create',
      component: () => import('@/views/shop/store/agent/Create'),
      name: 'CreateArticle',
      meta: { title: '添加经销商', noCache: true },
      hidden: true,
    },
    {
      path: 'agent/edit/:id(\\d+)',
      component: () => import('@/views/shop/store/agent/Edit'),
      name: 'EditAgent',
      meta: { title: '编辑经销商', noCache: true },
      hidden: true,
    },
  ],
};

export default shopRoutes;
