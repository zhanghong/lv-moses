<template>
  <div class="app-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" label-width="150px">
      <el-form-item :label="fields.name" prop="name">
        <el-input v-model.trim="postForm.name" placeholder="请输入分类名称" />
      </el-form-item>

      <el-form-item :label="fields.order" prop="order">
        <el-input v-model.number="postForm.order" placeholder="编号小的排在前面" />
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
const modelResource = new Resource('shop/product/groups');

const defaultForm = {
  name: '',
  order: 0,
};

export default {
  name: 'ModelForm',
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    var validateNameUnique = (rule, value, callback) => {
      modelResource
        .unique('name', value, this.postForm.id)
        .then(response => {
          if (response.code !== 200) {
            callback(new Error('分类名称 已存在'));
          } else {
            callback();
          }
        })
        .catch(() => {
          callback();
        });
    };

    return {
      loading: true,
      formTitle: '',
      tempRoute: {},
      postForm: Object.assign({}, defaultForm),
      fields: {
        name: this.$t('product_group.name'),
        order: this.$t('base_fields.order'),
      },
      rules: {
        name: [
          { required: true, message: '分类名称 不能为空', trigger: 'blur' },
          { min: 2, max: 20, message: '分类名称 长度在 2 到 20 个字符', trigger: 'blur' },
          { validator: validateNameUnique, trigger: 'blur' },
        ],
        order: [
          { required: true, message: '排序编号 不能为空', trigger: 'change' },
          { type: 'integer', message: '排序编号 只能输入整数', trigger: 'blur' },
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
      modelResource
        .get(id)
        .then(response => {
          this.postForm = response.data;
        })
        .catch(() => {
          // nothing
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
          modelResource.update(this.postForm.id, this.postForm)
            .then(response => {
              this.$message({
                message: this.$t('options.update_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.goToList();
            }).catch(() => {
              this.loading = false;
            });
        } else {
          modelResource.store(this.postForm)
            .then(response => {
              this.$message({
                message: this.$t('options.create_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.goToList();
            }).catch(() => {
              this.loading = false;
            });
        }
      });
    },
    onCancel() {
      this.goToList();
    },
    goToList() {
      if (this.loading) {
        this.loading = false;
      }
      this.$router.push({ name: 'shopProductGroupList' });
    },
  },
};
</script>
