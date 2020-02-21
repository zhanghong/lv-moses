<template>
  <div class="app-container">
    <div class="filter-container">
      选择类目
    </div>

    <div class="choice-container-bg">
      <div class="choice-notices">
        商品创建后，类目不可修改
      </div>
      <div class="choice-box">
        <el-scrollbar>
          <ul>
            <li v-for="cat in firsts" :key="cat.id" :class="cat.id===cid_1 ? 'active' : ''" @click="choseFirst(cat)">
              <i>></i>{{ cat.name }}
            </li>
          </ul>
        </el-scrollbar>
      </div>

      <div class="choice-box" :style="cid_1===0 ? 'background:#fcfcfc;' : ''">
        <el-scrollbar>
          <ul>
            <li v-for="cat in seconds" :key="cat.id" :class="cat.id===cid_2 ? 'active' : ''" @click="choseSecond(cat)">
              <i>></i>{{ cat.name }}
            </li>
          </ul>
        </el-scrollbar>
      </div>

      <div class="choice-box" :style="cid_2===0 ? 'background:#fcfcfc;' : ''">
        <el-scrollbar>
          <ul>
            <li v-for="cat in thirds" :key="cat.id" :class="cat.id===cid_3 ? 'active' : ''" @click="choseThird(cat)">
              {{ cat.name }}
            </li>
          </ul>
        </el-scrollbar>
      </div>
    </div>

    <div class="choice-tags">
      <el-tag type="success">
        您当前选择的商品类别是： {{ choices.join('&nbsp;&nbsp;&nbsp;') }}
      </el-tag>
    </div>

    <div class="choice-opts">
      <el-button type="default" @click="backToList">
        取消
      </el-button>
      <el-button type="primary" :disabled="cid_3===0" @click="nextStep">
        下一步
      </el-button>

    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const shop_id = 1;
const modelResource = new Resource('shops/' + shop_id + '/category/index');

export default {
  name: 'CategoryChoice',
  data() {
    return {
      cid_1: 0,
      cid_2: 0,
      cid_3: 0,
      firsts: [],
      seconds: [],
      thirds: [],
      choices: [],
    };
  },
  created() {
    this.getFirstsList();
  },
  methods: {
    getFirstsList() {
      modelResource.list({ pid: 1 }).then(response => {
        this.firsts = response.data;
        this.seconds = [];
        this.thirds = [];
      });
    },
    getSecondsList(pid) {
      if (this.cid_1 < 1) {
        this.seconds = [];
        this.thirds = [];
        return;
      }
      modelResource.list({ pid: this.cid_1 }).then(response => {
        this.seconds = response.data;
        this.thirds = [];
      });
    },
    getThirdsList(pid) {
      if (this.cid_2 < 1) {
        this.thirds = [];
        return;
      }
      modelResource.list({ pid: this.cid_2 }).then(response => {
        this.thirds = response.data;
      });
    },
    choseFirst(cat) {
      if (cat.id < 1) {
        return;
      }
      this.cid_1 = cat.id;
      this.cid_2 = 0;
      this.cid_3 = 0;
      this.choices = [cat.name];
      this.getSecondsList(this.cat_id);
    },
    choseSecond(cat) {
      if (this.cat_id < 1) {
        return;
      }
      this.cid_2 = cat.id;

      if (this.choices.length > 2) {
        this.choices.splice(2, 1);
      }
      this.choices[1] = cat.name;
      this.cid_3 = 0;
      this.getThirdsList(this.cid_2);
    },
    choseThird(cat) {
      if (this.cat_id < 1) {
        return;
      }
      this.choices[2] = cat.name;
      this.cid_3 = cat.id;
    },
    backToList() {
      this.$router.push({ name: 'ProductManage' });
    },
    nextStep() {
      if (this.cid_3 < 1) {
        this.$message.error('请先选择栏目');
        return;
      }

      this.$router.push('/shop/product/create/' + this.cid_3);
    },
  },
};
</script>
<style lang="scss" scoped>
.choice-container-bg{
  background:#fafafa;
  padding:40px;
  width: 1000px;
  margin:0 auto;
  border:1px solid #eee;
  border-radius: 5px;
}
.choice-notices {
  padding-bottom: 15px;
  font-size: 14px;
  color: red;
}
.choice-box{
  height: 400px;
  float: left;
  width: 30%;
  background: #fff;
  margin-right: 5%;
  box-sizing: border-box;
  border:1px solid #efefef;
  border-radius: 5px;
  color:#666;
  font-size: 12px;
  .el-scrollbar{
    height: 100%;
  }
}
.choice-box ul{
  padding:10px;
  li{
    line-height: 30px;
    margin-bottom: 10px;
    padding-left: 8px;
    box-sizing: border-box;
    border:1px solid #fff;
    i{
      float:right;
      padding-right: 8px;
    }
  }
  li.active{
    box-sizing: border-box;
    border:1px solid #d9ecff;
    background: #ecf5ff;
    color:#409eff;
    border-radius: 3px;
  }
}

.choice-box:last-child{
  margin-right: 0;
}
.choice-container-bg:after{
  content:'';
  display: block;
  clear:both;
}
.choice-tags{
  text-align: center;
  margin-top:30px;
}
.choice-opts{
  margin-top:30px;
  text-align: center;
}
</style>
