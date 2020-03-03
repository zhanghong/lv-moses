<template>
  <div class="app-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" label-width="150px">

      <el-form-item :label="fields.title" prop="title">
        <el-input v-model.trim="postForm.title" placeholder="请输入商品名" />
      </el-form-item>

      <el-form-item :label="fields.long_title" prop="long_title">
        <el-input v-model.trim="postForm.long_title" placeholder="请输入商品副标题" type="textarea" :autosize="{ minRows: 2, maxRows: 4}" />
      </el-form-item>

      <el-form-item :label="fields.bar_code" prop="bar_code">
        <el-input v-model.trim="postForm.bar_code" placeholder="请输入商品编号" />
      </el-form-item>

      <el-form-item :label="fields.brand_id" prop="brand_id">
        <el-select v-model="postForm.brand_id" placeholder="请输选择商品品牌">
          <el-option v-for="item in brands" :key="item.id" :label="item.name" :value="item.id" />
        </el-select>
      </el-form-item>

      <el-form-item :label="fields.group_id" prop="group_id">
        <el-select v-model="postForm.group_id" placeholder="请输选择商品分组">
          <el-option v-for="item in groups" :key="item.id" :label="item.name" :value="item.id" />
        </el-select>
      </el-form-item>

      <el-form-item :label="fields.images" prop="images">
        <el-upload
          name="image"
          accept="image/png, image/jpeg"
          :action="uploadMainImageUrl"
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

      <el-form-item :label="fields.sell_price" prop="sell_price">
        <el-input v-model.number="postForm.sell_price" :disabled="hasActiveSkus" placeholder="0.0" />
      </el-form-item>

      <el-form-item :label="fields.market_price" prop="market_price">
        <el-input v-model.number="postForm.market_price" :disabled="hasActiveSkus" placeholder="0.0" />
      </el-form-item>

      <el-form-item :label="fields.sku_stock" prop="stock">
        <el-input v-model.number="postForm.stock" :disabled="hasActiveSkus" placeholder="0" />
      </el-form-item>

      <el-form-item :label="fields.group_standards" class="width-auto">
        <div>
          <el-checkbox-group v-model="choicedStardardSelectors">
            <div v-for="stardard in choicedStardards" :key="stardard.id" class="stardard-item">
              <div class="stardard-name">{{ stardard.name }}：</div>
              <ul>
                <li v-for="selector in stardard.selectors" :key="selector.id">
                  <el-checkbox :label="stardard.id+'|'+selector.id" :checked="selector.checked" :disabled="selector.locked">{{ selector.name }}</el-checkbox>
                </li>
              </ul>
            </div>
          </el-checkbox-group>
        </div>
        <div v-show="choicedStardards.length" class="clear" />
        <div v-show="canAddStardards" class="btn-choice-standard">
          <el-button type="primary" @click="handleChoiceStandard">  {{ fields.stardards_add }}
          </el-button>
        </div>
      </el-form-item>

      <el-form-item :label="fields.group_stock" class="width_auto_70">
        <div class="spec_group_title">
          <el-row>
            <el-col :span="4">{{ fields.sku_name }}</el-col>
            <el-col :span="4">{{ fields.sell_price }}</el-col>
            <el-col :span="4">{{ fields.market_price }}</el-col>
            <el-col :span="4">{{ fields.sku_stock }}</el-col>
            <el-col :span="4">{{ fields.sku_code }}</el-col>
          </el-row>
        </div>
        <div v-for="(v, k) in postForm.skus" v-show="v.is_active" :key="'sku_' + k" class="spec_group">
          <el-row>
            <el-col :span="4">{{ v.selector_names }}</el-col>
            <el-col :span="4">
              <el-form-item :key="'skus.' + k + '.sell_price'" :prop="'skus.' + k + '.sell_price'" label="">
                <el-input
                  v-model.number="v.sell_price"
                  class="spec_group_input"
                  placeholder="0.00"
                >
                  <template slot="append">￥</template>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :span="4">
              <el-form-item :key="'skus.' + k + '.market_price'" :prop="'skus.' + k + '.market_price'" label="">
                <el-input
                  v-model.number="v.market_price"
                  class="spec_group_input"
                  placeholder="0.00"
                >
                  <template slot="append">￥</template>
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :span="4">
              <el-form-item :key="'skus.' + k + '.stock'" :prop="'skus.' + k + '.stock'" label="">
                <el-input
                  v-model.number="v.stock"
                  class="spec_group_input"
                  placeholder="0"
                >
                  <i class="el-icon-pie-chart" />
                </el-input>
              </el-form-item>
            </el-col>
            <el-col :span="4">
              <el-form-item :key="'skus.' + k + '.code'" :prop="'skus.' + k + '.code'" label="">
                <el-input
                  v-model.number="v.code"
                  class="spec_group_input"
                  placeholder=""
                />
              </el-form-item>
            </el-col>
          </el-row>
        </div>
      </el-form-item>

      <el-form-item :label="fields.description" prop="description">
        <el-input v-model.trim="postForm.description" placeholder="请输入详细介绍" type="textarea" :autosize="{ minRows: 3, maxRows: 8}" />
      </el-form-item>

      <el-form-item :label="fields.desc_images" prop="desc_images">
        <el-upload
          name="image"
          accept="image/png, image/jpeg"
          :action="uploadDescImageUrl"
          list-type="picture-card"
          :limit="descImageLimit"
          :file-list="postForm.desc_images"
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

      <el-form-item :label="fields.is_published" prop="is_published">
        <el-switch v-model="postForm.is_published" active-color="#13ce66" inactive-color="#dcdfe6" />
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

    <el-dialog title="图片预览" :visible.sync="dialogImageVisible">
      <img width="100%" :src="dialogImageUrl" alt="">
    </el-dialog>

    <!-- dialog -->
    <el-dialog title="选择属性规格" :visible.sync="dialogChoiceStandardVisible">
      <choice-standard :init-choiced="choicedStardards" @change="changeChoicedStardards" />
    </el-dialog>
  </div>
