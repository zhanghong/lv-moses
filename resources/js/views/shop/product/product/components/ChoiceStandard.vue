<template>
  <div class="container">
    <div class="modal-header">
      <div class="header-left">
        <el-button type="primary" icon="el-icon-plus" @click="changeStandards">选择确定</el-button>
      </div>
    </div>
    <div class="modal-body">
      <el-table ref="table" :data="pageList" @selection-change="handleSelectionChange">
        <el-table-column type="selection" :selectable="selectable" />
        <el-table-column :label="fields.name" width="80">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
          </template>
        </el-table-column>
        <el-table-column :label="fields.selectors">
          <template slot-scope="scope">
            <el-tag v-for="tag in scope.row.selectors" :key="tag.id">
              {{ tag.name }}
            </el-tag>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const modelResource = new Resource('shop/product/standards');

export default {
  name: 'ChoiceStandard',
  props: {
    initChoiced: {
      type: Array,
      default: () => ([]),
    },
  },
  data() {
    const initList = Array.from(this.initChoiced);

    return {
      initList,
      pageList: [],
      choicedList: [],
      fields: {
        name: this.$t('product_standard.name'),
        selectors: this.$t('product_standard.selectors'),
        order: this.$t('base_fields.order'),
      },
    };
  },
  created() {
    this.getList();
  },
  updated() {
    for (const row of this.pageList) {
      const initIndex = this.initList.findIndex((item) => (item.id === row.id));
      if (initIndex >= 0) {
        this.$refs.table.toggleRowSelection(row, true);
      }
    }
  },
  methods: {
    async getList() {
      const { data } = await modelResource.list({});
      this.pageList = data;
    },
    handleSelectionChange(e) {
      this.choicedList = e;
    },
    changeStandards() {
      this.$emit('change', this.choicedList);
    },
    selectable(row, index) {
      const initIndex = this.initList.findIndex((item) => (item.id === row.id));
      if (initIndex >= 0){
        // 已选定的属性不能再修改
        return false;
      }

      return true;
    },
  },
};
</script>
