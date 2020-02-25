import Layout from '@/layout';
// 店铺后台
const shopRoutes = {
  path: '/shop',
  component: Layout,
  redirect: '/shop/index',
  name: 'ShopHome',
  meta: {
    title: 'shopManage',
    icon: 'star',
  },
  children: [
    {
      path: 'index',
      component: () => import('@/views/shop/base/config/Index'),
      name: 'ShopConfig',
      meta: { title: 'shopConfig', icon: 'list' },
    },
    {
      path: 'store',
      component: () => import('@/views/shop/store/store/List'),
      name: 'ShopStoreManage',
      meta: { title: 'shopStoreManage', icon: 'list' },
    },
    {
      path: 'store/create',
      component: () => import('@/views/shop/store/store/Create'),
      name: 'CreateShopStore',
      meta: { title: 'createShopStore', noCache: true },
      hidden: true,
    },
    {
      path: 'store/edit/:id(\\d+)',
      component: () => import('@/views/shop/store/store/Edit'),
      name: 'EditShopStore',
      meta: { title: 'editShopStore', noCache: true },
      hidden: true,
    },
    {
      path: 'agent',
      component: () => import('@/views/shop/store/agent/List'),
      name: 'ShopAgentManage',
      meta: { title: 'shopAgentManage', icon: 'list' },
    },
    {
      path: 'agent/create',
      component: () => import('@/views/shop/store/agent/Create'),
      name: 'CreateShopAgent',
      meta: { title: 'createShopAgent', noCache: true },
      hidden: true,
    },
    {
      path: 'agent/edit/:id(\\d+)',
      component: () => import('@/views/shop/store/agent/Edit'),
      name: 'EditShopAgent',
      meta: { title: 'editShopAgent', noCache: true },
      hidden: true,
    },
    {
      path: 'product',
      component: () => import('@/views/shop/product/product/Form'),
      name: 'ShopProductList',
      meta: { title: 'shopProductManage', icon: 'list' },
    },
    {
      path: 'product/category',
      component: () => import('@/views/shop/product/product/Category'),
      name: 'ShopProductCategoryList',
      meta: { title: 'shopProductCategoryManage', icon: 'list' },
      hidden: true,
    },
    {
      path: 'product/create/:cat_id(\\d+)',
      component: () => import('@/views/shop/product/product/Form'),
      name: 'CreateShopProduct',
      meta: { title: 'createShopProduct', icon: 'list' },
      hidden: true,
    },
    {
      path: 'product/edit/:id(\\d+)',
      component: () => import('@/views/shop/product/product/Form'),
      name: 'EditShopProduct',
      meta: { title: 'editShopProduct', icon: 'list' },
      hidden: true,
    },
  ],
};

export default shopRoutes;
