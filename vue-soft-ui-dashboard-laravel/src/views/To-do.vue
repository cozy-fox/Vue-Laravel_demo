<template>
  <div class="py-4 container-fluid">
    <div class="row">
      <div :class="`col-${action_type == '' ? '12' : '6'}`">
        <div class="card h-100">
          <div class="card-header pb-0 p-3">
            <div class="row">
              <div class="col-6 d-flex align-items-center">
                <h6 class="mb-0">To-do Lists</h6>
              </div>
              <div class="col-6 text-end">
                <soft-button color="success" variant="gradient" @click="addAction">
                  <i class="fas fa-plus me-2"></i>
                  Add New To-do
                </soft-button>
              </div>
            </div>
          </div>
          <div class="p-3 card-body">
            <ul class="list-group">
              <li class="px-0 border-0 list-group-item d-flex  justify-content-between align-items-center"
                v-for="({ name, isCompleted, id }) of to_do" :key="id">
                <soft-switch class="ps-0 ms-1" label-class="mb-0 text-body text-truncate w-auto" :checked="isCompleted"
                  @click="updateAction(id, name, !isCompleted)" :id="id">{{ name }}</soft-switch>
                <div class="text-end">
                  <a class="btn btn-link text-danger text-gradient px-3 mb-0" href="javascript:;"
                    @click="deleteAction(id)">
                    <i class="far fa-trash-alt me-2" aria-hidden="true"></i>Delete
                  </a>
                  <a class="btn btn-link text-dark px-3 mb-0" href="javascript:;"
                    @click="editAction(name, isCompleted, id)">
                    <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-6" v-show="action_type !== ''">
        <div id="basic-info" class="card">
          <div class="card-header">
            <h5>Info</h5>
          </div>
          <div class="card-body pt-0">
            <div class="row mb-3">
              <label class="form-label">Name</label>
              <soft-model-input id="name" v-model="selected_to_do.name" type="text" placeholder="Type here..." />
              <validation-error :errors="apiValidationErrors.name" />
              <div class=" d-flex  justify-content-between align-items-center mt-2">
                <div>Is completed?</div>
                <soft-switch class="ps-0 ms-1" label-class="mb-0 text-body text-truncate w-auto"
                  @click="changeCompleted()" :checked="selected_to_do.isCompleted"></soft-switch>
              </div>
            </div>
            <soft-button class="float-end mt-6 mb-0" color="success" variant="gradient" size="sm"
              :is-disabled="loading2 ? true : false" @click="submitAction"><span v-if="loading2"
                class="spinner-border spinner-border-sm"></span><span v-else>{{ action_type == 'add' ? 'Add' : 'Update'
                }}</span></soft-button>
            <soft-button class="float-end mt-6 mb-0 mx-2" color="dark" variant="gradient" size="sm"
              :is-disabled="loading2 ? true : false" @click="cancelAction"><span v-if="loading2"
                class="spinner-border spinner-border-sm"></span><span v-else>Cancel</span></soft-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

import SoftSwitch from "@/components/SoftSwitch.vue";
import SoftButton from "@/components/SoftButton.vue";
import SoftModelInput from "@/components/SoftModelInput.vue";
import ValidationError from "@/components/ValidationError.vue";
import formMixin from "/src/mixins/form-mixin.js";
import axios from "axios";
import showSwal from "/src/mixins/showSwal.js";

const API_URL = process.env.VUE_APP_API_BASE_URL + '/todos';

export default {
  name: "dashboard-default",
  mixins: [formMixin],
  data() {
    return {
      to_do: [],
      selected_to_do: {
        id: '',
        name: '',
        isCompleted: false
      },
      action_type: ''
    };
  },
  methods: {
    async getAction() {
      try {
        const response = await axios.get(API_URL);
        console.log(response.data);
        if (response.data?.length > 0) this.to_do = response.data;

      } catch (error) {
        showSwal.methods.showSwal({
          type: "error",
          message: "Error occured during getting data.",
        });
      }
    },
    addAction() {
      this.action_type = 'add';
      this.selected_to_do = {
        id: '',
        name: '',
        isCompleted: false
      }
    },
    cancelAction() {
      this.action_type = '';
    },
    editAction(name, isCompleted, id) {
      this.action_type = 'update';
      this.selected_to_do = {
        id: id,
        name: name,
        isCompleted: isCompleted
      }
    },
    changeCompleted() {
      this.selected_to_do.isCompleted = !this.selected_to_do.isCompleted;
    },
    async deleteAction(id) {
      try {
        const response = await axios.delete(`${API_URL}/${id}`);
        if (response.status == 200) {
          showSwal.methods.showSwal({
            type: "success",
            message: `Successfully deleted.`,
          });
          this.getAction();
        }
      } catch (error) {
        showSwal.methods.showSwal({
          type: "error",
          message: `Error occured during deleting data.`,
        });
      }
    },
    async updateAction(id, name, isCompleted) {
      try {
        const response = await axios.put(`${API_URL}/${id}`, {
          name: name,
          isCompleted: isCompleted
        });
        if (response.status == 200) {
          showSwal.methods.showSwal({
            type: "success",
            message: `Successfully updated.`,
          });
          this.getAction();
        }
      } catch (error) {
        showSwal.methods.showSwal({
          type: "error",
          message: `Error occured during updating data.`,
        });
      }
    },
    async createAction(name, isCompleted) {
      try {
        const response = await axios.post(API_URL, {
          name: name,
          isCompleted: isCompleted
        });
        if (response.status == 201) {
          showSwal.methods.showSwal({
            type: "success",
            message: `Successfully created.`,
          });
          this.getAction();
          this.action_type = '';
        }
      } catch (error) {
        showSwal.methods.showSwal({
          type: "error",
          message: `Error occured during creating data.`,
        });
      }
    },
    async submitAction() {
      if (this.action_type == 'add')
        this.createAction(this.selected_to_do.name, this.selected_to_do.isCompleted);
      else if (this.action_type == 'update')
        this.updateAction(this.selected_to_do.id, this.selected_to_do.name, this.selected_to_do.isCompleted);
    },
  },
  async created() {
    this.getAction();
  },
  components: {
    SoftSwitch,
    ValidationError,
    SoftButton,
    SoftModelInput
  },
};
</script>
