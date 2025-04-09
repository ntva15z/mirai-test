<template>
  <v-dialog v-model="dialog" persistent max-width="300px">
    <v-card>
      <v-card-title>New Folder</v-card-title>
      <v-card-text>
        <v-container>
          <v-form ref="refNewFolder">
            <v-row>
              <v-col cols="12">
                <v-autocomplete
                  hide-details
                  dense
                  outlined
                  label="Parent"
                  :items="folderOptions"
                  v-model="parent"
                />
              </v-col>
              <v-col cols="12">
                <v-text-field
                  hide-details
                  dense
                  outlined
                  label="Name"
                  :rules="[(v) => !!v || 'Name is required!']"
                  v-model="name"
                />
              </v-col>
            </v-row>
          </v-form>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close()"> Close </v-btn>
        <v-btn color="blue darken-1" text @click="save()"> Save </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "NewFolder",
  props: {
    value: Boolean,
    folders: Array,
  },
  data() {
    return {
      parent: null,
      name: null,
    };
  },
  computed: {
    dialog: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit("input", val);
      },
    },
    folderOptions() {
      return [
        { text: "--No Selected--", value: null },
        ...this.folders.map((f) => ({ text: f.name, value: f.id })),
      ];
    },
  },
  methods: {
    save() {
      if (this.$refs.refNewFolder.validate()) {
        this.$emit("save", {
          parent: this.parent,
          name: this.name,
        });
        this.close();
      }
    },
    close() {
      this.dialog = false;
      this.$refs.refNewFolder.reset();
    },
  },
};
</script>