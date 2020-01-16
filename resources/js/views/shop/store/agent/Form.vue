<template>
  <div class="app-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" label-width="150px">
      <el-form-item :label="fields.name" prop="name">
        <el-input v-model.trim="postForm.name" placeholder="请输入经销商名称" />
      </el-form-item>

      <el-form-item :label="fields.contact_name" prop="contact_name">
        <el-input v-model.trim="postForm.contact_name" placeholder="请输入经销商名称" />
      </el-form-item>

      <el-form-item :label="fields.contact_phone" prop="contact_phone">
        <el-input v-model.trim="postForm.contact_phone" placeholder="请输入经销商名称" />
      </el-form-item>

      <el-form-item :label="fields.contact_address" prop="contact_address">
        <el-input v-model.trim="postForm.contact_address" type="textarea" placeholder="请输入经销商名称" />
      </el-form-item>

      <el-form-item>
        <el-button @click="onCancel">
          {{ $t('table.cancel') }}
        </el-button>
        <el-button type="primary" @click="onSubmit">
          {{ $t('table.confirm') }}
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import { checkNameUnique } from '@/api/shop/store/agent';

const defaultForm = {
  name: '',
  contact_name: '',
  contact_phone: '',
  contact_address: '',
};

const shop_id = 1;
const formResource = new Resource('shops/' + shop_id + '/store/agents');

export default {
  name: 'Form',
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    var validateNameUnique = (rule, value, callback) => {
      checkNameUnique(this.postForm.id, this.postForm.name)
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
      loading: true,
      formTitle: '',
      tempRoute: {},
      postForm: Object.assign({}, defaultForm),
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
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      this.fetchData(id);
    } else {
      this.postForm = Object.assign({}, defaultForm);
      this.loading = false;
    }
    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    fetchData(id) {
      formResource
        .get(id)
        .then(response => {
          this.postForm = response.data;
        })
        .catch(error => {
          console.log(error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    onSubmit() {
      this.$refs.postForm.validate(valid => {
        if (!valid) {
          return false;
        }

        this.loading = true;
        if (this.postForm.id !== undefined) {
          formResource.update(this.postForm.id, this.postForm)
            .then(response => {
              this.$message({
                message: this.$t('options.update_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.loading = false;
              this.$router.push({ path: '/shop/agent' });
            }).catch(error => {
              console.log(error);
              this.loading = false;
            });
        } else {
          formResource.store(this.postForm)
            .then(response => {
              this.$message({
                message: this.$t('options.create_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.loading = false;
              this.$router.push({ path: '/shop/agent' });
            }).catch(error => {
              this.loading = false;
              console.log(error);
            });
        }
      });
    },
    onCancel() {
      this.loading = false;
      this.$router.push({ path: '/shop/agent' });
    },
  },
};
</script>
