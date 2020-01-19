import Layout from '@/layout';
// 店铺后台
const shopRoutes = {
  path: '/shop',
  component: Layout,
  redirect: '/shop/index',
  name: 'shop',
  meta: {
    title: 'shopManage',
    icon: 'star',
  },
  children: [
    {
      path: 'index',
      component: () => import('@/views/shop/base/config/Index'),
      name: 'index',
      meta: { title: 'shopConfig', icon: 'list' },
    },
    {
      path: 'agent',
      component: () => import('@/views/shop/store/agent/List'),
      name: 'AgentManage',
      meta: { title: 'agentManage', icon: 'list' },
    },
    {
      path: 'agent/create',
      component: () => import('@/views/shop/store/agent/Create'),
      name: 'CreateArticle',
      meta: { title: 'createAgent', noCache: true },
      hidden: true,
    },
    {
      path: 'agent/edit/:id(\\d+)',
      component: () => import('@/views/shop/store/agent/Edit'),
      name: 'EditAgent',
      meta: { title: 'editAgent', noCache: true },
      hidden: true,
    },
  ],
};

export default shopRoutes;
