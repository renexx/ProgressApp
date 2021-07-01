<template>
  <div class="main">
    <v-tabs class="mt-16 d-flex justify-center">
      <v-tab :to="to"> History of logs</v-tab>
    </v-tabs>
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

    <div class="content">
      <v-card class="mx-5 mt-5 justify-start user-profile" max-width="400">
        <v-card-title
          >Personal info
          <v-btn
            class="mx-2 edit-profile"
            fab
            light
            right
            absolute
            color="cyan accent-2"
            @click="editPersonalInfo = true"
          >
            <v-icon dark>
              mdi-pencil
            </v-icon>
          </v-btn>
        </v-card-title>
        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Login</v-list-item-title>
            <v-list-item-subtitle>{{
              user.login.charAt(0).toUpperCase() + user.login.slice(1)
            }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Registered</v-list-item-title>
            <v-list-item-subtitle>{{ user.created_at }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Last updated</v-list-item-title>
            <v-list-item-subtitle>{{ user.updated_at }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Email</v-list-item-title>
            <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line v-if="user.height == null">
          <v-list-item-content>
            <v-list-item-title>Height</v-list-item-title>
            <v-list-item-subtitle>
              Height not entered
            </v-list-item-subtitle>
          </v-list-item-content>
          <v-list-item-action>
            <v-btn
              class="mx-2"
              fab
              small
              dark
              color="indigo"
              @click="dialogHeight = true"
            >
              <v-icon dark>
                mdi-plus
              </v-icon>
            </v-btn>
          </v-list-item-action>
        </v-list-item>
        <v-list-item two-line v-else>
          <v-list-item-content>
            <v-list-item-title>Height</v-list-item-title>
            <v-list-item-subtitle>
              {{ user.height }}
            </v-list-item-subtitle>
          </v-list-item-content>
          <v-list-item-action>
            <v-btn
              class="mx-2"
              fab
              small
              dark
              color="indigo"
              @click="dialogHeight = true"
            >
              <v-icon dark>
                mdi-pencil
              </v-icon>
            </v-btn>
          </v-list-item-action>
        </v-list-item>
      </v-card>

      <!-- BODY PARAMETERS  -->
      <v-card class="mx-5 mt-5 justify-start body-param" max-width="400">
        <v-card-title
          >Body parameters
          <v-btn
            class="mx-2 edit-profile"
            fab
            light
            right
            absolute
            color="cyan accent-2"
            @click="addBodyParamDialog = true"
          >
            <v-icon dark>
              mdi-plus
            </v-icon>
          </v-btn>
        </v-card-title>
        <v-list v-if="bodyParameters">
          <v-list-item-group color="primary">
            <v-list-item
              v-for="(value, name, i) in uniq(sortArrays(Test))"
              :key="i"
            >
              <v-list-item-content>
                <v-list-item-title>{{
                  value.body_parameter_name
                }}</v-list-item-title>
                <v-list-item-subtitle v-if="value.value"
                  >actual: {{ value.value }}
                  {{ value.unit }}</v-list-item-subtitle
                >
                <v-list-item-subtitle v-else
                  >Pls enter value</v-list-item-subtitle
                >
              </v-list-item-content>
              <v-list-item-action>
                <v-btn class="mx-2" x-small dark color="white">
                  <router-link
                    class="link"
                    :to="{
                      name: 'bodyparam',
                      params: { bodyparamId: value.body_parameter_id },
                    }"
                  >
                    <v-icon dark>mdi-chart-line</v-icon>
                  </router-link>
                </v-btn>
              </v-list-item-action>
              <v-list-item-action>
                <v-btn
                  class="mx-2"
                  fab
                  x-small
                  dark
                  color="indigo"
                  @click="addLogDialog = true"
                  @click.prevent="nahod(value.body_parameter_id)"
                >
                  <v-icon dark v-if="value.log_id">
                    mdi-pencil
                  </v-icon>
                  <v-icon dark v-else>
                    mdi-plus
                  </v-icon>
                </v-btn>
              </v-list-item-action>
              <v-list-item-action>
                <v-btn
                  class="mx-2"
                  fab
                  x-small
                  dark
                  color="red"
                  @click.prevent="onDelete(value.body_parameter_id)"
                >
                  <v-icon dark>
                    mdi-trash-can
                  </v-icon>
                </v-btn>
              </v-list-item-action>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card>

      <v-dialog v-model="addBodyParamDialog" max-width="500px">
        <v-card>
          <v-card-title>
            Create body parameter
          </v-card-title>
          <v-card-text>
            <v-text-field
              label="Body parameter name"
              v-model="bodyParameters.body_parameter_name"
            ></v-text-field>
            <v-select
              :items="items"
              label="Unit"
              v-model="bodyParameters.unit"
            ></v-select>
            <v-text-field
              class="user-id"
              hidden
              v-model="user.id"
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" text @click="addBodyParamDialog = false">
              Close
            </v-btn>
            <v-btn
              color="blue darken-1"
              text
              @click="addBodyParamDialog = false"
              @click.prevent="addBodyParam()"
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
              v-model="bodyParameters.value"
            ></v-text-field>
            <v-text-field
              class="user-id"
              hidden
              disabled
              v-model="body_parameter_id"
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

      <v-dialog v-model="dialogHeight" max-width="500px">
        <v-card>
          <v-card-title>
            Height
          </v-card-title>
          <v-card-text>
            <v-text-field
              label="Your height (cm)"
              v-model="user.height"
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" text @click="dialogHeight = false">
              Close
            </v-btn>
            <v-btn
              color="blue darken-1"
              text
              @click="dialogHeight = false"
              @click.prevent="updateHeight()"
            >
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="editPersonalInfo" max-width="600px">
        <v-card>
          <v-card-title>
            Personal info
          </v-card-title>
          <v-card-text>
            <v-text-field
              label="Your Login"
              v-model="user.login"
            ></v-text-field>
            <v-text-field
              label="Your email"
              v-model="user.email"
            ></v-text-field>
            <v-text-field
              label="Your height (cm)"
              v-model="user.height"
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" text @click="editPersonalInfo = false">
              Close
            </v-btn>
            <v-btn
              color="blue darken-1"
              text
              @click="editPersonalInfo = false"
              @click.prevent="updateProfile()"
            >
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import axios from "axios";
import { mapGetters } from "vuex";
import _ from "lodash";

export default {
  name: "profile",
  components: {},
  data() {
    return {
      to: "/log-history",
      personalValue: [
        {
          login: "",
          height: "",
          email: "",
        },
      ],

      dialogHeight: false,
      editPersonalInfo: false,
      dialog3: false,
      addLogDialog: false,
      dialog: false,
      addBodyParamDialog: false,
      msgErr: false,
      msgSucc: false,
      err: "",
      selectedItem: 1,
      bodyParameters: [],
      bodyParameter: {
        body_parameter_name: "",
        value: "",
      },
      Logs: {},
      Test: [],
      items: ["kg", "lb", "%", "bmi"],

      body_parameter_id: "",
    };
  },

  computed: {
    ...mapGetters({
      authenticated: "auth/authenticated",
      user: "auth/user",
    }),
  },

  created() {
    this.getAllBodyParameters();
    this.getAllLogs();
    this.getAllTest();
  },
  methods: {
    updateHeight() {
      axios
        .put("api/v1/me/update", { height: this.user.height })
        .then((res) => res.json)
        .catch(() => {
          this.msgErr = true;
          this.err = "bad height, please enter max 3 number";
        });
    },
    updateProfile() {
      axios
        .put("api/v1/me/update", {
          login: this.user.login,
          email: this.user.email,
          height: this.user.height,
        })
        .then((res) => res.json)
        .catch(() => {
          this.msgErr = true;
          this.err = "bad value";
        });
    },
    getAllBodyParameters() {
      axios
        .get("api/v1/bodyparameters")
        .then((res) => {
          this.bodyParameters = res.data;
        })
        .catch((err) => console.log(err));
    },
    addBodyParam() {
      axios
        .post("api/v1/bodyparameters", {
          body_parameter_name: this.bodyParameters.body_parameter_name,
          user_id: this.user.id,
          unit: this.bodyParameters.unit,
        })
        .then(() => {
          (this.msgSucc = true), this.clear();
          this.getAllTest();
          this.getAllBodyParameters();
        });
    },
    onDelete(id) {
      if (
        window.confirm(
          "Are you sure? Do you want to delete this body parameter?"
        )
      ) {
        axios.delete("api/v1/bodyparameters/" + id).then(() => {
          (this.msgSucc = true), this.getAllBodyParameters();
          this.getAllTest();
        });
      }
    },
    getAllLogs() {
      axios.get("api/v1/log").then((res) => {
        this.Logs = res.data;
      });
    },
    getAllTest() {
      axios.get("api/v1/bodyparamlogs").then((res) => {
        this.Test = res.data;
      });
    },
    nahod(id) {
      this.body_parameter_id = id;
    },
    addNewLog() {
      axios
        .post("api/v1/log", {
          value: this.bodyParameters.value,
          body_parameter_id: this.body_parameter_id,
        })
        .then(() => {
          (this.msgSucc = true), this.getAllBodyParameters();
          this.getAllTest();
          this.getAllLogs();
        });
    },
    uniq(data) {
      return _.uniqBy(data, "body_parameter_name");
    },
    sortArrays(activities) {
      return _.orderBy(activities, "created_at", "desc");
    },
    clear() {
      this.bodyParameters.body_parameter_name = "";
    },
  },
};
</script>
<style scoped>
.content {
  display: grid;
  grid-gap: 20px;
  grid-template-areas:
    "user-profile body-param"
    "user-profile body-param"
    "chart chart ";
}
.chart {
  margin: 20px 20px 20px 0;

  max-width: 600px;
  margin: 150px auto;
  grid-area: chart;
}
.user-profile {
  grid-area: user-profile;
  width: 400px;
}
.body-param {
  grid-area: body-param;
}
.link {
  text-decoration: none;
  color: rgb(9, 52, 168);
}
</style>
