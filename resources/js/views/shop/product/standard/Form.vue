<template>
  <div class="app-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" label-width="150px">
      <el-form-item :label="fields.name" prop="name">
        <el-input v-model.trim="postForm.name" placeholder="请输入规格名" />
      </el-form-item>

      <el-form-item :label="fields.order" prop="order">
        <el-input v-model.number="postForm.order" placeholder="编号小的排在前面" />
      </el-form-item>

      <el-form-item :label="fields.selector_name" prop="selector_name">
        <el-input v-model.trim="postForm.selector_name" autocomplete="off" placeholder="请输入属性值" @keyup.enter.native="addSelector" />
      </el-form-item>

      <el-form-item :label="''" prop="selectors">
        <el-tag v-for="tag in postForm.selectors" :key="tag.name">
          {{ tag.name }}
        </el-tag>
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
const modelResource = new Resource('shop/product/standards');

const defaultForm = {
  name: '',
  selector_name: '',
  selectors: [],
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
            callback(new Error('属性名 已存在'));
          } else {
            callback();
          }
        })
        .catch(() => {
          callback();
        });
    };

    // 验证属性值是否存在
    var validateSelectorNameUnique = (rule, value, callback) => {
      let name = '';
      let idx = -1;

      if (value !== undefined) {
        name = value.trim();
      }

      if (name === '') {
        return false;
      }

      idx = this.postForm.selectors.findIndex(item => item.name === name);
      if (idx >= 0) {
        callback(new Error('规格值已存在'));
        return false;
      }

      callback();
    };

    var validateSelectorIsEmpty = (rule, value, callback) => {
      if (this.$isEmpty(this.postForm.selectors)) {
        callback(new Error('属性值 不能为空'));
      } else {
        callback();
      }
    };

    return {
      loading: true,
      formTitle: '',
      tempRoute: {},
      postForm: Object.assign({}, defaultForm),
      fields: {
        name: this.$t('product_standard.name'),
        selector_name: this.$t('product_standard.selector_name'),
        order: this.$t('base_fields.order'),
      },
      rules: {
        name: [
          { required: true, message: '规格名 不能为空', trigger: 'blur' },
          { min: 2, max: 10, message: '规格名 长度在 2 到 10 个字符', trigger: 'blur' },
          { validator: validateNameUnique, trigger: 'blur' },
        ],
        selector_name: [
          // { required: true, message: '属性名 不能为空', trigger: 'blur' },
          // { min: 2, max: 10, message: '属性名 长度在 2 到 10 个字符', trigger: 'blur' },
          { validator: validateSelectorNameUnique, trigger: 'blur' },
        ],
        selectors: [
          { validator: validateSelectorIsEmpty, trigger: 'blur' },
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
      this.$router.push({ name: 'shopProductStandardList' });
    },
    addSelector() {
      this.$refs.postForm.validateField('selector_name', error => {
        if (!error) {
          const name = this.postForm.selector_name;
          if (!this.$isEmpty(name)) {
            this.postForm.selectors.push({
              name,
            });
            this.postForm.selector_name = '';
          }
        }
      });
    },
  },
};
</script>
