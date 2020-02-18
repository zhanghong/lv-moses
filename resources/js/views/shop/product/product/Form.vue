<template>
  <div class="app-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" label-width="150px">

      <el-form-item :label="fields.name" prop="name">
        <el-input v-model.trim="postForm.name" placeholder="请输入商品名称" />
      </el-form-item>

      <el-form-item :label="fields.bar_code" prop="bar_code">
        <el-input v-model.trim="postForm.bar_code" placeholder="请输入商品条码" />
      </el-form-item>

      <el-form-item :label="fields.published_time" prop="published_time">
        <el-input v-model.trim="postForm.published_time" placeholder="请输入开售时间" />
      </el-form-item>

      <el-form-item :label="fields.category_id" prop="category_id">
        <el-select v-model="postForm.category_id" placeholder="请输选择商品分组">
          <el-option v-for="item in categories" :key="item.value" :label="item.label" :value="item.value" />
        </el-select>
      </el-form-item>

      <el-form-item :label="fields.images" prop="images">
        <el-upload
          name="image"
          accept="image/png, image/jpeg"
          :action="uploadImageUrl"
          list-type="picture-card"
          :limit="mainImageLimit"
          :file-list="postForm.images"
          :on-exceed="handleMainImageExceed"
          :on-preview="handlePictureCardPreview"
          :on-remove="handleMainImageRemove"
          :on-success="handleMainImageUpload"
          :before-upload="beforeMainImageUpload"
        >
          <i class="el-icon-plus" />
          <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过2M</div>
        </el-upload>
      </el-form-item>

      <el-form-item :label="fields.code" prop="code">
        <el-input v-model.trim="postForm.code" placeholder="请输入商家编码" />
      </el-form-item>

      <el-form-item :label="fields.group_standards">
        <div>
          <el-checkbox-group v-model="property_list">
            <div v-for="(spec, k) in specs" :key="k" class="spec_list">
              <div class="spec_name">{{ spec.name + '：' }}</div>
              <ul>
                <li v-for="(selector,key) in spec.selectors" :key="key">
                  <el-checkbox :label="spec.id+'|'+selector.name">{{ selector.name }}</el-checkbox>
                </li>
              </ul>
              <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleEditSpec(spec.id);">
                {{ $t('table.add') }}
              </el-button>
            </div>
          </el-checkbox-group>
          <el-button class="filter-item" type="primary" icon="el-icon-plus" @click="handleCreateSpec">
            {{ $t('table.add') }}
          </el-button>
        </div>
      </el-form-item>

      <el-form-item label="属性价格" class="width_auto_70">
        <div class="spec_group_title">
          <el-row>
            <el-col :span="6">名称</el-col>
            <el-col :span="6">商品价格</el-col>
            <el-col :span="6">市场价格</el-col>
            <el-col :span="6">商品库存</el-col>
          </el-row>
        </div>
        <div v-for="(v,k) in spec_group_all" v-show="v.is_chose" :key="k" class="spec_group">
          <el-row>
            <el-col :span="6">{{ v.attr_str }}</el-col>
            <el-col :span="6">
              <el-input v-model="v.price" type="number" class="spec_group_input" placeholder="0.00">
                <template slot="append">￥</template>
              </el-input>
            </el-col>
            <el-col :span="6">
              <el-input v-model="v.market_price" type="number" class="spec_group_input" placeholder="0.00">
                <template slot="append">￥</template>
              </el-input>
            </el-col>
            <el-col :span="6">
              <el-input v-model="v.num" type="number" class="spec_group_input" placeholder="0">
                <template slot="append">
                  <i class="el-icon-pie-chart" />
                </template>
              </el-input>
            </el-col>
          </el-row>
        </div>
      </el-form-item>

      <el-form-item :label="fields.description" prop="description">
        <el-input v-model.trim="postForm.description" placeholder="请输入详细介绍" />
      </el-form-item>

      <el-form-item :label="fields.desc_images" prop="desc_images">
        <el-upload
          name="image"
          accept="image/png, image/jpeg"
          :action="uploadImageUrl"
          list-type="picture-card"
          :limit="descImageLimit"
          :file-list="postForm.images"
          :on-exceed="handleDescImageExceed"
          :on-preview="handlePictureCardPreview"
          :on-remove="handleDescImageRemove"
          :on-success="handleDescImageUpload"
          :before-upload="beforeDescImageUpload"
        >
          <i class="el-icon-plus" />
          <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过2M</div>
        </el-upload>
      </el-form-item>

    </el-form>

    <el-dialog :title="propertyFormTitle" :visible.sync="propertyFormVisible">
      <div class="form-container">
        <el-form ref="propForm" :model="currentProperty" :rules="propRules" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item v-if="currentProperty.id === undefined" label="属性名" prop="name">
            <el-input v-model.trim="currentProperty.name" />
          </el-form-item>
          <el-form-item v-else label="属性名" prop="name">
            {{ currentProperty.name }}
          </el-form-item>
          <el-form-item label="规格值" prop="value">
            <el-input v-model="currentProperty.value" autocomplete="off" placeholder="请输入内容" @keyup.enter.native="addSelector" />
            <el-tag v-for="tag in currentProperty.selectors" :key="tag.name">
              {{ tag.name }}
            </el-tag>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="propertyFormVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="handlePropertySubmit()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import Resource from '@/api/resource';

