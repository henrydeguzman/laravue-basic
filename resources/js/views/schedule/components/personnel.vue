<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="query.keyword" :placeholder="'sample'" style="width: 200px;" class="filter-item" />
      <el-input v-model="query.role" :placeholder="'sample 2'" style="width: 90px" class="filter-item" />
      <el-button class="filter-item" type="primary" icon="el-icon-search" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
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
          <span>{{ scope.row.user.name }}</span>
        </template>
      </el-table-column>

      <!-- COLUMN ACTIONS -->
      <el-table-column align="center" label="Actions">
        <template slot-scope="scope">

          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEdit(scope.row)">
            {{ $t('table.edit') }}
          </el-button>
          <el-button type="danger" size="small" icon="el-icon-danger" @click="handleDelete(scope.row.id, scope.row.user)">
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

    <!-- DIALOG FOR ADDING PERSONNEL -->
    <el-dialog :title=" currentPersonnel > 0 ? 'Update personnel' : 'Add new personnel'" :visible.sync="dialogFormVisible">
      <div v-loading="userCreating" class="form-container">
        <el-form ref="personnelForm" :rules="rules" :model="newPersonnel" label-position="left" label-width="250px" style="max-width: 500px;">
          <el-form-item :label="$t('schedulePersonnel.select')" prop="id">
            <el-select v-model="newPersonnel.id" class="filter-item" placeholder="Please select personnel">
              <el-option v-for="item in userList" :key="item.id" :label="item.name | uppercaseFirst" :value="item.id" />
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="addPersonnel()">
            {{ currentPersonnel > 0 ? $t('table.update') : $t('table.add') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import UserResource from '@/api/user';
import PersonnelResource from '@/api/personnel';
import axios from 'axios';

const personnelResource = new PersonnelResource();
const userResource = new UserResource();

export default {
  name: 'PersonnelList',
  components: { Pagination },
  data() {
    return {
      list: null,
      userList: null,
      total: 0,
      loading: true,
      userCreating: false,
      query: {
        page: 1,
        limit: 15,
      },
      dialogFormVisible: false,
      rules: {
        id: { required: true, message: 'Please select personnel' },
      },
      newPersonnel: {},
      personnels: [],
      personnelAdding: false,
      currentPersonnel: 0,
    };
  },
  created() {
    this.getList();
    this.getUsers();
  },
  methods: {
    async getUsers() {
      const { data, meta } = await userResource.list(this.query);
      console.log(data);
      this.userList = data;
      this.total = meta.total;
    },
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await personnelResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      console.log(data);
      this.total = meta.total;
      this.loading = false;
    },
    addPersonnel() {
      this.$refs['personnelForm'].validate((valid) => {
        if (valid) {
          this.personnelAdding = true;
          if (this.currentPersonnel > 0) {
            console.log('update');
            console.log(this.newPersonnel.id, this.currentPersonnel);
            // personnelResource
            //   .update(1, 2)
            //   .then(response => {
            //     console.log(response);
            //   });
            axios.put('api/personnels/' + this.newPersonnel.id + '/' + this.currentPersonnel, { sample: 1 })
              .then(response => {
                console.log(response);
                this.resetNewPersonnel();
                this.dialogFormVisible = false;
                this.handleFilter();
              });
          } else {
            personnelResource
              .store(this.newPersonnel)
              .then(response => {
                this.$message({
                  message: 'New personnel ' + this.newPersonnel.name + ' has been successfully added.',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetNewPersonnel();
                this.dialogFormVisible = false;
                this.handleFilter();
              });
          }
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    handleEdit(row){
      console.log(row);
      const user = row.user;
      this.dialogFormVisible = true;
      this.currentPersonnel = row.id;
      this.newPersonnel.id = user.id;
      console.log(this.newPersonnel.id, this.currentPersonnel);
    },
    handleDelete(id, name){
      console.log(id);
      this.$confirm('Are you sure ?', 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning',
      }).then(() => {
        personnelResource.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: 'Delete completed',
          });
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Delete cancelled',
        });
      });
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetNewPersonnel();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['personnelForm'].clearValidate();
      });
    },
    resetNewPersonnel() {
      this.currentPersonnel = 0;
      this.newPersonnel = {
        id: null,
      };
    },
  },
};
</script>
