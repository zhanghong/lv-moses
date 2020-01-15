import Layout from '@/layout';

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
      component: () => import('@/views/shop/base/config/index.vue'),
      name: 'index',
      meta: { title: 'index' },
    },
    {
      path: 'config',
      component: () => import('@/views/shop/base/config/index.vue'),
      name: 'config',
      meta: { title: 'config' },
    },
  ],
};

export default shopRoutes;