const defaultForm = {

};

const defaultProperty = {
  name: '',
  vaue: '',
  selectors: [
    { id: 1, name: 'tag1' },
    { id: 2, name: 'tag2' },
    { id: 3, name: 'tag3' },
  ],
};

export default {
  name: 'Form',
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    var validateSelectorUnique = (rule, value, callback) => {
      let name = '';
      let idx = -1;

      if (value !== undefined) {
        name = value.trim();
      }

      if (name === '') {
        return false;
      }

      idx = this.currentProperty.selectors.findIndex(item => item.name === name);
      if (idx >= 0) {
        callback(new Error('规格值已存在'));
        return false;
      }

      callback();
    };

    return {
      loading: true,
      formTitle: '',
      shop_id: 1,
      tempRoute: {},
      base_category_id: 0,
      postForm: Object.assign({}, defaultForm),
      mainImageLimit: 5,
      descImageLimit: 5,
      categories: [],
      propertyFormTitle: '',
      propertyFormVisible: false,
      currentProperty: Object.assign({}, defaultProperty),
      specs: [
        { id: 100, name: '容量', selectors: [{ id: 100, name: '100ML' }, { id: 101, name: '200ML' }, { id: 102, name: '300ML' }] },
        { id: 102, name: '颜色', selectors: [{ id: 200, name: '黑' }, { id: 201, name: '红' }, { id: 202, name: '绿' }, { id: 202, name: '蓝' }] },
        { id: 105, name: 'Image', selectors: [{ id: 300, name: 'Img1' }, { id: 301, name: 'Img2' }, { id: 303, name: 'Img3' }, { id: 304, name: 'Img4' }] },
      ],
      property_list: [], // 选中属性
      spec_group_all: [], // 所有属性组合
      // spec_group: [], // 选中的属性组合
      // chose_spec_list: [], // 选择的规格
      fields: {
        group_base: this.$t('product.group_base'),
        base_category_id: this.$t('product.base_category_id'),
        name: this.$t('product.name'),
        bar_code: this.$t('product.bar_code'),
        published_time: this.$t('product.published_time'),
        category_id: this.$t('product.category_id'),
        images: this.$t('product.images'),
        code: this.$t('product.code'),
        group_standards: this.$t('product.group_standards'),
        stardards_name: this.$t('product.stardards_name'),
        stardards_values: this.$t('product.stardards_values'),
        stardards_images: this.$t('product.stardards_images'),
        stardards_add: this.$t('product.stardards_add'),
        group_stock: this.$t('product.group_stock'),
        sell_price: this.$t('product.sell_price'),
        market_price: this.$t('product.market_price'),
        sku_no: this.$t('product.sku_no'),
        sku_code: this.$t('product.sku_code'),
        group_desc: this.$t('product.group_desc'),
        description: this.$t('product.description'),
        desc_images: this.$t('product.desc_images'),
        desc_params: this.$t('product.desc_params'),
        group_delivery: this.$t('product.group_delivery'),
        delivery_template: this.$t('product.delivery_template'),
        group_services: this.$t('product.group_services'),
        service_type: this.$t('product.service_type'),
      },
      rules: {

      },
      propRules: {
        value: [
          { validator: validateSelectorUnique },
        ],
      },
    };
  },
  computed: {
    // 上传Logo图片后端路由
    uploadImageUrl() {
      return '/api/shops/' + this.shop_id + '/store/image';
    },
    modelResource() {
      return new Resource('shops' + this.shop_id + '/product/index');
    },
  },
  watch: {
    base_category_id(newVal) {
      this.loadCategoryProperty();
    },
    property_list: function(val) {
      // 选中属性的分组结果
      const chose_prop_group = [];
      // 新添加的商品规格
      const spec_diff = [];

      val.forEach(res => {
        const info = res.split('|');
        const key = 'spec_' + info[0];
        if (chose_prop_group[key] === undefined) {
          chose_prop_group[key] = [];
        }
        chose_prop_group[key].push(info[1]);
      });

      // 属性记录
      const specObjs = this.addSpecAttr(this.recursiveSort(Object.values(chose_prop_group)));

      this.spec_group_all.forEach(res => {
        res.is_chose = false;
      });

      for (const spec_a of specObjs) {
        let is_diff = true;

        for (const spec_b of this.spec_group_all) {
          if (spec_a.attr_str === spec_b.attr_str) {
            is_diff = false;
            break;
          }
        }

        if (is_diff) {
          spec_diff.push(spec_a);
        }
      }
      this.spec_group_all = this.spec_group_all.concat(spec_diff);

      specObjs.forEach(realRes => {
        this.spec_group_all.forEach(allRes => {
          if (realRes.attr_str === allRes.attr_str) {
            allRes.is_chose = true;
          }
        });
      });
    },
  },
  created() {
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      this.fetchData(id);
    } else {
      this.postForm = Object.assign({}, defaultForm);
      this.loading = false;
    }
    this.base_category_id = 708;
    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    // 添加其他属性
    addSpecAttr(data) {
      const arrs = [];
      if (this.$isEmpty(data)) {
        return arrs;
      }

      for (let i = 0; i < data.length; i++) {
        const attr = data[i];
        let attr_str = '';
        if (attr instanceof Array) {
          attr_str = attr.join(',');
        } else {
          attr_str = attr;
        }
        const obj = {
          'attr': data[i],
          'attr_str': attr_str,
          'price': 0.00,
          'market_price': 0.00,
          'num': 0,
          'is_chose': false,
        };
        arrs.push(obj);
      }
      return arrs;
    },
    addProperty(e) {

    },
    updateProperty() {

    },
    addSelector() {
      this.$refs.propForm.validateField('value', error => {
        if (!error) {
          const name = this.currentProperty.value;
          if (!this.$isEmpty(name)) {
            this.currentProperty.selectors.push({
              name,
              id: 100,
            });
            this.currentProperty.value = '';
          }
        }
      });
    },
    // 递归排列
    recursiveSort(arr) {
      const arrLen = arr.length;

      if (arrLen < 2) {
        return arr[0];
      }

      // 第一个数组的长度
      const len1 = arr[0].length;
      // 第二个数组的长度
      const len2 = arr[1].length;
      //  申明一个新数组
      const items = new Array(len1 * len2);
      let index = 0;
      for (let i = 0; i < len1; i++) {
        for (let j = 0; j < len2; j++) {
          let node1 = arr[0][i];
          const node2 = arr[1][j];
          if (!(node1 instanceof Array)) {
            node1 = [node1];
          }
          items[index++] = node1.concat(node2);
        }
      }

      const newArr = new Array(arrLen - 1);
      newArr[0] = items;
      for (let i = 2; i < arr.length; i++) {
        newArr[i - 1] = arr[i];
      }
      return this.recursiveSort(newArr);
    },
    fetchData(id) {
      this.modelResource
        .get(id)
        .then(response => {
          this.postForm = response.data;
        })
        .catch(() => {

        })
        .finally(() => {
          this.loading = false;
        });
    },
    loadCategoryProperty() {
      const cid = this.base_category_id;
      const resource = new Resource('shops' + this.shop_id + '/product/categories');
      resource
        .get(cid)
        .then(response => {
          console.log(response.data);
        })
        .catch(() => {

        });
    },
    validImageFileSize(fileSize, maxMb = 1) {
      const KbSize = fileSize / 1024; // file KB size
      let isValid = true;

      if (KbSize < 20) {
        this.$message.error('图片大小不能小于 20KB!');
        isValid = false;
      } else if (KbSize > maxMb * 1024) {
        this.$message.error('图片大小不能超过 ' + maxMb + 'M!');
        isValid = false;
      }

      return isValid;
    },
    // 主图片文件超出个数限制时的钩子
    handleMainImageExceed(files, fileList) {
      this.$message.warning(`当前限制选择 ${this.mainImageLimit} 个文件`);
    },
    // 主图片上传之前的钩子
    beforeMainImageUpload(file) {
      return this.validImageFileSize(file.size, 1);
    },
    // 主图片上传成功时的钩子
    handleMainImageUpload(res, file) {
      this.postForm.images.push(res.data);
    },
    // 主图片列表移除文件时的钩子
    handleMainImageRemove(file, fileList) {
      this.postForm.images = fileList.splice(fileList.findIndex(item => item.id === file.id));
    },
    // 介绍图片文件超出个数限制时的钩子
    handleDescImageExceed(files, fileList) {
      this.$message.warning(`当前限制选择 ${this.descImageLimit} 个文件`);
    },
    // 介绍图片上传之前的钩子
    beforeDescImageUpload(file) {
      return this.validImageFileSize(file.size, 1);
    },
    // 介绍图片上传成功时的钩子
    handleDescImageUpload(res, file) {
      this.postForm.images.push(res.data);
    },
    // 介绍图片列表移除文件时的钩子
    handleDescImageRemove(file, fileList) {
      this.postForm.images = fileList.splice(fileList.findIndex(item => item.id === file.id));
    },
    // 点击文件列表中已上传的文件时的钩子
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleEditSpec(id) {
      if (id !== undefined) {
        this.propertyFormTitle = '编辑属性';
        this.propertyFormVisible = true;
        this.currentProperty = this.specs.find(spec => spec.id === id);
      }
    },
    handleCreateSpec() {
      this.propertyFormTitle = '添加属性';
      this.propertyFormVisible = true;
      this.propertyIsEdit = false;
      this.currentProperty = Object.assign(defaultProperty);
    },
    onSubmit() {
      console.log(this.postForm);
    },
    onCancel() {
      this.loading = false;
      console.log(this.postForm);
    },
  },
};
</script>
<style lang="scss" scoped>
.spec_list{
    .spec_name{
        float: left;
        margin-right:15px;
        font-size: 14px;
    }
    ul li{
        float: left;
        margin-right:15px;
    }
}
.spec_list:after{
    clear:both;
    display: block;
    content:'';
}
.spec_group_title{
    text-align: center;
    background:#f1f1f1;
    border-radius: 4px;
    line-height: 40px;
}
.spec_group{
    text-align: center;
    margin:10px 0;
    border-bottom:1px solid #efefef;
    padding-bottom: 12px;
}
</style>
