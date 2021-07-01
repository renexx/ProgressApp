<template>
  <v-app id="inspire">
    <v-navigation-drawer v-model="drawer" app>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="title">
            Fitness progress App
          </v-list-item-title>
          <v-list-item-subtitle>
            App for fitness progress
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-sheet class="pa-4" v-if="authenticated">
        <v-avatar class="mb-4" color="indigo" size="64"
          ><span class="white--text headline">{{
            user.login.charAt(0).toUpperCase()
          }}</span></v-avatar
        >

        <div>{{ user.email }}</div>
      </v-sheet>

      <v-divider></v-divider>

      <v-list dense nav v-if="authenticated">
        <v-list-item
          v-for="item in authItems"
          :key="item.title"
          :to="item.to"
          link
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-list dense nav v-else>
        <v-list-item
          v-for="item in noAuthItems"
          :key="item.title"
          :to="item.to"
          link
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <template v-slot:append>
        <div class="pa-2">
          <v-btn @click.prevent="signOut" block dark v-if="authenticated">
            Logout

            <v-icon>mdi-logout</v-icon>
          </v-btn>

          <v-btn
            @click="dialogUnRegister = true"
            class="mt-3"
            v-if="authenticated"
            block
            dark
          >
            Delete account

            <v-icon>mdi-trash-can</v-icon>
          </v-btn>
        </div>
      </template>

      <v-dialog v-model="dialogUnRegister" max-width="500">
        <v-card>
          <v-card-title class="headline">
            Do you want to delete your account?
          </v-card-title>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn
              color="green darken-1"
              text
              @click="dialogUnRegister = false"
            >
              Disagree
            </v-btn>

            <v-btn
              color="green darken-1"
              text
              @click="dialogUnRegister = false"
              @click.prevent="unRegister()"
            >
              Agree
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-navigation-drawer>
    <v-app-bar app>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title
        >{{ $route.name.charAt(0).toUpperCase() + $route.name.slice(1) }}

        <v-switch
          v-model="$vuetify.theme.dark"
          inset
          label="Theme"
          persistent-hint
          class="float-right mx-5"
        ></v-switch>
      </v-toolbar-title>
    </v-app-bar>
    <v-app-bar
      app
      color="primary"
      dark
      inverted-scroll
      scroll-target="#scrolling-techniques-8"
    >
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title>Title</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>mdi-heart</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
    </v-app-bar>

    <v-main>
      <router-view></router-view>
    </v-main>
    <v-footer dark padless>
      <v-card class="flex" flat tile>
        <v-card-text class="py-2 white--text text-center">
          {{ new Date().getFullYear() }} — <strong>Fitness progress App</strong>
        </v-card-text>
      </v-card>
    </v-footer>
  </v-app>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data: () => ({
    dialogUnRegister: false,
    drawer: null,
    authItems: [
      { title: "All activity", icon: "mdi-view-dashboard", to: "/dashboard" },
      { title: "Profile", icon: "mdi-account", to: "/me" },
    ],
    noAuthItems: [
      { title: "Login", icon: "mdi-login", to: "/login" },
      { title: "Register", icon: "mdi-login", to: "/register" },
    ],
  }),
  computed: {
    ...mapGetters({
      authenticated: "auth/authenticated",
      user: "auth/user",
    }),
  },
  methods: {
    ...mapActions({
      signOutAction: "auth/signOut",
      unRegisterAction: "auth/unRegister",
    }),
    signOut() {
      this.signOutAction().then(() => {
        this.$router.replace({
          name: "Home",
        });
      });
    },
    unRegister() {
      this.unRegisterAction().then(() => {
        this.$router.replace({
          name: "Home",
        });
      });
    },
    getFirstLetter() {
      var str = this.user.login.charAt(0);
      console.log(str);
    },
  },
};
</script>
<style scoped>
.btn {
  width: 50px;
}
</style>
