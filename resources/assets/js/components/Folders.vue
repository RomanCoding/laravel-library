<template>
    <div class="card card-default">
        <div class="card-header">
            Folders permissions
        </div>
        <div class="card-body">
            <li>
                <div v-for="model in filteredFolders">
                    <item :model="model" :permissions="true" v-on:permissions="updatePermissions"></item>
                </div>
            </li>
        </div>
    </div>
</template>

<script>
    import Item from './Item.vue';

    export default {
        components: {Item},
        computed: {
            filteredFolders: function () {
                return _.filter(this.folders, function (o) {
                    return (o.parent_id === null);
                });
            }
        },
        data() {
            return {
                sortKey: 'filename',
                reverse: false,
                files: [],
                folders: [],
                rootFolderId: [],
            }
        },
        created() {
            axios.get('/folders').then((response) => {
                this.folders = response.data;
                let rootFolder = _.find(this.folders, function (f) {
                    return f.parent_id === null;
                });
                this.rootFolderId = rootFolder ? rootFolder.id : null;
            });
        },
        methods: {
            getFileSize(bytes) {
                if (bytes > 1048576) {
                    return (Math.round(bytes / 1048576 * 10) / 10) + ' mb';
                }
                if (bytes > 1024) {
                    return (Math.round(bytes / 1024 * 10) / 10) + ' kb';
                }
                return (Math.round(bytes * 10) / 10) + ' b';
            },
            getFolderSize(folder, formatted = true) {
                let size = 0;
                let self = this;
                folder.files.forEach(function (file) {
                    size += file.filesize;
                });
                folder.folders.forEach(function (childrenFolder) {
                    size += self.getFolderSize(childrenFolder, false);
                });
                return formatted ? this.getFileSize(size) : size;
            },
            updatePermissions(model) {
                axios.patch('/folders/' + model.id).then(function(response) {
                    //
                });
            }
        }
    }
</script>
<style scoped>
    li {
        list-style: none;
    }
    thead tr th:not(:first-child) {
        cursor: pointer;
    }

    li a:hover {
        text-decoration: none;
        color: inherit;
    }
    .card-body {
        padding-bottom: 5px;
    }
</style>
