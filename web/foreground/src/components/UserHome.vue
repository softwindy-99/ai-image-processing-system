<template>
  <div class="container">
    <div class="UserHome">
      <h2>个人中心</h2>
      <el-card class="box-card user-info" shadow="never">
        <template #header>
          <b>总览</b>
        </template>
        <div class="card-content">
          <div id="pie-doughnut"></div>
          <div id="cate-gory"></div>
        </div>
      </el-card>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import * as echarts from "echarts";
export default defineComponent({
  name: 'UserHome',
  methods: {
    createPie() {
      type EChartsOption = echarts.EChartsOption;
      let option: EChartsOption;
      let myChart = echarts.init(document.getElementById("pie-doughnut")!);
      option = {
        title: {
          text: "各功能使用情况",
          subtext: '总使用次数',
          left: "center"
        },
        tooltip: {
          trigger: 'item',
          formatter: '{b0}: {c0} 次'
        },
        legend: {
          top: '15%',
          left: 'center'
        },

        series: [
          {
            name: '各功能使用情况',
            type: 'pie',
            radius: ['35%', '50%'],
            avoidLabelOverlap: false,
            labelLine: {
              show: false
            },
            label: {
              show: false,
            },
            data: [
              { value: 1048, name: '色彩还原' },
              { value: 735, name: '图像修复' },
              { value: 580, name: '图像放大' },
            ]
          }
        ]
      };
      option && myChart.setOption(option);
    },
    createdCategory() {
      type EChartsOption = echarts.EChartsOption;

      let chartDom = document.getElementById('cate-gory')!;
      let myChart = echarts.init(chartDom);
      let option: EChartsOption;

      option = {
        xAxis: {
          type: 'category',
          data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            data: [150, 230, 224, 218, 135, 147, 260],
            type: 'line'
          }
        ]
      };
      option && myChart.setOption(option);
    }
  },
  mounted() {
    this.createPie();
    this.createdCategory();
  }
});
</script>
<style lang="scss" scoped>
@import "@/scss/index.scss";

@media (min-width: $desktop_width) {
  .container {
    width: auto;
    height: 100%;
    flex: 1;
  }

  .UserHome {
    width: 100%;
    height: 100%;
    overflow: auto;
  }

  .UserHome h2 {
    padding: 8px 16px;
    border-bottom: 1px solid rgb(227, 227, 227);
  }

  .user-info {
    margin: 32px 16px;
  }

  .user-info b {
    font-size: 18px;
  }

  .card-content {
    width: 100%;
    min-height: 378px;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-around;
  }

  #pie-doughnut {
    width: 378px;
    height: 378px;
  }

  #cate-gory {
    width: 512px;
    height: 378px;
  }
}
</style>
