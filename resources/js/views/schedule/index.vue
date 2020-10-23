<template>
  <div class="tab-container">
    <el-tabs v-model="activeName" style="margin-top: 15px;" type="border-card">
      <el-tab-pane v-for="item in tabMapOptions" :key="item.key" :label="item.label" :name="item.key">
        <keep-alive>
          <marcher v-if="activeName==item.key && item.key == 'marcher'" :type="item.key" @create="showCreatedTimes" />
          <history v-if="activeName==item.key && item.key == 'history'" :type="item.key" @create="showCreatedTimes" />
          <personnel v-if="activeName==item.key && item.key == 'personnel'" :type="item.key" @create="showCreatedTimes" />
        </keep-alive>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
import marcher from './components/marcher';
import history from './components/history';
import personnel from './components/personnel';

export default {
  name: 'Tab',
  components: { marcher, history, personnel },
  data() {
    return {
      tabMapOptions: [
        { label: 'Marcher', key: 'marcher' },
        { label: 'Personnel', key: 'personnel' },
        { label: 'History', key: 'history' },
      ],
      activeName: 'marcher',
      createdTimes: 0,
    };
  },
  methods: {
    showCreatedTimes() {
      this.createdTimes = this.createdTimes + 1;
    },
  },
};
</script>
