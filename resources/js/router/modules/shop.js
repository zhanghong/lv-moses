import Layout from '@/layout';
// 店铺后台
const shopRoutes = {
  path: '/shop',
  component: Layout,
  redirect: '/shop/index',
  name: 'shop',
  meta: {
    title: 'Shop',
    icon: 'star',
  },
  children: [
    {
      path: 'index',
      component: () => import('@/views/shop/base/config/Index'),
      name: 'index',
      meta: { title: 'index', icon: 'list' },
    },
    {
      path: 'agent',
      component: () => import('@/views/shop/store/agent/List'),
      name: 'agent',
      meta: { title: 'agent', icon: 'list' },
    },
  ],
};

export default shopRoutes;
