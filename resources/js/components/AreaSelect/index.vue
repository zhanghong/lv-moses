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
  </div>
</template>

<script>
import { getDistricts } from '@/api/base/area';

export default {
  name: 'AreaSelect',
  props: {
    initValue: {
      type: Array,
      default: () => ([]),
    },
  },
  data() {
    return {
      addressData: {},
      province_id: '',
      city_id: '',
      district_id: '',
      cities: {},
      districts: {},
    };
  },
  computed: {
    provinces: function() {
      if (!this.addressData['0']) {
        return {};
      }
      return this.addressData['0'];
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
    district_id() {
      this.$emit('change', [this.province_id, this.city_id, this.district_id]);
    },
  },
  created() {
    getDistricts()
      .then(response => {
        this.addressData = response.data;
      })
      .catch(error => {
        console.log(error);
      })
      .finally(() => {
        this.setFromValue(this.initValue);
      });
  },
  methods: {
    setFromValue(value) {
      if (value.length === 0) {
        this.province_id = '';
        this.city_id = '';
        this.district_id = '';
        return;
      }
      if (value[0]) {
        this.province_id = value[0];
      } else {
        this.province_id = '';
      }

      if (value[1]) {
        this.city_id = value[1];
      } else {
        this.city_id = '';
      }

      if (value[2]) {
        this.district_id = value[2];
      } else {
        this.district_id = '';
      }
    },
  },
};
</script>
