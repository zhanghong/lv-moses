
<template>
  <div class="app-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" label-width="150px">

      <el-form-item :label="fields.agent_id" prop="agent_id">
        <el-select v-model="postForm.agent_id" placeholder="请输选择所属经销商">
          <el-option v-for="item in agents" :key="item.value" :label="item.label" :value="item.value" />
        </el-select>
      </el-form-item>

      <el-form-item :label="fields.name" prop="name">
        <el-input v-model.trim="postForm.name" placeholder="请输入经销商名称" />
      </el-form-item>

      <el-form-item :label="fields.area_id" prop="area_id">
        <area-select :init-value="postForm.locate_paths" @change="onDistrictChanged" />
      </el-form-item>

      <el-form-item :label="fields.address" prop="address">
        <el-input v-model.trim="postForm.address" placeholder="请输入详细地址" />
      </el-form-item>

      <el-form-item :label="fields.contact_phone" prop="contact_phone">
        <el-input v-model.trim="postForm.contact_phone" placeholder="请输入联系电话" />
      </el-form-item>

      <el-form-item :label="fields.contact_name" prop="contact_name">
        <el-input v-model.trim="postForm.contact_name" placeholder="请输入联系人姓名" />
      </el-form-item>

      <el-form-item :label="fields.streets" prop="streets">
        <region-select :list="postForm.streets" @change="onRegionChanged" />
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

      <el-form-item :label="fields.staff_count" prop="staff_count">
        <el-input v-model.number="postForm.staff_count" placeholder="请输入员工人数" />
      </el-form-item>

      <el-form-item :label="fields.auth_no" prop="auth_no">
        <el-input v-model.trim="postForm.auth_no" placeholder="请输入门店编号" />
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
    <el-dialog :visible.sync="dialogVisible">
      <img width="100%" :src="dialogImageUrl" alt="">
    </el-dialog>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import AreaSelect from '@/components/AreaSelect';
import RegionSelect from './components/RegionSelect';

const defaultForm = {
  agent_id: '',
  name: '',
  area_id: 0,
  locate_paths: [],
  streets: [],
  address: '',
  contact_phone: '',
  contact_name: '',
  images: [],
  staff_count: '',
  auth_no: '',
  order: 0,
};

const modelResource = new Resource('shop/store/index');

export default {
  name: 'ModelForm',
  components: {
    AreaSelect,
    RegionSelect,
  },
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
            callback(new Error('门店名称 已存在'));
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
      agents: [],
      postForm: Object.assign({}, defaultForm),
      street_ids: [],
      dialogImageUrl: '',
      dialogVisible: false,
      imageLimit: 2,
      fields: {
        agent_id: this.$t('store.agent_id'),
        name: this.$t('store.name'),
        auth_no: this.$t('store.auth_no'),
        images: this.$t('store.images'),
        area_id: this.$t('store.area_id'),
        address: this.$t('store.address'),
        contact_name: this.$t('store.contact_name'),
        contact_phone: this.$t('store.contact_phone'),
        streets: this.$t('store.streets'),
        staff_count: this.$t('store.staff_count'),
        order: this.$t('base_fields.order'),
      },
      rules: {
        agent_id: [
          { required: true, message: '请选择所属经销商', trigger: 'change' },
        ],
        name: [
          { required: true, message: '门店名称 不能为空', trigger: 'blur' },
          { min: 2, max: 20, message: '门店名称 长度在 2 到 20 个字符', trigger: 'blur' },
          { validator: validateNameUnique, trigger: 'blur' },
        ],
        area_id: [
          { required: true, message: '请选择所在地区' },
          { min: 1, message: '请选择所在地区' },
        ],
        address: [
          { required: true, message: '详细地址 不能为空', trigger: 'blur' },
          { min: 2, max: 50, message: '门店名称 长度在 2 到 20 个字符', trigger: 'blur' },
        ],
        contact_name: [
          { required: true, message: '联系人 不能为空', trigger: 'change' },
          { min: 2, max: 20, message: '联系人 长度在 2 到 20 个字符', trigger: 'blur' },
        ],
        contact_phone: [
          { required: true, message: '联系方式 不能为空', trigger: 'change' },
          { max: 30, message: '联系方式 最大长度 30 个字符', trigger: 'blur' },
        ],
        order: [
          { required: true, message: '排序编号 不能为空', trigger: 'change' },
          { type: 'integer', message: '排序编号 只能输入整数', trigger: 'blur' },
        ],
      },
    };
  },
  computed: {
    // 上传Logo图片后端路由
    uploadImageUrl() {
      return '/api/shop/store/image';
    },
  },
  created() {
    this.fetchInit();
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
          this.street_ids = this.postForm.streets.map(function(item) {
            return item.id;
          });
        })
        .catch(() => {
          // nothing
        })
        .finally(() => {
          this.loading = false;
        });
    },
    fetchInit() {
      const agentResource = new Resource('shop/store/agents');
      agentResource
        .select({})
        .then(response => {
          this.agents = response.data;
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
      // console.log(this.postForm.images);
    },
    handleImageExceed(files, fileList) {
      this.$message.warning(`当前限制选择 ${this.imageLimit} 个文件`);
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleImageRemove(file, fileList) {
      // console.log(file);
      this.postForm.images = fileList.splice(fileList.findIndex(item => item.id === file.id));
    },
    onDistrictChanged(val) {
      if (val.length === 3) {
        this.postForm.area_id = val[2];
      }
    },
    onRegionChanged(list) {
      this.street_ids = list;
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
        data['street_ids'] = this.street_ids;
        console.log((agents === undefined));
        this.loading = true;
        if (id !== undefined) {
          modelResource.update(this.postForm.id, data)
            .then(response => {
              this.$message({
                message: this.$t('options.update_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.goToList();
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
      this.$router.push({ name: 'shopStoreList' });
    },
  },
};
</script>
