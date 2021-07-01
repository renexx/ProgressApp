<template>
  <!-- <div class="content"> {{$route.params.activityId}}</div> -->
  <div class="content mt-16">
    <v-card class="mx-5 mt-5 justify-start activity">
      <div class="alert">
        <v-alert
          dismissible
          type="success"
          max-width="400"
          height="50"
          class=" mx-auto"
          v-if="msgSucc"
        >
          success
        </v-alert>
        <v-alert
          v-model="msgErr"
          dismissible
          type="error"
          max-width="800"
          height="50"
          class="mx-5"
          elevation="2"
          v-if="msgErr"
        >
          {{ err }}
        </v-alert>
      </div>
      <v-card-title
        >{{ activity.activity_name }}
        <v-btn
          class="mx-2 edit-profile"
          fab
          light
          right
          absolute
          color="cyan accent-2"
          @click="dialog1 = true"
        >
          <v-icon dark>
            mdi-pencil
          </v-icon>
        </v-btn>
      </v-card-title>
      <v-list-item two-line>
        <v-list-item-content>
          <v-list-item-title>Activity name</v-list-item-title>
          <v-list-item-subtitle>{{
            activity.activity_name
          }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list-item two-line>
        <v-list-item-content>
          <v-list-item-title>Added</v-list-item-title>
          <v-list-item-subtitle>{{ activity.created_at }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list-item two-line>
        <v-list-item-content>
          <v-list-item-title>Last updated</v-list-item-title>
          <v-list-item-subtitle>{{ activity.updated_at }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list-item v-for="item in uniq(Logs)" :key="item.log_id">
        <v-list-item-content>
          <v-list-item-title>{{ item.activity_name }}</v-list-item-title>
          <v-list-item-subtitle v-if="item.value"
            >actual: {{ item.value }} {{ item.unit }}</v-list-item-subtitle
          >
          <v-list-item-subtitle v-else>Pls enter value</v-list-item-subtitle>
        </v-list-item-content>

        <v-list-item-action>
          <v-btn
            class="mx-2"
            fab
            x-small
            dark
            color="indigo"
            @click="addLogDialog = true"
          >
            <v-icon dark>
              mdi-plus
            </v-icon>
          </v-btn>
        </v-list-item-action>
      </v-list-item>

      <v-list-item two-line>
        <v-list-item-content>
          <v-list-item-title>Unit</v-list-item-title>
          <v-list-item-subtitle v-if="activity.unit">{{
            activity.unit
          }}</v-list-item-subtitle>
          <v-list-item-subtitle v-else>Unit not entered </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-card>

    <!-- GRAF pravy stlpec -->
    <v-card class="mx-5 mt-5" id="chart">
      <div>
        <h2>{{ activity.activity_name }}</h2>
        <line-chart v-if="loaded" :chart-data="Graf" />
        <h3 v-else>Loading ...</h3>
      </div>
    </v-card>

    <v-card class="mx-5 mt-5 mb-5 log-history">
      <v-card-title>Log history </v-card-title>
      <v-simple-table fixed-header height="300px">
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
                Created at
              </th>

              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in sortArrays(Logs)" :key="item.log_id">
              <td v-if="item.log_id">{{ item.activity_name }}</td>
              <td v-if="item.value">{{ item.value }}</td>
              <td v-else>no value has been entered yet</td>
              <td>{{ item.created_at }}</td>

              <td v-if="item.log_id">
                <v-btn
                  class="mx-2"
                  fab
                  x-small
                  dark
                  color="red"
                  @click.prevent="onDelete(item.log_id)"
                >
                  <v-icon dark>
                    mdi-trash-can
                  </v-icon>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-card>

    <v-dialog v-model="dialog1" max-width="600px">
      <v-card>
        <v-card-title> Activity : {{ activity.activity_name }} </v-card-title>
        <v-card-text>
          <v-text-field label="Activity name" v-model="activity.activity_name">
          </v-text-field>
          <v-select :items="items" label="Unit" v-model="activity.unit">
          </v-select>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" text @click="dialog1 = false">
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="dialog1 = false"
            @click.prevent="updateActivity()"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="addLogDialog" max-width="600px">
      <v-card>
        <v-card-title>
          Add new log
        </v-card-title>
        <v-card-text>
          <v-text-field label="Enter value" v-model="activity.value">
          </v-text-field>
          <v-text-field
            class="user-id"
            hidden
            disabled
            v-model="$route.params.activityId"
          >
          </v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" text @click="addLogDialog = false">
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="addLogDialog = false"
            @click.prevent="addNewLog()"
          >
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from "axios";
import LineChart from "../components/Chart.vue";
import _ from "lodash";
import moment from "moment";

export default {
  name: "activity",
  components: {
    LineChart,
  },
  data() {
    return {
      activity: [],
      msgErr: false,
      msgSucc: false,
      err: "",
      items: ["km", "kg", "m"],
      dialog1: false,
      addLogDialog: false,
      label: [],
      loaded: false,
      chartdata: [],
      chartOptions: {
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
              },
              gridLines: {
                display: true,
              },
            },
          ],
          xAxes: [
            {
              gridLines: {
                display: false,
              },
            },
          ],
        },
        legend: {
          display: false,
        },
        responsive: true,
        maintainAspectRatio: false,
      },
      Graf: null,
      Logs: [],
    };
  },

  created() {
    this.getSingleActivity();
    this.makeCharts();
    this.getLogs();
  },
  mounted() {
    this.makeCharts();
  },
  methods: {
    async makeCharts() {
      const { data } = await axios.get("api/v1/activitylogs");
      this.loaded = true;

      var values = [];
      data.forEach((d) => {
        if (d.activity_id == this.$route.params.activityId) {
          values.push({ value: d.value, created_at: d.created_at });
          const param = values.map((d) => d.value).reverse();
          const dates = values
            .map((e) =>
              moment(e.created_at, "YYYY-MM-DD HH:mm:ss").format("DD.MM.YYYY")
            )
            .reverse();
          this.Graf = {
            labels: dates,
            datasets: [
              {
                label: d.activity_name,
                borderColor: "#077187",
                pointBackgroundColor: "#3CDBD3",
                borderWidth: 2,
                pointBorderColor: "#00FFF5",
                backgroundColor: "#FF8360",
                data: param,
              },
            ],
          };
        }
      });
    },
    getLogs() {
      axios.get("api/v1/activitylogs").then((res) => {
        this.Logs = _.filter(res.data, [
          "activity_name",
          this.activity.activity_name,
        ]);
      });
    },
    getSingleActivity() {
      axios
        .get("api/v1/activities/" + this.$route.params.activityId)
        .then((res) => {
          this.activity = res.data;
        })
        .catch((err) => console.log(err));
    },
    updateActivity() {
      axios
        .put("api/v1/activities/" + this.$route.params.activityId, {
          activity_name: this.activity.activity_name,
          unit: this.activity.unit,
        })
        .then((res) => res.json)
        .then(() => {
          this.activity.activity_name = "";
          this.getSingleActivity();
        });
    },
    uniq(data) {
      return _.uniqBy(data, "activity_name");
    },
    addNewLog() {
      axios
        .post("api/v1/log", {
          value: this.activity.value,
          activity_id: this.$route.params.activityId,
        })
        .then(() => {
          this.getSingleActivity();
          this.getLogs();
          this.makeCharts();
        });
    },
    onDelete(id) {
      if (window.confirm("Are you sure? Do you want to delete this log?")) {
        axios.delete("api/v1/log/" + id).then(() => {
          (this.msgSucc = true), this.getSingleActivity();
          this.getLogs();
          this.makeCharts();
        });
      }
    },
    sortArrays(data) {
      return _.orderBy(data, "created_at", "desc");
    },
  },
};
</script>
<style scoped>
.content {
  display: grid;
  grid-gap: 40px;
  grid-template-columns: 1fr;
  grid-template-areas: "activity chart chart";
}
#chart {
  margin: 20px 20px 20px 0;
  width: 100%;
  max-width: 800px;
  grid-area: chart;
}
.activity {
  grid-area: activity;
  width: 100%;
  max-height: 435px;
}

@media (max-width: 800px) {
  .content {
    grid-template-columns: 1fr;
    grid-template-areas:
      "activity"
      "chart";
  }
  #chart {
    margin: 20px 20px 20px 0;
    width: auto;
    max-width: 800px;
    grid-area: chart;
  }
  .activity {
    grid-area: activity;
    width: auto;
    max-width: 800px;
  }
}
@media (max-width: 500px) {
  .content {
    grid-template-columns: 1fr;
    grid-template-areas:
      "activity"
      "chart";
  }
  #chart {
    margin: 20px 20px 20px 0;
    width: auto;
    max-width: 350px;
    grid-area: chart;
  }
  .activity {
    grid-area: activity;
    width: auto;
    max-width: 800px;
  }
}
</style>
