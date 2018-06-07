<template>
    <div class="card card-default">
        <div class="modal fade show" tabindex="-1" role="dialog" v-if="folderToDelete">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Folder deleting</h5>
                        <button type="button" class="close" aria-label="Close" @click="folderToDelete=null;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        You are about to delete folder.
                        <br>
                        This will also delete all folders and files in it.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="folderToDelete=null;">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="deleteFolder">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" tabindex="-1" role="dialog" v-if="fileToDelete">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">File deleting</h5>
                        <button type="button" class="close" aria-label="Close" @click="fileToDelete=null;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        You are about to delete file {{ fileToDelete.filename }}.{{ fileToDelete.extension }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="fileToDelete=null;">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="deleteFile">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" tabindex="-1" role="dialog" v-if="uploadsModal">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <file-uploader :folder="currentFolder"
                                   :extensions="extensions"
                                   @uploaded="addUploadedFiles"
                                   @closed="toggleUploadModal">
                    </file-uploader>
                </div>
            </div>
        </div>

        <nav aria-label="breadcrumb" v-if="history.length">
            <ol class="breadcrumb" style="background-color: black;">
                <li class="breadcrumb-item" v-for="(page, key) in history">
                    <a href="#" @click="openFolder(page, key)" style="color: white;">{{ page.name }}</a>
                </li>
            </ol>
        </nav>
        <table class="table table-striped table-dark">
            <tbody>
            <tr v-if="currentFolder && newFolder === null" class="pointer">
                <td colspan="2" width="50%" style="text-align: center; border: 1px solid white;" @click="createFolder">Create Folder</td>
                <td colspan="2" width="50%" style="text-align: center; border: 1px solid white;" @click="toggleUploadModal">Upload Files</td>
            </tr>
            <tr v-if="currentFolder && newFolder !== null">
                <td width="1%">
                    <i class="fa fa-fw fa-plus-square"></i>
                </td>
                <td colspan="4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" @click="saveFolder">Save</button>
                            <button class="btn btn-outline-secondary" type="button" @click="newFolder = null">Cancel
                            </button>
                        </div>
                        <input type="text" class="form-control" v-model="newFolder">
                    </div>
                </td>
            </tr>
            <tr v-for="folder in filteredFolders" class="pointer">
                <td width="1%">
                    <i class="fa fa-fw fa-folder"></i>
                </td>
                <td colspan="2" @click="openFolder(folder)">{{ folder.name }}</td>
                <td width="1%" @click="askToDeleteFolder(folder)">
                    <i class="fa fa-fw fa-trash"></i>
                </td>
            </tr>
            <template v-if="currentFolder">
                <tr v-for="file in currentFolder.files" class="pointer">
                    <td width="1%">
                        <i :class="iconForExtension(file.extension)"></i>
                    </td>
                    <td colspan="2">{{ file.filename }}.{{ file.extension }}</td>
                    <td width="1%" @click="askToDeleteFile(file)">
                        <i class="fa fa-fw fa-trash"></i>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    import FileUploader from './FileUploader.vue';
    export default {
        components: { FileUploader },
        props: ['extensions'],
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
                folderToDelete: null,
                fileToDelete: null,
                uploadsModal: false,
            }
        },
        created() {
            axios.get('/folders').then((response) => {
                this.folders = response.data;
            });
        },
        methods: {
            addUploadedFiles(files) {
                this.currentFolder.files = this.currentFolder.files.concat(files).unique('id');
            },
            openFolder(folder, index = null) {
                this.newFolder = null;
                this.history.push(folder);
                this.currentFolder = folder;
                if (index !== null) {
                    this.popHistory(index);
                }
            },
            askToDeleteFolder(folder) {
                this.folderToDelete = folder;
            },
            askToDeleteFile(file) {
                this.fileToDelete = file;
            },
            toggleUploadModal() {
                this.uploadsModal = !this.uploadsModal;
            },
            deleteFolder() {
                let self = this;
                axios.delete('/folders/' + this.folderToDelete.id).then((response) => {
                    alert(response.data.message);
                    let index = self.folders.indexOf(self.folderToDelete);
                    self.folders.splice(index, 1);
                    self.folderToDelete = null;
                }).catch(e => {
                    if (e.response.status == 404) {
                        let index = self.folders.indexOf(self.folderToDelete);
                        alert('This folder is already deleted');
                        self.folders.splice(index, 1);
                    } else {
                        alert(e.response.data.message);
                    }
                    self.folderToDelete = null;
                });
            },
            deleteFile() {
                let self = this;
                axios.delete('/files/' + this.fileToDelete.id).then((response) => {
                    alert(response.data.message);
                    let index = self.currentFolder.files.indexOf(self.fileToDelete);
                    self.currentFolder.files.splice(index, 1);
                    self.fileToDelete = null;
                }).catch(e => {
                    if (e.response.status == 404) {
                        alert('This file is already deleted');
                        let index = self.currentFolder.files.indexOf(self.fileToDelete);
                        self.currentFolder.files.splice(index, 1);
                        self.fileToDelete = null;
                    } else {
                        alert(e.response.data.message);
                    }
                });
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
    ol.breadcrumb {
        margin: 0;
    }

    table.table {
        margin-bottom: 0;
    }

    img.glyphs {
        width: 20px;
        height: 20px;
    }

    .modal-sm {
        min-width: 22em !important;
    }

    .modal {
        display: block;
    }
</style>
