<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">File Explorer</div>

                    <div class="card-body">
                        <li>
                            <div v-for="model in filteredItems">
                                <item :model="model"></item>
                            </div>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Item from './Item.vue';
    export default {
        components: {Item},
        computed: {
            orderedFiles: function () {
                return _.orderBy(this.files, this.sortKey, this.reverse ? 'desc' : 'asc')
            },
            filteredItems: function () {
                let folders = _.filter(this.folders, function (o) {
                    return (o.parent_id === 1);
                });
                let files = _.filter(this.files, function (o) {
                    return (o.folder_id === 1);
                });

                return folders.concat(files);
            }
        },
        data() {
            return {
                sortKey: 'filename',
                reverse: false,
                files: [],
                folders: [],
            }
        },
        created() {
            axios.post('/library/folders').then((response) => {
                this.folders = response.data;
            });
            axios.post('/library').then((response) => {
                this.files = response.data;
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
            getFolderSize(folder) {
                let size = 0;
                folder.files.forEach(function (file) {
                    size += file.filesize;
                });
                return this.getFileSize(size);
            }
        }
    }
</script>
<style scoped>
    li {
        list-style: none;
    }
    img.glyphs {
        width: 20px;
        height: 20px;
    }
</style>
