import Layout from '@/layout';
// 店铺后台
const shopProductRoutes = {
  path: '/shop/product',
  component: Layout,
  redirect: '/shop/product/index',
  name: 'shopProductHome',
  meta: {
    title: 'shopProductHome',
    icon: 'star',
  },
  children: [
    {
      path: 'index',
      component: () => import('@/views/shop/product/brand/List'),
      name: 'shopProductList',
      meta: { title: 'shopProductList', icon: 'list' },
    },
    {
      path: 'brand',
      component: () => import('@/views/shop/product/brand/List'),
      name: 'shopProductBrandList',
      meta: { title: 'shopProductBrandList', icon: 'list' },
    },
    {
      path: 'brand/create',
      component: () => import('@/views/shop/product/brand/Create'),
      name: 'createShopProductBrand',
      meta: { title: 'createShopProductBrand', noCache: true },
      hidden: true,
    },
    {
      path: 'brand/edit/:id(\\d+)',
      component: () => import('@/views/shop/product/brand/Edit'),
      name: 'editShopProductBrand',
      meta: { title: 'editShopProductBrand', noCache: true },
      hidden: true,
    },
    {
      path: 'group',
      component: () => import('@/views/shop/product/group/List'),
      name: 'shopProductGroupList',
      meta: { title: 'shopProductGroupList', icon: 'list' },
    },
    {
      path: 'group/create',
      component: () => import('@/views/shop/product/group/Create'),
      name: 'createShopProductGroup',
      meta: { title: 'createShopProductGroup', noCache: true },
      hidden: true,
    },
    {
      path: 'group/edit/:id(\\d+)',
      component: () => import('@/views/shop/product/group/Edit'),
      name: 'editShopProductGroup',
      meta: { title: 'editShopProductGroup', noCache: true },
      hidden: true,
    },
  ],
};

export default shopProductRoutes;
