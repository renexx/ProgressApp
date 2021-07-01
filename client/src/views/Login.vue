<template>
  <v-app class="nieco">
    <v-alert
      v-model="msgErr"
      dismissible
      type="error"
      max-width="400"
      height="50"
      class="mx-5"
      elevation="2"
      v-if="msgErr"
    >
      {{ err }}
    </v-alert>
    <v-container class="row wrap align-center mx-auto">
      <v-card class="mx-auto">
        <v-card-title class="pb-0">
          <h1>Login</h1>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" @submit.prevent="submit" id="check-login-form">
            <v-text-field
              label="Email"
              prepend-icon="mdi-account-circle"
              v-model="form.email"
            />
            <v-text-field
              :type="showPassword ? 'text' : 'password'"
              label="Password"
              prepend-icon="mdi-lock"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
              v-model="form.password"
            />
          </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn type="submit" form="check-login-form" color="info"
            >Login</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
// @ is an alias to /src

import { mapActions } from "vuex";
export default {
  name: "login",
  components: {},
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
      showPassword: false,
      msgErr: false,
      err: "",
    };
  },
  methods: {
    ...mapActions({
      logIn: "auth/logIn",
    }),
    submit() {
      this.logIn(this.form).then(() => {
        this.$router
          .replace({
            name: "allactivities",
          })
          .catch(() => {
            this.msgErr = true;
            this.err = "Failed route to dashboard";
          });
      });
    },
  },
};
</script>
<style scoped>
.v-card {
  width: 600px;
}
.nieco {
  display: flex;
  justify-content: center;
  text-align: center;
}
</style>
