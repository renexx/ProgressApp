<template>
  <div class="content pa-6">
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
    <div class="container">
      <div>
        <v-btn
          class="mt-10"
          fab
          light
          x-large
          color="cyan accent-2"
          @click="dialog1 = true"
        >
          <v-icon dark>
            mdi-plus
          </v-icon>
        </v-btn>
        <h3>Add activity</h3>
      </div>
      <v-dialog v-model="dialog1" max-width="600px">
        <v-card>
          <v-card-title>
            Add new activity
          </v-card-title>
          <v-card-text>
            <v-text-field
              label="Activity name"
              v-model="activity.add_activity_name"
            ></v-text-field>
            <v-select
              :items="items"
              label="Unit"
              v-model="activity.unit"
            ></v-select>
            <v-text-field
              class="user-id"
              hidden
              v-model="user.id"
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" text @click="dialog1 = false">
              Close
            </v-btn>
            <v-btn
              color="blue darken-1"
              text
              @click="dialog1 = false"
              @click.prevent="addActivity()"
            >
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialog2" max-width="600px">
        <v-card>
          <v-card-title>
            Edit activity
            {{ activity.activity_name }}
          </v-card-title>
          <v-card-text>
            <v-text-field
              label="Activity name"
              v-model="activity.activity_name"
            ></v-text-field>
            <v-select
              :items="items"
              label="Unit"
              v-model="activity.unit"
            ></v-select>
            <v-text-field
              class="user-id"
              hidden
              v-model="user.id"
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" text @click="dialog2 = false">
              Close
            </v-btn>
            <v-btn
              color="blue darken-1"
              text
              @click="dialog2 = false"
              @click.prevent="updateActivity()"
            >
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <div
        v-for="activity in sortArrays(activities)"
        :key="activity.id"
        class="box"
      >
        <v-card>
          <v-card-title>
            {{ activity.activity_name }}
          </v-card-title>
          <v-divider></v-divider>
          <v-card-actions justify="space-between">
            <v-btn
              class="pr-3 mx-2 btn-delete"
              fab
              x-small
              color="red"
              @click.prevent="onDelete(activity.activity_id)"
            >
              <v-icon dark>
                mdi-trash-can
              </v-icon></v-btn
            >

            <v-btn
              class="btn-edit"
              color="indigo"
              fab
              x-small
              @click="dialog2 = true"
              @click.prevent="editActivity(activity)"
            >
              <v-icon dark>
                mdi-pencil
              </v-icon></v-btn
            >
          </v-card-actions>
          <v-divider></v-divider>
          <v-card-actions>
            <v-btn class="btn-edit" x-small>
              <router-link
                :to="{
                  name: 'activity',
                  params: { activityId: activity.activity_id },
                }"
                class="link"
              >
                <v-icon dark>
                  mdi-chevron-down
                </v-icon>
                More
              </router-link>
            </v-btn>
          </v-card-actions>
        </v-card>
      </div>
    </div>

    <div></div>
  </div>
</template>

<script>
// @ is an alias to /src
import axios from "axios";
import { mapGetters } from "vuex";
import _ from "lodash";
export default {
  name: "allactivities",
  components: {},
  data() {
    return {
      activities: [],
      activity: {
        activity_name: "",
        add_activity_name: "",
        unit: "",
      },
      edit: false,
      activity_id: "",
      dialog1: false,
      dialog2: false,
      dialogDelete: false,
      items: ["km", "kg", "m"],
      msgErr: false,
      msgSucc: false,
      err: "",
    };
  },
  computed: {
    ...mapGetters({
      authenticated: "auth/authenticated",
      user: "auth/user",
    }),
  },
  created() {
    this.getAllActivities();
  },
  methods: {
    getAllActivities() {
      axios
        .get("api/v1/activities")
        .then((res) => {
          this.activities = res.data;
        })
        .catch((err) => console.log(err));
    },
    sortArrays(activities) {
      return _.orderBy(activities, "created_at", "desc");
    },
    addActivity() {
      axios
        .post("api/v1/activities", {
          activity_name: this.activity.add_activity_name,
          user_id: this.user.id,
          unit: this.activity.unit,
        })
        .then(() => {
          this.clear();
          this.getAllActivities();
        })
        .catch((res) => {
          (this.msgErr = true), (this.err = res.response.data.message);
        });
    },

    onDelete(id) {
      if (
        window.confirm("Are you sure? Do you want to delete this activity?")
      ) {
        axios.delete("api/v1/activities/" + id).then(() => {
          (this.msgSucc = true), this.getAllActivities();
        });
      }
    },
    editActivity(activity) {
      this.activity.activity_name = activity.activity_name;
      this.activity.user_id = this.user.id;
      this.activity_id = activity.activity_id;
      this.activity.unit = activity.unit;
    },

    updateActivity() {
      axios
        .put("api/v1/activities/" + this.activity_id, {
          activity_name: this.activity.activity_name,
          unit: this.activity.unit,
        })
        .then((res) => res.json)
        .then(() => {
          this.clear();
          this.getAllActivities();
        })
        .catch((res) => {
          (this.msgErr = true), (this.err = res.response.data.message);
        });
    },

    clear() {
      this.activity.activity_name = "";
      this.activity.add_activity_name = "";
      this.activity.unit = "";
    },
  },
};
</script>
<style scoped>
.content {
  padding-top: 50px;
  margin-top: 50px;
}
.container {
  display: grid;
  grid-gap: 20px;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}
.neviem {
  display: flex;
  flex-direction: column;
}
.btn-delete {
  box-shadow: none;
  background-color: none;
  color: white;
}
.btn-edit {
  box-shadow: none;
  background-color: none;
  color: white;
}
.link {
  text-decoration: none;
  color: black;
}
.user-id {
  opacity: 0;
}
</style>
