<template>
  <div class="app-container">

    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateForm">
        {{ $t('table.add') }}
      </el-button>
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
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEditForm(scope.row.id, scope.row.name);">
            {{ $t('table.edit') }}
          </el-button>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id, scope.row.name);">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :title="formTitle" :visible.sync="formVisible">
      <div class="form-container">
        <el-form ref="form" :model="currentRecord" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item :label="fields.name" prop="name">
            <el-input v-model="currentRecord.name" />
          </el-form-item>
          <el-form-item :label="fields.contact_name" prop="contact_name">
            <el-input v-model="currentRecord.contact_name" />
          </el-form-item>
          <el-form-item :label="fields.contact_phone" prop="contact_phone">
            <el-input v-model="currentRecord.contact_phone" />
          </el-form-item>
          <el-form-item :label="fields.contact_address" prop="contact_phone">
            <el-input v-model="currentRecord.contact_address" type="textarea" />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="formVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="handleSubmit()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>

  </div>
</template>

<script>
import Resource from '@/api/resource';
const agentResource = new Resource('shops/' + 1 + '/store/agents');
import { checkNameUnique } from '@/api/shop/store/agent';
const formDefault = {
  name: '',
  contact_name: '',
  contact_phone: '',
  contact_address: '',
};

export default {
  name: 'StoreAgentList',
  data() {
    var validateNameUnique = (rule, value, callback) => {
      checkNameUnique(this.currentRecord.id, this.currentRecord.name)
        .then(response => {
          if (response.code !== 200) {
            callback(new Error('经销商名称已存在'));
          } else {
            callback();
          }
        })
        .catch(error => {
          console.log(error.response.status);
          callback();
        });
    };

    return {
      list: [],
      loading: true,
      formVisible: false,
      currentRecord: {},
      formTitle: '',
      fields: {
        name: this.$t('store_agent.name'),
        contact_name: this.$t('store_agent.contact_name'),
        contact_phone: this.$t('store_agent.contact_phone'),
        contact_address: this.$t('store_agent.contact_address'),
        store_count: this.$t('store_agent.store_count'),
      },
      rules: {
        name: [
          { required: true, message: '经销商名称 不能为空', trigger: 'blur' },
          { min: 2, max: 20, message: '经销商名称 长度在 2 到 20 个字符', trigger: 'blur' },
          { validator: validateNameUnique, trigger: 'blur' },
        ],
        contact_name: [
          { required: true, message: '联系人 不能为空', trigger: 'blur' },
          { min: 2, max: 20, message: '联系人 长度在 2 到 20 个字符', trigger: 'blur' },
        ],
        contact_phone: [
          { required: true, message: '联系方式 不能为空', trigger: 'blur' },
          { max: 30, message: '联系方式 最大长度 30 个字符', trigger: 'blur' },
        ],
        contact_address: [
          { max: 100, message: '联系地址 最大长度 100 个字符', trigger: 'blur' },
        ],
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
    handleSubmit() {
      this.$refs.shop.validate(valid => {
        if (!valid) {
          return false;
        }

        if (this.currentRecord.id !== undefined) {
          agentResource.update(this.currentRecord.id, this.currentRecord)
            .then(response => {
              this.$message({
                message: this.$t('options.update_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.getList();
            }).catch(error => {
              console.log(error);
            }).finally(() => {
              this.formVisible = false;
            });
        } else {
          agentResource
            .store(this.currentRecord)
            .then(response => {
              this.$message({
                message: this.$t('options.create_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.currentRecord = Object.assign({}, formDefault);
              this.getList();
            })
            .catch(error => {
              console.log(error);
            }).finally(() => {
              this.formVisible = false;
            });
        }
      });
    },
    handleCreateForm() {
      this.formVisible = true;
      this.formTitle = '添加经销商';
      this.currentRecord = Object.assign({}, formDefault);
    },
    handleEditForm(id) {
      this.formVisible = true;
      this.formTitle = '编辑经销商';
      this.currentRecord = this.list.find(agent => agent.id === id);
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
