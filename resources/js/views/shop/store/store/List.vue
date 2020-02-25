<template>
  <div class="app-container">
    <div class="filter-container">
      <router-link :to="{ name: 'shopStoreList'}">
        <el-button class="filter-item" type="primary" icon="el-icon-plus">
          {{ $t('table.add') }}
        </el-button>
      </router-link>
    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row>

      <el-table-column align="center" :label="fields.name" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.agent_name">
        <template slot-scope="scope">
          <span>{{ scope.row.agent_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.auth_no">
        <template slot-scope="scope">
          <span>{{ scope.row.auth_no }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.area_full_name" width="400">
        <template slot-scope="scope">
          <span>{{ scope.row.full_address }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.order">
        <template slot-scope="scope">
          <span>{{ scope.row.order }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="" width="200">
        <template slot-scope="scope">
          <router-link :to="{ name: 'editShopStore', params: { id: scope.row.id }}">
            <el-button type="primary" size="small" icon="el-icon-edit">
              {{ $t('table.edit') }}
            </el-button>
          </router-link>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row);">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

  </div>
</template>

<script>
import Resource from '@/api/resource';
const modelResource = new Resource('shop/store/index');

export default {
  name: 'StoreItemList',
  data() {
    return {
      list: [],
      loading: true,
      fields: {
        name: this.$t('store.name'),
        agent_name: this.$t('store.contact_name'),
        area_full_name: this.$t('store.area_full_name'),
        auth_no: this.$t('store.auth_no'),
        staff_count: this.$t('store.staff_count'),
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
      this.$confirm('确定要删除' + this.$t('model.store') + '『' + row.name + '』吗？', this.$t('options.waring'), {
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
