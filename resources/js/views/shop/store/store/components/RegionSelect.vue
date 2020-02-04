<template>
  <div class="linkage">
    <el-select v-model="province_id">
      <el-option label="选择省" value="" disabled />
      <el-option
        v-for="(name, id) in provinces"
        :key="id"
        :label="name"
        :value="id"
      />
    </el-select>
    <el-select v-model="city_id">
      <el-option label="选择市" value="" disabled />
      <el-option
        v-for="(name, id) in cities"
        :key="id"
        :label="name"
        :value="id"
      />
    </el-select>
    <el-select v-model="district_id">
      <el-option label="选择区" value="" disabled />
      <el-option
        v-for="(name, id) in districts"
        :key="id"
        :label="name"
        :value="id"
      />
    </el-select>
    <el-select v-model="street_id">
      <el-option label="选择街道" value="" disabled />
      <el-option
        v-for="(name, id) in streets"
        :key="id"
        :label="name"
        :value="id"
      />
    </el-select>
    <el-button v-if="show_add_btn" type="text" @click="addRegion">添加</el-button>
    <ul id="street-list">
      <li v-for="item in list" :key="item.id">
        {{ item.text }} <el-button type="text" @click="delRegion(item.id)">删除</el-button>
      </li>
    </ul>
  </div>
</template>

<script>
import { getDistricts, getStreets } from '@/api/base/area';

export default {
  name: 'RegionSelect',
  props: {
    list: {
      type: Array,
      default: () => ([]),
    },
  },
  data() {
    return {
      province_id: '',
      city_id: '',
      district_id: '',
      street_id: '',
      addressData: {},
      cities: {},
      districts: {},
      streets: {},
    };
  },
  computed: {
    provinces: function() {
      if (!this.addressData['0']) {
        return {};
      }
      return this.addressData['0'];
    },
    show_add_btn: function() {
      if (this.street_id === '') {
        return false;
      } else if (this.list.findIndex(item => item.id === this.street_id) >= 0) {
        return false;
      }
      return true;
    },
    active_ids: function() {
      return this.list.map(function(item) {
        return item.id;
      });
    },
  },
  watch: {
    province_id(newVal) {
      if (!newVal) {
        this.cities = {};
        this.city_id = '';
        return;
      }
      if (!this.addressData[newVal]) {
        this.cities = {};
      } else {
        this.cities = this.addressData[newVal];
      }
      if (!this.cities[this.city_id]) {
        this.city_id = '';
      }
    },
    city_id(newVal) {
      if (!newVal) {
        this.districts = {};
        this.district_id = '';
        return;
      }

      if (!this.addressData[newVal]) {
        this.districts = {};
      } else {
        this.districts = this.addressData[newVal];
      }
      if (!this.districts[this.district_id]) {
        this.district_id = '';
      }
    },
    district_id(newVal) {
      this.getDistrictStreets(newVal);
    },
  },
  created() {
    getDistricts()
      .then(response => {
        // 获取省、市、区三级数据
        this.addressData = response.data;
      })
      .catch(() => {

      });
  },
  methods: {
    getDistrictStreets(value) {
      if (!value) {
        this.streets = {};
        this.street_id = '';
        return;
      }

      getStreets(value)
        .then(response => {
          if (response.code !== 200) {
            this.streets = {};
          } else {
            this.streets = response.data;
          }
        })
        .catch(() => {
          this.streets = {};
        })
        .finally(() => {
          this.street_id = '';
        });
    },
    addRegion() {
      // 添加管辖区域
      const names = [];

      if (this.province_id === '') {
        return;
      } else if (!this.provinces[this.province_id]) {
        return;
      }
      names.push(this.provinces[this.province_id]);

      if (this.city_id === '') {
        return;
      } else if (!this.cities[this.city_id]) {
        return;
      }
      names.push(this.cities[this.city_id]);

      if (this.district_id === '') {
        return;
      } else if (!this.districts[this.district_id]) {
        return;
      }
      names.push(this.districts[this.district_id]);

      if (this.street_id === '') {
        return;
      } else if (!this.streets[this.street_id]) {
        return;
      } else if (this.list.findIndex(item => item.id === this.street_id) >= 0) {
        return;
      }

      names.push(this.streets[this.street_id]);

      this.list.push({ id: this.street_id, text: names.join('-') });
      this.$emit('change', this.active_ids);

      return;
    },
    delRegion(id) {
      // 删除管辖区域
      this.list.splice(this.list.findIndex(item => item.id === id), 1);
      this.$emit('change', this.active_ids);

      return;
    },
  },
};
</script>
