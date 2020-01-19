<template>
  <div class="app-container">
    <div class="filter-container">
      <router-link :to="'/shop/agent/create'">
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

      <el-table-column align="center" :label="fields.store_count">
        <template slot-scope="scope">
          <span>{{ scope.row.store_count }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.contact_name" width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.contact_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.contact_phone">
        <template slot-scope="scope">
          <span>{{ scope.row.contact_phone }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="fields.contact_address">
        <template slot-scope="scope">
          <span>{{ scope.row.contact_address }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="" width="350">
        <template slot-scope="scope">
          <router-link :to="'/shop/agent/edit/'+scope.row.id">
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
const agentResource = new Resource('shops/1/store/agents');

export default {
  name: 'StoreAgentList',
  data() {
    return {
      list: [],
      loading: true,
      fields: {
        name: this.$t('store_agent.name'),
        contact_name: this.$t('store_agent.contact_name'),
        contact_phone: this.$t('store_agent.contact_phone'),
        contact_address: this.$t('store_agent.contact_address'),
        store_count: this.$t('store_agent.store_count'),
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.loading = true;
      const { data } = await agentResource.list({});
      this.list = data;
      this.loading = false;
    },
    handleDelete(id, name) {
      this.$confirm('确定要删除经销商『' + name + '』吗？', this.$t('options.waring'), {
        confirmButtonText: this.$t('table.confirm'),
        cancelButtonText: this.$t('table.cancel'),
        type: 'warning',
      }).then(() => {
        agentResource.destroy(id).then(response => {
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
