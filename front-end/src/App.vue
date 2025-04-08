<template>
  <v-main>
    <router-view :key="$route.fullPath" />
  </v-main>
</template>

<script>
export default {
  name: "App",

  data: () => ({
    //
  }),
};
</script>
