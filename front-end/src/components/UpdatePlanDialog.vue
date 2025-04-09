<template>
  <v-dialog v-model="dialog" persistent max-width="300px">
    <v-card>
      <v-card-title>
        <span class="text-h5">Change Plan</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12">
              <v-text-field
                type="number"
                :min="1"
                hide-details
                dense
                outlined
                label="Plan"
                placeholder="Input Number GB"
                v-model="planValue"
              />
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close"> Close </v-btn>
        <v-btn color="blue darken-1" text @click="save"> Save </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "UpdatePlan",
  props: {
    value: Boolean,
    plan: Number,
  },
  data() {
    return {
      localPlan: this.plan,
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
    planValue: {
      get() {
        return this.localPlan;
      },
      set(value) {
        this.localPlan = Math.max(1, Number(value));
      },
    },
  },
  methods: {
    save() {
      this.$emit("save", this.localPlan);
      this.close();
    },
    close() {
      this.dialog = false;
    },
  },
  watch: {
    plan(newVal) {
      this.localPlan = newVal;
    },
  },
};
</script>