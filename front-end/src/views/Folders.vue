<template>
  <v-app>
    <v-navigation-drawer app permanent>
      <div class="mx-4 my-4">
        <!-- Plan -->
        <v-row>
          <v-col cols="12">
            <div class="d-flex align-center justify-space-between">
              <div>Storage</div>
              <div>
                <a class="link-btn" @click="openDialogChangePlan()"
                  >Change plan</a
                >
              </div>
            </div>
          </v-col>
          <v-col cols="12">
            <v-progress-linear :value="getProgressLabel" height="15" rounded />
            <div class="d-flex align-center">
              <span style="color: blue"
                >{{ Math.ceil(getProgressLabel) }}%</span
              >
              <span>&nbsp;used of {{ plan }}GB</span>
            </div>
          </v-col>
        </v-row>
        <v-divider class="my-6"></v-divider>

        <!-- Search -->
        <v-row no-gutters>
          <v-col cols="12">
            <div>Search</div>
            <div class="mt-3">
              <v-text-field
                outlined
                placeholder="e.g. image.png"
                append-icon="mdi-magnify"
                hide-details
                v-model="searchText"
                @keypress.enter="filterFolderItem()"
              />
            </div>
          </v-col>
        </v-row>
        <v-divider class="my-6"></v-divider>

        <!-- Folders -->
        <v-row no-gutters>
          <v-col cols="12">
            <div class="d-flex align-center justify-space-between">
              <div>Folders</div>
              <div><a class="link-btn" @click="openDialogNewFolder()">New Folder</a></div>
            </div>
          </v-col>
          <v-col cols="12">
            <v-list>
              <v-list-group
                no-action
                :sub-group="false"
                append-icon="mdi-menu-down"
                class="list-parent"
                v-for="(folder, i) in folders"
                :key="i"
                :value="isDefaultSelectedFolder(folder)"
              >
                <template v-slot:activator>
                  <v-list-item-content>
                    <div class="d-flex align-center">
                      <v-icon medium class="mr-2">mdi-folder-open</v-icon>
                      <v-list-item-title>{{ folder.name }}</v-list-item-title>
                    </div>
                  </v-list-item-content>
                </template>
                <template v-if="folder.children">
                  <v-list-item-group :value="0" color="primary">
                    <v-list-item
                      v-for="(children, j) in folder.children"
                      :key="j"
                      link
                      @click="onClickFolder(children)"
                    >
                      <v-list-item-icon>
                        <v-icon medium class="mr-2">mdi-menu-right</v-icon>
                        <v-icon medium class="mr-2">mdi-folder-open</v-icon>
                      </v-list-item-icon>
                      <v-list-item-title>{{ children.name }}</v-list-item-title>
                    </v-list-item>
                  </v-list-item-group>
                </template>
              </v-list-group>
            </v-list>
          </v-col>
        </v-row>

        <!-- member -->
        <v-divider class="mt-2 mb-6"></v-divider>
        <v-row no-gutters>
          <v-col cols="12">
            <div class="d-flex align-center justify-space-between">
              <div>Member</div>
              <div>
                <a class="link-btn" @click="onClickSelectAll()">Select all</a>
              </div>
            </div>
          </v-col>
          <v-col cols="12">
            <v-list>
              <v-list-item
                v-for="(member, index) in getMembers"
                :key="index"
                @click="toggleSelectionMember(member)"
                :class="{ 'selected-item': selectedMember.includes(member) }"
                class="list-item"
                dense
              >
                <v-list-item-action>
                  <v-checkbox
                    v-model="selectedMember"
                    :value="member"
                    @click="toggleSelectionMember(member)"
                    hide-details
                    dense
                    color="#E91E63"
                  ></v-checkbox>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title
                    :class="{
                      'selected-text': selectedMember.includes(member),
                    }"
                  >
                    {{ member }}
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-col>
        </v-row>
      </div>
    </v-navigation-drawer>
    <v-main style="padding: 0"> 
      <v-card>
        <v-card-title>{{selectedFolderLabel}}</v-card-title>
        <v-data-table
          :headers="headers"
          :items="tblItemFilters"
          :items-per-page="-1"
          :show-select="true"
          hide-default-footer
          class="elevation-1"
          :mobile-breakpoint="0"
        >
          <template v-slot:item.file="{item}">
            <img :src="item.url" width="200" height="100" />
          </template>
        </v-data-table>  
      </v-card>
    </v-main>

    <!-- dialog change plan -->
    <UpdatePlanDialog
      v-model="dialogChangePlan"
      :plan="plan"
      @save="toggleUpdatePlan"
    />

    <!-- dialog new folder -->
    <NewFolderDialog
      v-model="dialogNewFolder"
      :folders="folders"
      @save="toggleNewFolder"
    />
  </v-app>
</template>

<script>
import mixin from "./mixin";
import NewFolderDialog from "../components/NewFolderDialog";
import UpdatePlanDialog from "../components/UpdatePlanDialog";

export default {
  name: "FoldersManagement",
  components: {NewFolderDialog, UpdatePlanDialog},
  mixins: [mixin],
  methods: {
    
  },
};
</script>

<style lang="scss">
.v-list-group__header__append-icon {
  order: -1;
  margin-right: 16px;
  margin-left: 0 !important;
  justify-content: flex-start !important;
  min-width: 32px !important;
}
.list-parent {
  .v-list-group__header {
    padding-left: 0;
  }
  .v-list-group__header__prepend-icon {
    color: black !important;
  }
}
.list-parent {
  &:has(.v-list-group--active) {
    .v-list-group__header__prepend-icon {
      color: blue !important;
    }
  }
}
.list-children {
  .v-list-group__header {
    padding-left: 16px;
  }
}
.v-list-group__items {
  .v-list-item {
    padding-left: 32px;
  }
}
.v-application--is-ltr
  .v-list-group--no-action.v-list-group--sub-group
  > .v-list-group__items
  > .v-list-item {
  padding-left: 56px;
}
.v-list-item__action {
  margin: 6px 0;
}
.v-list-item {
  min-height: 24px;
}
.v-list-item__icon {
  margin: 6px 0;
}

.link-btn {
  &:hover {
    cursor: pointer;
  }
  text-decoration: underline;
  color: #000000de !important;
}
</style>