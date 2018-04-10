<template>
    <div class="card card-default">
        <nav aria-label="breadcrumb" v-if="history.length">
            <ol class="breadcrumb" style="background-color: black;">
                <li class="breadcrumb-item" v-for="(page, key) in history">
                    <a href="#" @click="openFolder(page, key)" style="color: white;">{{ page.name }}</a>
                </li>
            </ol>
        </nav>
        <table class="table table-striped table-dark">
            <tbody>
            <tr v-if="currentFolder && newFolder === null" style="cursor: pointer;" @click="createFolder">
                <td width="1%">
                    <i class="fa fa-fw fa-plus-square"></i>
                </td>
                <td>Create Folder</td>
            </tr>
            <tr v-if="currentFolder && newFolder !== null">
                <td width="1%">
                    <i class="fa fa-fw fa-plus-square"></i>
                </td>
                <td>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" @click="saveFolder">Save</button>
                            <button class="btn btn-outline-secondary" type="button" @click="newFolder = null">Cancel</button>
                        </div>
                        <input type="text" class="form-control" v-model="newFolder">
                    </div>
                </td>
            </tr>
            <tr v-for="folder in filteredFolders" style="cursor: pointer;" @click="openFolder(folder)">
                <td width="1%">
                    <i class="fa fa-fw fa-folder"></i>
                </td>
                <td>{{ folder.name }}</td>
            </tr>
            <template v-if="currentFolder">
                <tr v-for="file in currentFolder.files" style="cursor: pointer;" @click="openFolder(folder)">
                    <td width="1%">
                        <i :class="iconForExtension(file.extension)"></i>
                    </td>
                    <td>{{ file.filename }}.{{ file.extension }}</td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        computed: {
            filteredFolders: function () {
                let currentFolder = this.currentFolder;
                return _.filter(this.folders, function (o) {
                    return currentFolder ? (o.parent_id === currentFolder.id) : (o.parent_id === null);
                });
            }
        },
        data() {
            return {
                folders: [],
                currentFolder: null,
                history: [],
                newFolder: null,
            }
        },
        created() {
            axios.get('/folders').then((response) => {
                this.folders = response.data;
            });
        },
        methods: {
            openFolder(folder, index = null) {
                this.history.push(folder);
                this.currentFolder = folder;
                if (index !== null) {
                    this.popHistory(index);
                }
            },
            popHistory(index) {
                this.history = this.history.slice(0, index + 1);
            },
            createFolder() {
                this.newFolder = 'New Folder';
            },
            saveFolder() {
                let self = this;
                axios.post('/manage/folders', {
                    'folder_id': this.currentFolder.id,
                    'folder_name': this.newFolder,
                }).then((response) => {
                    alert(response.data.message);
                    self.newFolder = null;
                    self.folders.push(response.data.folder);
                }).catch((error) => {
                    if (error.response.status === 422) {
                        alert(error.response.data.errors[Object.keys(error.response.data.errors)[0]]);
                    } else {
                        alert(error.response.data.message);
                    }
                });
            },
            iconForExtension(extension) {
                switch (extension) {
                    case 'txt':
                        return 'fa fa-file-text';
                    case 'docx':
                    case 'doc':
                        return 'fa fa-file-word-o';
                    case 'htm':
                    case 'html':
                        return 'fa fa-html5';
                    case 'png':
                    case 'jpg':
                    case 'jpeg':
                        return 'fa fa-file-image-o';
                }
            }
        }
    }
</script>
<style scoped>
    img.glyphs {
        width: 20px;
        height: 20px;
    }
</style>
