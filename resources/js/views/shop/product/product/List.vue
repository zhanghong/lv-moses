<template>
  <div class="app-container">
    <div class="filter-container">
      <router-link :to="{ name: 'createShopNormalProduct'}">
        <el-button class="filter-item" type="primary" icon="el-icon-plus">
          {{ $t('table.add') }}
        </el-button>
      </router-link>
    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row>

      <el-table-column align="center" :label="fields.title" width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.title }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.main_image_url">
        <template slot-scope="scope">
          <el-image v-if="scope.row.main_image_url !== undefined" :src="scope.row.main_image_url" style="width: 100px; height: 100px" fit="cover" />
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.group_id">
        <template slot-scope="scope">
          <span v-if="scope.row.group">
            {{ scope.row.group.name }}
          </span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.brand_id">
        <template slot-scope="scope">
          <span v-if="scope.row.brand">
            {{ scope.row.brand.name }}
          </span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.sold_count">
        <template slot-scope="scope">
          <span>{{ scope.row.sold_count }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.market_price">
        <template slot-scope="scope">
          <span>{{ scope.row.market_price }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.sell_price">
        <template slot-scope="scope">
          <span>{{ scope.row.sell_price }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.order">
        <template slot-scope="scope">
          <span>{{ scope.row.order }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="" width="80">
        <template slot-scope="scope">
          <router-link :to="{ name: 'editShopNormalProduct', params: { id: scope.row.id }}">
            <el-button type="primary" size="mini" icon="el-icon-edit">
              {{ $t('table.edit') }}
            </el-button>
          </router-link>
          <el-button type="danger" size="mini" icon="el-icon-delete" @click="handleDelete(scope.row);">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

  </div>
</template>

<script>
import Resource from '@/api/resource';
const modelResource = new Resource('shop/product/index');

export default {
  name: 'ProductList',
  data() {
    return {
      list: [],
      loading: true,
      fields: {
        title: this.$t('product.title'),
        group_id: this.$t('product.group_id'),
        brand_id: this.$t('product.brand_id'),
        main_image_url: this.$t('product.main_image_url'),
        sold_count: this.$t('product.sold_count'),
        market_price: this.$t('product.market_price'),
        sell_price: this.$t('product.sell_price'),
        order: this.$t('base_fields.order'),
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.loading = true;
      const { data } = await modelResource.list({});
      this.list = data;
      this.loading = false;
    },
    handleDelete(row) {
      this.$confirm('确定要删除' + this.$t('model.product') + '『' + row.title + '』吗？', this.$t('options.waring'), {
        confirmButtonText: this.$t('table.confirm'),
        cancelButtonText: this.$t('table.cancel'),
        type: 'warning',
      }).then(() => {
        modelResource.destroy(row.id).then(response => {
          this.$message({
            type: 'success',
            message: this.$t('options.delete_success'),
          });
          this.getList();
        }).catch(() => {
          // nothing
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: this.$t('options.delete_cancel'),
        });
      });
    },
  },
};
</script>
