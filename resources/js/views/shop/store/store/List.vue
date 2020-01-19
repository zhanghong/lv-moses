<template>
  <div class="app-container">
    <div class="filter-container">
      <router-link :to="'/shop/store/create'">
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

      <el-table-column align="center" :label="fields.agent_name" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.agent_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.auth_no">
        <template slot-scope="scope">
          <span>{{ scope.row.auth_no }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.area_full_name">
        <template slot-scope="scope">
          <span>{{ scope.row.area_full_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.staff_count" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.staff_count }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="" width="350">
        <template slot-scope="scope">
          <router-link :to="'/shop/store/edit/'+scope.row.id">
            <el-button type="primary" size="small" icon="el-icon-edit">
              {{ $t('table.edit') }}
            </el-button>
          </router-link>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id, scope.row.name);">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

  </div>
</template>

<script>
import Resource from '@/api/resource';
const modelResource = new Resource('shops/1/store/index');

export default {
  name: 'StoreItemList',
  data() {
    return {
      list: [],
      loading: true,
      fields: {
        name: this.$t('store_item.name'),
        agent_name: this.$t('store_item.contact_name'),
        area_full_name: this.$t('store_item.area_full_name'),
        auth_no: this.$t('store_item.auth_no'),
        staff_count: this.$t('store_item.staff_count'),
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
    handleDelete(id, name) {
      this.$confirm('确定要删除『' + name + '』吗？', this.$t('options.waring'), {
        confirmButtonText: this.$t('table.confirm'),
        cancelButtonText: this.$t('table.cancel'),
        type: 'warning',
      }).then(() => {
        modelResource.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: this.$t('options.delete_success'),
          });
          this.getList();
        }).catch(error => {
          console.log(error);
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
