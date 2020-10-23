<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="query.keyword" :placeholder="'sample'" style="width: 200px;" class="filter-item" />
      <el-input v-model="query.role" :placeholder="'sample 2'" style="width: 90px" class="filter-item" />
      <el-button class="filter-item" type="primary" icon="el-icon-search" @click="handleCreate"></el-button>
    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <!-- COLUMN ID -->
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.index }}</span>
        </template>
      </el-table-column>

      <!-- COLUMN NAME -->
      <el-table-column align="center" label="Name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

    <!-- DIALOG FOR ADDING PERSONNEL -->
    <el-dialog :title="'Add new personnel'" :visible.sync="dialogFormVisible">
      <div v-loading="userCreating" class="form-container">
        <el-form ref="personnelForm">

        </el-form>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/component/Pagination'; // Secondary package based on el-pagination
import ScheduleResource from '@/api/schedule';

const scheduleResource = new ScheduleResource();

export default {
  name: 'PersonnelList',
  components: { Pagination },
  data() {
    return {
      list: null,
      total: 0,
      loading: true,
      userCreating: false,
      query: {
        page: 1,
        limit: 15,
      },
      dialogFormVisible: false,
      rules: {
        user_id: { required: true, message: 'Please select personnel' },
      },
      newPersonnel: {},
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await scheduleResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    addPersonnel() {
      // this.$refs['']
    },
    handleCreate() {
      this.resetNewPersonnel();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['personnelForm'].clearValidate();
      });
    },
    resetNewPersonnel() {
      this.newPersonnel = {
        user_id: 0,
      };
    },
  },
};
</script>
