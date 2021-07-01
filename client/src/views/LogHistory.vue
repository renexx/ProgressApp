<template>
  <div class="content">
    <v-tabs class="mt-16 d-flex justify-center">
      <v-tab :to="to"> Profile</v-tab>
    </v-tabs>
    <h1>All body parameter logs</h1>
    <v-simple-table fixed-header height="auto" class="mx-10">
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">
              Body parameter name
            </th>
            <th class="text-left">
              Value
            </th>
            <th class="text-left">
              Added
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in logs" :key="item.body_parameter_id">
            <td>{{ item.body_parameter_name }}</td>
            <td v-if="item.value">{{ item.value }}</td>
            <td v-else>no value has been entered yet</td>
            <td v-if="item.created_at">{{ item.created_at }}</td>
            <td v-else>no value has been entered yet</td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
    <h1>Records of all activities</h1>
    <v-simple-table fixed-header height="auto" class="mx-10">
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">
              Activity name
            </th>
            <th class="text-left">
              Value
            </th>
            <th class="text-left">
              Added
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in activity" :key="item.activity_id">
            <td>{{ item.activity_name }}</td>
            <td v-if="item.value">{{ item.value }}</td>
            <td v-else>no value has been entered yet</td>
            <td v-if="item.created_at">{{ item.created_at }}</td>
            <td v-else>no value has been entered yet</td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
  </div>
</template>

<script>
// @ is an alias to /src

import axios from "axios";

export default {
  name: "logHistory",
  components: {},
  data() {
    return {
      to: "/me",
      logs: [],
      activity: [],
      log: {
        value: "",
      },
    };
  },
  created() {
    this.getAllLogs();
    this.getAllActivityLogs();
  },
  methods: {
    getAllLogs() {
      axios
        .get("api/v1/bodyparamlogs")
        .then((res) => {
          this.logs = res.data;
        })
        .catch((err) => console.log(err));
    },
    getAllActivityLogs() {
      axios.get("api/v1/activitylogs").then((res) => {
        this.activity = res.data;
      });
    },
  },
};
</script>
<style scoped>
.content {
  height: 100%;
  width: auto;
  margin-top: 60px;
}
h1 {
  margin-left: 20px;
}
</style>
