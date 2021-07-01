<template>
  <div class="content mt-16">
    <v-card class="mx-5 mt-5 justify-start activity">
      <v-card-title
        >{{ bodyparam.body_parameter_name }}
        <v-btn
          class="mx-2 edit-profile"
          fab
          light
          right
          absolute
          color="cyan accent-2"
          @click="editbodyparam = true"
        >
          <v-icon dark>
            mdi-pencil
          </v-icon>
        </v-btn>
      </v-card-title>
      <v-list-item two-line>
        <v-list-item-content>
          <v-list-item-title>Body parameter name</v-list-item-title>
          <v-list-item-subtitle>{{
            bodyparam.body_parameter_name
          }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list-item v-for="item in uniq(Logs)" :key="item.id">
        <v-list-item-content>
          <v-list-item-title>{{ item.body_parameter_name }}</v-list-item-title>
          <v-list-item-subtitle v-if="item.value"
            >actual: {{ item.value }}
          </v-list-item-subtitle>
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
          <v-list-item-subtitle v-if="bodyparam.unit">{{
            bodyparam.unit
          }}</v-list-item-subtitle>
          <v-list-item-subtitle v-else></v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-list-item two-line>
        <v-list-item-content>
          <v-list-item-title>Added</v-list-item-title>
          <v-list-item-subtitle>{{
            bodyparam.created_at
          }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-card>

    <v-card class="mx-5 mt-5" id="chart">
      <!-- GRAF pravy stlpec -->
      <div>
        <h2>{{ bodyparam.body_parameter_name }}</h2>
        <line-chart v-if="loaded" :chart-data="Graf" />
      </div>
    </v-card>
    <v-card class="mx-5 mt-5 mb-5">
      <v-card-title>Log history </v-card-title>
      <v-simple-table fixed-header height="300px">
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
                Created at
              </th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in sortArrays(Logs)" :key="item.log_id">
              <td v-if="item.log_id">{{ item.body_parameter_name }}</td>
              <td v-if="item.value">{{ item.value }} {{ item.unit }}</td>
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

    <v-dialog v-model="editbodyparam" max-width="600px">
      <v-card>
        <v-card-title>
          Body parameter : {{ bodyparam.body_parameter_name }}
        </v-card-title>
        <v-card-text>
          <v-text-field
            label="Body parameter name"
            v-model="bodyparam.body_parameter_name"
          ></v-text-field>
          <v-select
            :items="items"
            label="Unit"
            v-model="bodyparam.unit"
          ></v-select>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" text @click="editbodyparam = false">
            Close
          </v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="editbodyparam = false"
            @click.prevent="updateBodyParam()"
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
          <v-text-field
            label="Enter value"
            v-model="bodyparam.value"
          ></v-text-field>
          <v-text-field
            class="user-id"
            hidden
            disabled
            v-model="$route.params.bodyparamId"
          ></v-text-field>
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
// @ is an alias to /src

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
      items: ["kg", "lb", "%", "bmi"],
      addLogDialog: false,
      editbodyparam: false,
      bodyparams: [],
      bodyparam: {
        value: "",
      },
      loaded: false,
      chartdata: [],
      label: [],
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
    this.getSingleBodyParam();
    this.makeCharts();
    this.getLogs();
  },
  mounted() {
    this.makeCharts();
  },
  methods: {
    async makeCharts() {
      const { data } = await axios.get("api/v1/bodyparamlogs");
      this.loaded = true;

      var values = [];
      data.forEach((d) => {
        if (d.body_parameter_id == this.$route.params.bodyparamId) {
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
                label: d.body_parameter_name,
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
      axios.get("api/v1/bodyparamlogs").then((res) => {
        this.Logs = _.filter(res.data, [
          "body_parameter_name",
          this.bodyparam.body_parameter_name,
        ]);
      });
    },

    getSingleBodyParam() {
      axios
        .get("api/v1/bodyparameters/" + this.$route.params.bodyparamId)
        .then((res) => {
          this.bodyparam = res.data;
        })
        .catch((err) => console.log(err));
    },
    updateBodyParam() {
      axios
        .put("api/v1/bodyparameters/" + this.$route.params.bodyparamId, {
          body_parameter_name: this.bodyparam.body_parameter_name,
          unit: this.bodyparam.unit,
        })
        .then((res) => res.json)
        .then(() => {
          this.bodyparam.body_parameter_name = "";
          this.getSingleBodyParam();
        })
        .then((err) => console.log(err));
    },
    addNewLog() {
      axios
        .post("api/v1/log", {
          value: this.bodyparam.value,
          body_parameter_id: this.$route.params.bodyparamId,
        })
        .then(() => {
          this.getLogs();
          this.makeCharts();
        });
    },
    onDelete(id) {
      if (window.confirm("Are you sure? Do you want to delete this log?")) {
        axios.delete("api/v1/log/" + id).then(() => {
          (this.msgSucc = true), this.getLogs();
          this.makeCharts();
        });
      }
    },
    sortArrays(data) {
      return _.orderBy(data, "created_at", "desc");
    },
    uniq(data) {
      return _.uniqBy(data, "body_parameter_name");
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
