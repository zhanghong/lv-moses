<template>
  <div class="app-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" label-width="150px">

      <el-form-item :label="fields.agent_id" prop="agent_id">
        <el-select v-model="postForm.agent_id" placeholder="请输选择所属经销商">
          <el-option v-for="item in postForm.agents" :key="item.value" :label="item.label" :value="item.value" />
        </el-select>
      </el-form-item>

      <el-form-item :label="fields.name" prop="name">
        <el-input v-model.trim="postForm.name" placeholder="请输入经销商名称" />
      </el-form-item>

      <el-form-item :label="fields.images" prop="images">
        <el-upload
          name="image"
          accept="image/png, image/jpeg"
          :action="uploadImageUrl"
          list-type="picture-card"
          :limit="imageLimit"
          :file-list="postForm.images"
          :on-exceed="handleImageExceed"
          :on-preview="handlePictureCardPreview"
          :on-remove="handleImageRemove"
          :on-success="handleImageUpload"
          :before-upload="beforeImageUpload"
        >
          <i class="el-icon-plus" />
          <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过2M</div>
        </el-upload>
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
    <el-dialog :visible.sync="dialogVisible">
      <img width="100%" :src="dialogImageUrl" alt="">
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import { checkNameUnique } from '@/api/shop/store/store';

const defaultForm = {
  agent_id: '',
  name: '',
  auth_no: '',
  images: [],
  agents: [],
  area_id: 0,
  address: '',
  staff_count: '',
  order: '',
  contact_name: '',
  contact_phone: '',
};

const shop_id = 1;
const modelResource = new Resource('shops/' + shop_id + '/store/index');
// const imageResource = new Resource('shops/' + shop_id + '/store/image');

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
            callback(new Error('门店名称已存在'));
          } else {
            callback();
          }
        })
        .catch(error => {
          console.log(error);
          callback();
        });
    };

    return {
      loading: true,
      formTitle: '',
      tempRoute: {},
      agents: [],
      postForm: Object.assign({}, defaultForm),
      dialogImageUrl: '',
      dialogVisible: false,
      imageLimit: 2,
      fields: {
        agent_id: this.$t('store_item.agent_id'),
        name: this.$t('store_item.name'),
        auth_no: this.$t('store_item.auth_no'),
        images: this.$t('store_item.images'),
        area_id: this.$t('store_item.area_id'),
        address: this.$t('store_item.address'),
        contact_name: this.$t('store_item.contact_name'),
        contact_phone: this.$t('store_item.contact_phone'),
        staff_count: this.$t('store_item.staff_count'),
        order: this.$t('store_item.order'),
      },
      rules: {
        name: [
          { required: true, message: '门店名称 不能为空', trigger: 'blur' },
          { min: 2, max: 20, message: '门店名称 长度在 2 到 20 个字符', trigger: 'blur' },
          { validator: validateNameUnique, trigger: 'blur' },
        ],
        // contact_name: [
        //   { required: true, message: '联系人 不能为空', trigger: 'blur' },
        //   { min: 2, max: 20, message: '联系人 长度在 2 到 20 个字符', trigger: 'blur' },
        // ],
        // contact_phone: [
        //   { required: true, message: '联系方式 不能为空', trigger: 'blur' },
        //   { max: 30, message: '联系方式 最大长度 30 个字符', trigger: 'blur' },
        // ],
        // contact_address: [
        //   { max: 100, message: '联系地址 最大长度 100 个字符', trigger: 'blur' },
        // ],
      },
    };
  },
  computed: {
    // 上传Logo图片后端路由
    uploadImageUrl() {
      return '/api/shops/' + shop_id + '/store/image';
    },
  },
  created() {
    this.fetchFieldOptions();
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      this.fetchData(id);
    } else {
      this.postForm = Object.assign({}, defaultForm);
      console.log(this.postForm.images);
      console.log('+++++++++++++++');
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
        .catch(error => {
          console.log(error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    fetchFieldOptions() {
      const agentResource = new Resource('shops/' + shop_id + '/store/agents');
      agentResource
        .select({})
        .then(response => {
          this.postForm.agents = response.data;
        })
        .catch(() => {
          this.postForm.agents = [];
        });
    },
    beforeImageUpload(file) {
      const fileKbSize = file.size / 1024; // file KB size
      let isValid = true;

      if (fileKbSize < 20) {
        this.$message.error('Logo 图片大小不能小于 20KB!');
        isValid = false;
      } else if (fileKbSize > 5120) {
        this.$message.error('Logo 图片大小不能超过 5M!');
        isValid = false;
      }

      return isValid;
    },
    handleImageUpload(res, file) {
      this.postForm.images.push(res.data);
      console.log(this.postForm.images);
    },
    handleImageExceed(files, fileList) {
      this.$message.warning(`当前限制选择 ${this.imageLimit} 个文件`);
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleImageRemove(file, fileList) {
      console.log(file);
      this.postForm.images = fileList.splice(fileList.findIndex(item => item.id === file.id));
    },
    onSubmit() {
      this.$refs.postForm.validate(valid => {
        if (!valid) {
          return false;
        }

        const { id, images, agents, ...data } = this.postForm;
        data['image_ids'] = images.map((img) => {
          return img.id;
        });
        console.log(agents);
        this.loading = true;
        if (id !== undefined) {
          modelResource.update(this.postForm.id, data)
            .then(response => {
              this.$message({
                message: this.$t('options.update_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.loading = false;
              this.$router.push({ path: '/shop/store' });
            }).catch(error => {
              console.log(error);
              this.loading = false;
            });
        } else {
          modelResource.store(data)
            .then(response => {
              this.$message({
                message: this.$t('options.create_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.loading = false;
              this.$router.push({ path: '/shop/store' });
            }).catch(error => {
              this.loading = false;
              console.log(error);
            });
        }
      });
    },
    onCancel() {
      this.loading = false;
      this.$router.push({ path: '/shop/store' });
    },
  },
};
</script>
