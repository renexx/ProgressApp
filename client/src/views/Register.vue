<template>
  <v-app class="nieco">
    <div class="alert">
      <v-alert
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
    <v-container class="align-center mx-auto">
      <v-card class="mx-auto">
        <v-card-title class="pb-0">
          <h1>Register</h1>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" @submit.prevent="submit" id="check-login-form">
            <v-text-field
              label="Login"
              prepend-icon="mdi-account-circle"
              v-model="form.login"
            />
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
            >Register</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
// @ is an alias to /src

import axios from "axios";

export default {
  name: "register",
  components: {},
  data() {
    return {
      form: {
        login: "",
        email: "",
        password: "",
      },
      showPassword: false,
      msgErr: false,
      msgSucc: false,
      err: "",
    };
  },
  methods: {
    submit() {
      axios
        .post("api/v1/register", {
          login: this.form.login,
          email: this.form.email,
          password: this.form.password,
        })
        .then(() => {
          (this.msgSucc = true),
            this.$router
              .replace({
                name: "login",
              })
              .catch(() => {
                this.msgErr = true;
                this.err = "Failed route to login page";
              });
        })
        .catch((res) => {
          this.msgErr = true;
          if (res.response.data.errors.email)
            this.err = res.response.data.errors.email[0];
          else if (res.response.data.errors.password)
            this.err = res.response.data.errors.password[0];
          else this.err = res.response.data.message;
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
  margin-top: 100px;
}
.alert {
  display: flex;
  justify-content: center;
  text-align: center;
}
</style>
