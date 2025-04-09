import { watch } from "vue";
import mockData from "../../mockData.json";

export default {
  data() {
    return {
      plan: 2, // GB. 1GB = 1024 MB
      tempPlan: 0,
      folders: [...mockData],
      selectedMember: [],
      dialogChangePlan: false,
      dialogNewFolder: false,
      headers: [
        { text: 'Select all', value: 'file', sortable: false },
        { text: 'Name', value: 'name' },
        { text: 'Dimmension', value: 'dimmension' },
        { text: 'Size', value: 'size' },
      ],
      tblItems: [],
      tblItemFilters: [],
      selectedFolderLabel: 'Images',
      searchText: ''
    };
  },
  created() {
    if (this.folders.length) {
      this.folders.forEach(folder => {
        if (folder.children) {
          folder.children.forEach(item => {
            if (item.name == this.selectedFolderLabel) {
              this.tblItems = item.children;
              this.tblItemFilters = JSON.parse(JSON.stringify(this.tblItems));
            }
          })
        }
      })
    }
  },
  computed: {
    getProgressLabel() {
      const BYTES_IN_GB = 1024 * 1024 * 1024;
      if (!this.folders || !this.folders.length) return 0;

      let totalSize = this.calculateSize(this.folders);
      let totalGB = totalSize / BYTES_IN_GB;
      return (totalGB / this.plan) * 100;
    },
    isDefaultSelectedFolder() {
      return (folder) => {
        return folder.name == "Uploads";
      };
    },
    getMembers() {
      let members = [];
      const addMember = (items) => {
        items.forEach((item) => {
          if (item?.photo_by && !members.includes(item.photo_by)) {
            members.push(item.photo_by);
          }
          if (item.children?.length) {
            addMember(item.children);
          }
        });
      };

      addMember(this.folders);

      return members;
    },
    getListParentFolder() {
      let result = [];
      result = this.folders.map(folder => {
        return {
          text: folder.name,
          value: folder.id
        }
      });

      return [
        {
          text: '--No Selected--',
          value: null
        },
        ...result
      ];
    },
  },
  methods: {
    onClickFolder(data) {
      this.tblItems = [];
      this.selectedFolderLabel = data.name;
      if (data.children.length) {
        this.tblItems = JSON.parse(JSON.stringify(data.children));
        this.filterFolderItem();
      }
    },
    filterFolderItem() {
      if (this.selectedMember && this.selectedMember.length) {
        this.tblItemFilters = this.tblItems.filter(item => this.selectedMember.includes(item.photo_by));
      } else {
        this.tblItemFilters = JSON.parse(JSON.stringify(this.tblItems))
      }
      if (this.searchText) {
        let lowerKeyword = this.searchText.toLowerCase();
        this.tblItemFilters = this.tblItemFilters.filter(item =>
          item.name.toLowerCase().includes(lowerKeyword)
        );
      }
    },
    calculateSize(items) {
      let total = 0;
      const sumSize = (items) => {
        items.forEach((item) => {
          if (item?.size) {
            total += Number(item.size);
          }
          if (item.children?.length) {
            sumSize(item.children);
          }
        });
      };

      sumSize(items);

      return total;
    },
    toggleSelectionMember(key) {
      const index = this.selectedMember.indexOf(key);
      if (index === -1) {
        this.selectedMember.push(key);
      } else {
        this.selectedMember.splice(index, 1);
      }
    },
    onClickSelectAll() {
      let members = this.getMembers;
      if (members.length == this.selectedMember.length) {
        this.selectedMember = [];
      } else {
        this.selectedMember = this.selectedMember.concat(members);
      }
    },
    openDialogChangePlan() {
      this.tempPlan = this.plan;
      this.dialogChangePlan = true;
    },
    toggleUpdatePlan(newVal) {
      this.plan = newVal;
    },
    openDialogNewFolder() {
      this.$nextTick(() => {
        this.$refs?.refNewFolder?.resetValidation();
      });
      this.dialogNewFolder = true;
    },
    toggleNewFolder(tempFolder) {
      if (tempFolder.parent) {
        this.folders.forEach(folder => {
          if (folder.id == tempFolder.parent) {
            let arr = [...folder.children].sort((a, b) => b.id - a.id);
            let newId = arr[0].id + 1;
            folder.children.push({
              id: newId,
              name: tempFolder.name,
              children: []
            })
          }
        })
      } else {
        let arr = [...this.folders].sort((a, b) => b.id - a.id);
        let newId = arr[0].id + 1;
        this.folders.push({
          id: newId,
          name: tempFolder.name,
          children: []
        })
      }
      this.$forceUpdate();
    }
  },
  watch: {
    selectedMember: {
      handler() {
        this.filterFolderItem();
      },
      deep: true
    }
  }
};
