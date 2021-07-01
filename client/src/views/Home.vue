<template>
  <div>
    <v-parallax
      height="1000"
      dark
      src="https://cdn.vuetifyjs.com/images/backgrounds/vbanner.jpg"
    >
      <v-row align="center" justify="center">
        <v-col class="text-center" cols="12">
          <h1 class="display-4 font-weight-thin mb-4">
            Fitness Progress App
          </h1>
          <h4 class="subheading">
            Make your progress today !!
          </h4>
          <v-col class="text-center mt-2" cols="12">
            <v-btn class="mr-2" :to="{ name: 'login' }">login </v-btn>
            <v-btn :to="{ name: 'register' }">Register</v-btn>
          </v-col>
        </v-col>
      </v-row>
    </v-parallax>
  </div>
</template>

<script>
// @ is an alias to /src

export default {
  name: "Home",
  components: {},
};
</script>
<style scoped></style>
