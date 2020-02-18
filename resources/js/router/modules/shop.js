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
      path: 'store',
      component: () => import('@/views/shop/store/store/List'),
      name: 'StoreManage',
      meta: { title: 'storeManage', icon: 'list' },
    },
    {
      path: 'store/create',
      component: () => import('@/views/shop/store/store/Create'),
      name: 'CreateStore',
      meta: { title: 'createAgent', noCache: true },
      hidden: true,
    },
    {
      path: 'store/edit/:id(\\d+)',
      component: () => import('@/views/shop/store/store/Edit'),
      name: 'EditStore',
      meta: { title: 'editStore', noCache: true },
      hidden: true,
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
      name: 'CreateAgent',
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
    {
      path: 'product',
      component: () => import('@/views/shop/product/product/Form'),
      name: 'ProductManage',
      meta: { title: 'productManage', icon: 'list' },
    },
  ],
};

export default shopRoutes;
