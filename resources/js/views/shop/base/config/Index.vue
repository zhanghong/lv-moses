<template>
  <div class="app-container">
    <el-form ref="shop" :model="shop" :rules="rules" label-width="150px">
      <el-form-item :label="fields.name" prop="name">
        <el-input v-if="isEdit" v-model="shop.name" placeholder="请输入店铺名称" />
        <div v-else>{{ shop.name }}</div>
      </el-form-item>
      <el-form-item :label="fields.main_image_url" prop="main_image_url">
        <el-upload v-if="isEdit" class="avatar-uploader" :action="uploadMainImageUrl" name="image" accept="image/png, image/jpeg" :show-file-list="false" :on-success="handleMainImageSuccess" :before-upload="beforeMainImageUpload">
          <img v-if="shop.main_image_url" :src="shop.main_image_url" class="img-avatar">
          <i v-else class="el-icon-plus uploader-icon uploader-icon-avatar" />
        </el-upload>
        <el-image v-else-if="shop.main_image_url.length" class="img-avatar" :src="shop.main_image_url" />
        <el-image v-else class="img-avatar" />
      </el-form-item>
      <el-form-item :label="fields.banner_url" prop="banner_url">
        <el-upload v-if="isEdit" class="banner-uploader" :action="uploadBannerImageUrl" name="image" accept="image/png, image/jpeg" :show-file-list="false" :on-success="handleBannerSuccess" :before-upload="beforeBannerUpload">
          <img v-if="shop.banner_url" :src="shop.banner_url" class="img-banner">
          <i v-else class="el-icon-plus uploader-icon uploader-icon-banner" />
        </el-upload>
        <el-image v-else-if="shop.banner_url.length" class="img-banner" :src="shop.banner_url" />
        <el-image v-else class="img-banner" />
      </el-form-item>
      <el-form-item :label="fields.introduce" prop="introduce">
        <el-input v-if="isEdit" v-model="shop.introduce" type="textarea" placeholder="请输入店铺介绍信息" />
        <div v-else>{{ shop.introduce }}</div>
      </el-form-item>
      <el-form-item :label="fields.seo_keywords" prop="seo_keywords">
        <el-input v-if="isEdit" v-model="shop.seo_keywords" type="textarea" placeholder="请输入SEO搜索关键词" />
        <div v-else>{{ shop.seo_keywords }}</div>
      </el-form-item>
      <el-form-item :label="fields.seo_description" prop="seo_description">
        <el-input v-if="isEdit" v-model="shop.seo_description" type="textarea" placeholder="请输入SEO描述" />
        <div v-else>{{ shop.seo_description }}</div>
      </el-form-item>
      <el-form-item v-if="isEdit">
        <el-button type="primary" @click="onSubmit">
          提交
        </el-button>
        <el-button @click="onCancel">
          取消
        </el-button>
      </el-form-item>
      <el-form-item v-else>
        <el-button type="primary" @click="showFrom">
          编辑
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getShop, updateShop, checkNameUnique } from '@/api/shop/base/config';

const defaultShop = {
  name: '',
  main_image_id: '',
  main_image_url: '',
  banner_id: '',
  banner_url: '',
  introduce: '',
  seo_keywords: '',
  seo_description: '',
  can_update: true,
};

export default {
  data() {
    var validateNameUnique = (rule, value, callback) => {
      checkNameUnique(this.shop.name)
        .then(response => {
          if (response.code !== 200) {
            callback(new Error('店铺名称 已存在'));
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
      shop: Object.assign({}, defaultShop),
      loading: false,
      isEdit: false,
      fields: {
        name: this.$t('shop.name'),
        main_image_url: this.$t('shop.main_image_url'),
        banner_url: this.$t('shop.banner_url'),
        introduce: this.$t('shop.introduce'),
        seo_keywords: this.$t('shop.seo_keywords'),
        seo_description: this.$t('shop.seo_description'),
      },
      rules: {
        name: [
          { required: true, message: '店铺名称不能为空', trigger: 'blur' },
          { min: 2, max: 20, message: '店铺名称长度在 2 到 20 个字符', trigger: 'blur' },
          { validator: validateNameUnique, trigger: 'blur' },
        ],
        main_image_url: [
          { required: true, message: 'Logo 图片不能为空', trigger: 'blur' },
        ],
        banner_url: [
          { required: true, message: 'Banner 图片不能为空', trigger: 'blur' },
        ],
        introduce: [
          { required: true, message: '店铺简介不能为空', trigger: 'blur' },
          { min: 10, max: 150, message: '店铺名称长度在 10 到 150 个字符', trigger: 'blur' },
        ],
        seo_keywords: [
          { max: 100, message: 'SEO关键词长度在 10 到 150 个字符', trigger: 'blur' },
        ],
        seo_description: [
          { max: 150, message: 'SEO描述长度在 10 到 150 个字符', trigger: 'blur' },
        ],
      },
    };
  },
  computed: {
    // 上传Logo图片后端路由
    uploadMainImageUrl() {
      return '/api/shop/base/index/main_image';
    },
    // 上传Banner图片后端路由
    uploadBannerImageUrl() {
      return '/api/shop/base/index/banner_image';
    },
  },
  created() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      getShop()
        .then(response => {
          this.shop = response.data;
          this.isEdit = false;
        })
        .catch(() => {
          // nothing
        });
    },
    showFrom() {
      this.isEdit = true;
    },
    onSubmit() {
      // console.log(this.shop);
      this.$refs.shop.validate(valid => {
        if (valid) {
          this.loading = true;
          updateShop(this.shop)
            .then(response => {
              this.$message('店铺信息更新成功');
              this.loading = false;
              this.isEdit = false;
            })
            .catch(() => {
              this.loading = false;
              this.$message('店铺信息更新失败');
            });
        } else {
          return false;
        }
      });
    },
    onCancel() {
      this.fetchData();
    },
    beforeMainImageUpload(file) {
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
    handleMainImageSuccess(res, file) {
      this.shop.main_image_id = res.data.id;
      this.shop.main_image_url = res.data.url;
    },
    beforeBannerUpload(file) {
      const fileKbSize = file.size / 1024; // file KB size
      let isValid = true;

      if (fileKbSize < 20) {
        this.$message.error('Banner 图片大小不能小于 20KB!');
        isValid = false;
      } else if (fileKbSize > 5120) {
        this.$message.error('Banner 图片大小不能超过 5M!');
        isValid = false;
      }

      return isValid;
    },
    handleBannerSuccess(res, file) {
      this.shop.banner_id = res.data.id;
      this.shop.banner_url = res.data.url;
    },
  },
};
</script>

<style scoped>
.line {
  text-align: center;
}
.avatar-uploader .el-upload,
.banner-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover,
.banner-uploader .el-upload:hover {
  border-color: #409EFF;
}
.uploader-icon {
  font-size: 28px;
  color: #8c939d;
  text-align: center;
}
.uploader-icon-avatar {
  width: 100px;
  height: 100px;
  line-height: 100px;
}
.uploader-icon-banner {
  width: 450px;
  height: 100px;
  line-height: 100px;
}
.img-avatar {
  width: 100px;
  height: 100px;
  display: block;
}
.img-banner {
  width: 450px;
  height: 100px;
  display: block;
}
</style>