</template>
<script>
// import 'vue/html-self-closing'
import Resource from '@/api/resource';
import ChoiceStandard from './components/ChoiceStandard';

const modelResource = new Resource('shop/product/index');

const defaultForm = {
  title: '', // 商品名
  long_title: '', // 副标题
  group_id: '', // 商品分组
  brand_id: '', // 商品品牌
  images: [], // 主图片列表
  skus: [], // skus
  desc_images: [], // 介绍图片列表
  description: '', // 介绍信息
  sell_price: '', // 现价
  market_price: '', // 市场价
  stock: '', // 库存
  is_published: false, // 是否发布
  order: 99, // 排序编号
};

export default {
  name: 'Form',
  components: {
    ChoiceStandard,
  },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      loadingForm: true, // 是否加载表单数据
      loadingModel: true, // 是否加载Model数据
      formTitle: '', // 页面标题
      groups: [], // 商品分组
      brands: [], // 商品品牌
      tempRoute: {},
      postForm: Object.assign({}, defaultForm),
      mainImageLimit: 5, // 主图片数量限制
      uploadMainImageUrl: '/api/shop/product/images/main', // 上传主图片后端路由
      descImageLimit: 5, // 介绍图片数量限制
      uploadDescImageUrl: '/api/shop/product/images/desc', // 上传介绍图片后端路由
      dialogImageVisible: false, // 是否显示预览图片弹出框
      dialogImageUrl: '', // 预览图片URL
      canAddStardards: false, // 是否可以添加新的属性规格
      dialogChoiceStandardVisible: false, // 是否显示选择规格弹出框
      choicedStardards: [], // 已选择的规格列表
      choicedStardardSelectors: [], // 已选择的规格属性列表
      fields: {
        group_base: this.$t('product.group_base'),
        title: this.$t('product.title'),
        long_title: this.$t('product.long_title'),
        bar_code: this.$t('product.bar_code'),
        published_time: this.$t('product.published_time'),
        brand_id: this.$t('product.brand_id'),
        group_id: this.$t('product.group_id'),
        images: this.$t('product.images'),
        code: this.$t('product.code'),
        group_standards: this.$t('product.group_standards'),
        stardards_name: this.$t('product.stardards_name'),
        stardards_values: this.$t('product.stardards_values'),
        stardards_images: this.$t('product.stardards_images'),
        stardards_add: this.$t('product.stardards_add'),
        group_stock: this.$t('product.group_stock'),
        fiction_count: this.$t('product.fiction_count'),
        sell_price: this.$t('product.sell_price'),
        market_price: this.$t('product.market_price'),
        sku_name: this.$t('product.sku_name'),
        sku_stock: this.$t('product.sku_stock'),
        sku_no: this.$t('product.sku_no'),
        sku_code: this.$t('product.sku_code'),
        is_published: this.$t('product.is_published'),
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
        title: [
          { required: true, min: 5, max: 50, message: '商品名 长度在 5 到 50 个字符', trigger: 'blur' },
        ],
        long_title: [
          { max: 150, message: '商品名 长度不能超过 150 个字符', trigger: 'blur' },
        ],
        brand_id: [
          { required: true, message: '请选择 商品品牌', trigger: 'change' },
        ],
        group_id: [
          { required: true, message: '请选择 商品分组', trigger: 'change' },
        ],
        images: [
          { required: true, message: '商品主图片 不能为空' },
        ],
        description: [
          { required: true, min: 5, max: 2000, message: '详细介绍 长度在 5 到 2000 个字符', trigger: 'blur' },
        ],
      },
    };
  },
  computed: {
    loading() {
      return this.loadingForm && this.loadingModel;
    },
    choicedStandardSelectors() {
      const list = [];
      for (const standard of this.choicedStardards) {
        for (const selector of standard.selectors) {
          const { id, name } = selector;
          const key = this.selectorKey(id);
          list[key] = name;
        }
      }
      return list;
    },
    hasActiveSkus() {
      return this.postForm.skus.some(sku => sku.is_active);
    },
    activeSkus() {
      return this.postForm.skus.filter(sku => sku.is_active);
    },
  },
  watch: {
    choicedStardardSelectors: function(val) {
      // 选中属性的分组结果
      const choiceStandardGroup = [];
      // 新添加的商品规格
      const standardDiff = [];
      const skus = this.postForm.skus;

      val.forEach(res => {
        const info = res.split('|');
        const key = 'sku_' + info[0];
        if (choiceStandardGroup[key] === undefined) {
          choiceStandardGroup[key] = [];
        }
        choiceStandardGroup[key].push(info[1]);
      });

      // 属性记录
      const specObjs = this.addSpecAttr(this.recursiveSort(Object.values(choiceStandardGroup)));

      skus.forEach(res => {
        res.is_active = false;
      });

      for (const spec_a of specObjs) {
        let is_diff = true;

        for (const spec_b of skus) {
          if (spec_a.selector_ids_str === spec_b.selector_ids_str) {
            is_diff = false;
            break;
          }
        }

        if (is_diff) {
          standardDiff.push(spec_a);
        }
      }

      this.postForm.skus = Array.from(skus.concat(standardDiff));

      specObjs.forEach(spec => {
        this.postForm.skus.forEach(sku => {
          if (spec.selector_names === sku.selector_names) {
            sku.is_active = true;
          }
        });
      });

      this.setSkusRules();
    },
  },
  created() {
    this.fetchFormData();
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      this.fetchData(id);
    } else {
      // 可以添加新的属性规格
      this.canAddStardards = true;
      this.postForm = Object.assign({}, defaultForm);
      this.loadingModel = false;
    }
    // 设置SKU属性验证规则
    this.setSkusRules();
    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    // 加载表单初始数据
    fetchFormData() {
      let id = 0;
      if (this.isEdit) {
        id = this.$route.params && this.$route.params.id;
      }
      modelResource
        .request('post', 'form', null, { id: id })
        .then(response => {
          const data = response.data;
          this.brands = data.brands;
          this.groups = data.groups;
          this.choicedStardards = data.choicedStardards;
          if (!this.choicedStardards.length) {
            // 编辑时只有商品之前没有属性规格时才可以添加新的属性规格
            this.canAddStardards = true;
          }
        });
    },
    // 加载Model实例记录
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
          this.loadingModel = false;
        });
    },
    // 显示规格选择对话框
    handleChoiceStandard() {
      this.dialogChoiceStandardVisible = true;
    },
    // 更新选中的商品规格列表
    changeChoicedStardards(list) {
      this.dialogChoiceStandardVisible = false;
      this.choicedStardards = list;
    },
    selectorKey(id) {
      return 's_' + id;
    },
    // 添加规格属性
    addSpecAttr(data) {
      const arrs = [];
      if (this.$isEmpty(data)) {
        return arrs;
      }

      for (let i = 0; i < data.length; i++) {
        const attr = data[i];
        const selectorNames = [];
        let selectorIds = [];
        if (attr instanceof Array) {
          selectorIds = attr;
        } else {
          selectorIds.push(Number.parseInt(attr));
        }
        // 对属性值ID进行升序排列
        const sortSelectorIds = selectorIds.sort((a, b) => {
          return a - b;
        });

        for (const id of sortSelectorIds) {
          const key = this.selectorKey(id);
          let name = this.choicedStandardSelectors[key];
          if (name === undefined) {
            name = '--';
          }

          selectorNames.push(name);
        }

        const obj = {
          'selector_ids': sortSelectorIds,
          'selector_ids_str': sortSelectorIds.join('|'),
          'selector_names': selectorNames.join('|'),
          'sell_price': '',
          'market_price': '',
          'cost_price': '',
          'integral': 0,
          'stock': '',
          'is_active': false, // sku是否有效
        };
        arrs.push(obj);
      }
      return arrs;
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
    setSkusRules() {
      if (this.hasActiveSkus) {
        this.rules['sell_price'] = [];
        this.rules['market_price'] = [];
        this.rules['stock'] = [];
      } else {
        this.rules['sell_price'] = [
          { type: 'number', required: true, min: 0, message: '不能为空', trigger: 'blur' },
        ];
        this.rules['market_price'] = [
          { type: 'number', min: 0, message: '不能低于0元', trigger: 'blur' },
        ];
        this.rules['stock'] = [
          { type: 'number', required: true, min: 0, message: '不能为空', trigger: 'blur' },
        ];
      }

      if (this.postForm.skus && this.postForm.skus.length) {
        this.postForm.skus.forEach((sku, i) => {
          const keySellPrice = 'skus.' + i + '.sell_price';
          const keyMarketPrice = 'skus.' + i + '.market_price';
          const keyStock = 'skus.' + i + '.stock';
          const keyCode = 'skus.' + i + '.code';
          if (sku.is_active) {
            this.rules[keySellPrice] = [
              { type: 'number', required: true, min: 0, message: '不能为空', trigger: 'blur' },
            ];
            this.rules[keyMarketPrice] = [
              { type: 'number', min: 0, message: '不能低于0元', trigger: 'blur' },
            ];
            this.rules[keyStock] = [
              { type: 'integer', required: true, min: 0, message: '不能为空', trigger: 'blur' },
            ];
            this.rules[keyCode] = [
              { max: 20, message: '长度必须小于20个字符', trigger: 'blur' },
            ];
          } else {
            this.rules[keySellPrice] = [];
            this.rules[keyMarketPrice] = [];
            this.rules[keyStock] = [];
            this.rules[keyCode] = [];
          }
        });
      }
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
      return this.validImageFileSize(file.size, 5);
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
      return this.validImageFileSize(file.size, 5);
    },
    // 介绍图片上传成功时的钩子
    handleDescImageUpload(res, file) {
      this.postForm.desc_image.push(res.data);
    },
    // 介绍图片列表移除文件时的钩子
    handleDescImageRemove(file, fileList) {
      this.postForm.desc_image = fileList.splice(fileList.findIndex(item => item.id === file.id));
    },
    // 点击文件列表中已上传的文件时的钩子
    handlePictureCardPreview(file) {
      if (!this.$isEmpty(file.url)) {
        this.dialogImageUrl = file.url;
        this.dialogImageVisible = true;
      }
    },
    onSubmit() {
      this.$refs.postForm.validate(valid => {
        if (!valid) {
          return false;
        }

        const { id, images, desc_images, ...data } = this.postForm;
        data['skus'] = this.activeSkus;
        data['image_ids'] = images.map((img) => {
          return img.id;
        });
        data['desc_image_ids'] = desc_images.map((img) => {
          return img.id;
        });

        this.loadingModel = true;
        if (id !== undefined) {
          modelResource.update(this.postForm.id, data)
            .then(response => {
              this.$message({
                message: this.$t('options.update_success'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.goToList();
            }).catch(() => {
              this.loadingModel = false;
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
              this.loadingModel = false;
            });
        }
      });
    },
    onCancel() {
      this.goToList();
    },
    goToList() {
      if (this.loadingModel) {
        this.loadingModel = false;
      }
      this.$router.push({ name: 'shopNormalProductList' });
    },
  },
};
</script>
<style lang="scss" scoped>
.stardard-item{
    .stardard-name{
        float: left;
        margin-right:15px;
        font-size: 14px;
    }
    ul li{
        float: left;
        margin-right:15px;
    }
}
.stardard-item:after{
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
