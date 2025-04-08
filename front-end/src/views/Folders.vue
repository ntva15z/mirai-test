<template>
  <v-app>
    <v-navigation-drawer app>navbar  </v-navigation-drawer>
    <v-main> content </v-main>
  </v-app>
</template>

<script>
import mixin from "./mixin";

export default {
  name: "FoldersManagement",
  mixins: [mixin],
  data() {
    return {};
  },
};
</script>

<style>
</style>