import Layout from '@/layout';
// 店铺后台
const shopBaseRoutes = {
  path: '/shop',
  component: Layout,
  redirect: '/shop/index',
  name: 'shopHome',
  meta: {
    title: 'shopHome',
    icon: 'star',
  },
  children: [
    {
      path: 'index',
      component: () => import('@/views/shop/base/config/Index'),
      name: 'shopConfig',
      meta: { title: 'shopConfig', icon: 'list' },
    },
    {
      path: 'store',
      component: () => import('@/views/shop/store/store/List'),
      name: 'shopStoreList',
      meta: { title: 'shopStoreList', icon: 'list' },
    },
    {
      path: 'store/create',
      component: () => import('@/views/shop/store/store/Create'),
      name: 'createShopStore',
      meta: { title: 'createShopStore', noCache: true },
      hidden: true,
    },
    {
      path: 'store/edit/:id(\\d+)',
      component: () => import('@/views/shop/store/store/Edit'),
      name: 'editShopStore',
      meta: { title: 'editShopStore', noCache: true },
      hidden: true,
    },
    {
      path: 'agent',
      component: () => import('@/views/shop/store/agent/List'),
      name: 'shopAgentList',
      meta: { title: 'shopAgentList', icon: 'list' },
    },
    {
      path: 'agent/create',
      component: () => import('@/views/shop/store/agent/Create'),
      name: 'createShopAgent',
      meta: { title: 'createShopAgent', noCache: true },
      hidden: true,
    },
    {
      path: 'agent/edit/:id(\\d+)',
      component: () => import('@/views/shop/store/agent/Edit'),
      name: 'editShopAgent',
      meta: { title: 'editShopAgent', noCache: true },
      hidden: true,
    },
  ],
};

export default shopBaseRoutes;
