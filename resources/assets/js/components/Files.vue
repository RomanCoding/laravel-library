<template>
    <div>
        <tabs>
            <tab name="File Explorer">
                <div class="col-xs-3 offset-9 text-right">
                    <button class="btn btn-success"
                            @click="massDownload"
                            :disabled="!downloadsList.length">
                        Download
                    </button>
                </div>
                <div class="card card-default">
                    <div class="card-body">
                        <li v-if="user.access_level > 1">
                            <div v-for="model in filteredItems">
                                <item :model="model" accessible="true"
                                      @requestedDownload="downloadFile"
                                      @addedToDownloads="toDownloads">
                                </item>
                            </div>
                        </li>
                        <li v-else>
                            <div v-for="model in filteredItems">
                                <item :model="model" :accessible="model.accessible_1 !== 0"
                                      @requestedDownload="downloadFile"
                                      @addedToDownloads="toDownloads">
                                </item>
                            </div>
                        </li>
                    </div>
                </div>
            </tab>
            <tab name="Browse">
                <div class="row">
                    <div class="col-5 col-sm-4 col-md-3 col-xl-2">
                        <div class="search-form">
                            <div class="card-header">
                                Search
                            </div>
                            <div class="filters">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" v-model="filters.filename.use">
                                    <label class="form-check-label">Filename</label>
                                    <select class="custom-select" v-if="filters.filename.use"
                                            v-model="filters.filename.algh">
                                        <option value="is">is</option>
                                        <option value="isn't">isn't</option>
                                        <option value="contains">contains</option>
                                        <option value="doesn't contain">doesn't contain</option>
                                        <option value="starts with">starts with</option>
                                        <option value="ends with">ends with</option>
                                    </select>
                                    <input type="text" class="form-control" placeholder="Type here"
                                           v-if="filters.filename.use" v-model="filters.filename.text">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" v-model="filters.extension.use">
                                    <label class="form-check-label">Extension</label>
                                    <div v-if="filters.extension.use">
                                        <span v-for="e in extensions" :class="badgeClass(e)"
                                              @click="toggleExtension(e)" v-text="e">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input">
                                    <label class="form-check-label">Updated date</label>
                                </div>
                                <button class="btn btn-primary" @click="applyFilters">Apply filters</button>
                                <button class="btn btn-text" @click="clearFilters">Clear</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 col-sm-8 col-md-9 col-xl-10">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col" @click="setSortKey('filename')">
                                    Filename <i class="fa fa-fw fa-sort" :style="arrowOpacity('filename')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('extension')">
                                    Extension <i class="fa fa-fw fa-sort" :style="arrowOpacity('extension')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('filesize')">
                                    Filesize <i class="fa fa-fw fa-sort" :style="arrowOpacity('filesize')"></i>
                                </th>
                                <th scope="col">Download</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="file in orderedFiles">
                                <th scope="row" v-text="file.filename" @click="downloadFile(file)"></th>
                                <td v-text="file.extension"></td>
                                <td v-text="getFileSize(file.filesize)"></td>
                                <td>td</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </tab>
        </tabs>
    </div>
</template>

<script>
    import Item from './Item.vue';
    import {Tabs, Tab} from 'vue-tabs-component';

    let download = require('downloadjs');

    export default {
        components: {Item, Tabs, Tab},
        props: ['user', 'extensions'],
        computed: {
            orderedFiles: function () {
                return _.orderBy(this.filteredFiles, this.sortKey, this.reverse ? 'desc' : 'asc')
            },
            filteredItems: function () {
                let currentFolderId = this.currentFolderId;
                let folders = _.filter(this.folders, function (o) {
                    return (o.parent_id === currentFolderId);
                });
                let files = _.filter(this.files, function (o) {
                    return (o.folder === null || o.folder.parent_id === null);
                });

                return folders.concat(files);
            }
        },
        data() {
            return {
                sortKey: 'filename',
                reverse: false,
                files: [],
                filteredFiles: [],
                folders: [],
                currentFolderId: [],
                filters: {
                    filename: {
                        use: false,
                        text: '',
                        algh: 'is'
                    },
                    extension: {
                        use: false,
                        text: [],
                    }
                },
                downloadsList: [],
            }
        },
        created() {
            axios.get('/folders').then(r => {
                this.folders = r.data;
                let rootFolder = _.find(this.folders, f => f.parent_id === null);
                this.rootFolderId = rootFolder ? rootFolder.id : null;
            });
            axios.get('/folders/root').then(r => {
                this.folders = r.data.folders;
                this.currentFolderId = r.data.id;
            });
//            axios.get('/files').then(r => {
//                this.files = r.data;
//                this.filteredFiles = r.data;
//            });
        },
        methods: {
            downloadFile(file) {
                axios({
                    url: '/downloads/files/' + file.id,
                    method: 'get',
                    withCredentials: true,
                    responseType: 'blob',
                    headers: {
                        'Accept': file.filename + '.' + file.extension
                    },
                }).then(r => {
                    download(r.data, file.filename + '.' + file.extension);
                }).catch((error) => {
                    alert (error.response.status == 403 ? 'No permissions to access this file' : 'Unknown error');
                });

            },
            massDownload() {
                this.downloadsList.forEach((file) => {
                    axios({
                        url: '/downloads/files/' + file.id,
                        method: 'get',
                        withCredentials: true,
                        responseType: 'blob',
                        headers: {
                            'Accept': file.filename + '.' + file.extension
                        },
                    }).then(r => {
                        download(r.data, file.filename + '.' + file.extension);
                    }).catch((error) => {
                        alert (error.response.status == 403 ? 'No permissions to access this file' : 'Unknown error');
                    });
                });
            },
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
            applyFilters() {
                this.filteredFiles = this.files;
                let filters = this.filters;
                if (this.filters.filename.use) {
                    switch (filters.filename.algh) {
                        case "is": {
                            this.filteredFiles = _.filter(this.filteredFiles, f => f.filename === filters.filename.text);
                            break;
                        }
                        case "isn't": {
                            this.filteredFiles = _.filter(this.filteredFiles, f => f.filename !== filters.filename.text);
                            break;
                        }
                        case "contains": {
                            this.filteredFiles = _.filter(this.filteredFiles, f => f.filename.includes(filters.filename.text));
                            break;
                        }
                        case "doesn't contain": {
                            this.filteredFiles = _.filter(this.filteredFiles, f => !f.filename.includes(filters.filename.text));
                            break;
                        }
                        case "starts with": {
                            this.filteredFiles = _.filter(this.filteredFiles, f => f.filename.startsWith(filters.filename.text));
                            break;
                        }
                        case "ends with": {
                            this.filteredFiles = _.filter(this.filteredFiles, f => f.filename.endsWith(filters.filename.text));
                            break;
                        }
                    }
                }
                if (this.filters.extension.use) {
                    this.filteredFiles = this.filteredFiles.filter(f => filters.extension.text.includes('.' + f.extension));
                }
            },
            clearFilters() {
                this.filters = {
                    filename: {
                        use: false,
                        text: '',
                        algh: 'is'
                    },
                    extension: {
                        use: false,
                        text: []
                    }
                };
            },
            toggleExtension(extension) {
                if (this.filters.extension.text.indexOf(extension) !== -1) {
                    this.filters.extension.text.splice(this.filters.extension.text.indexOf(extension), 1);
                } else {
                    this.filters.extension.text.push(extension);
                }
            },
            badgeClass(extension) {
                return (this.filters.extension.text.indexOf(extension) === -1) ? "badge badge-secondary" : "badge badge-success";
            },
            setSortKey(key) {
                if (this.sortKey === key) {
                    this.reverse = !this.reverse;
                }
                else {
                    this.sortKey = key;
                    this.reverse = false;
                }
            },
            arrowOpacity(key) {
                return this.sortKey === key ? 'opacity: 1' : 'opacity: 0.1';
            },
            toDownloads(file) {
                let index = this.downloadsList.find(x => x.id === file.id);
                if (index === undefined) {
                    this.downloadsList.push(file);
                } else {
                    this.downloadsList.splice(index, 1);
                }
            },
        }
    }
</script>
<style>
    div.search-form {
        box-sizing: border-box;
        border: 2px solid #dee2e6;
    }

    .search-form .card-header {
        font-weight: bold;
    }

    div.search-form .filters {
        padding: 5px;
        line-height: 1.6;
    }

    .form-check select,
    .form-check input {
        margin-left: -1.25rem;
    }

    .form-check input {
        margin-top: 5px;
    }

    .filters button {
        margin-top: 10px;
    }

    thead tr th {
        cursor: pointer;
    }

    li a:hover {
        text-decoration: none;
        color: inherit;
    }

    li {
        list-style: none;
    }

    img.glyphs {
        width: 20px;
        height: 20px;
    }

    span.badge {
        cursor: pointer;
        margin-right: 5px;
    }

    .tabs-component {
        margin: 0.5em 0;
    }

    .tabs-component-tabs {
        border: solid 1px #ddd;
        border-radius: 6px;
        margin-bottom: 5px;
    }

    @media (min-width: 700px) {
        .tabs-component-tabs {
            border: 0;
            align-items: stretch;
            display: flex;
            justify-content: flex-start;
            margin-bottom: -1px;
        }
    }

    .tabs-component-tab {
        color: #999;
        font-size: 14px;
        font-weight: 600;
        margin-right: 0;
        list-style: none;
    }

    .tabs-component-tabs:not(.is-active) .tabs-component-tab {
        border-bottom: none;
    }

    .tabs-component-tab:not(:last-child) {
        border-bottom: dotted 1px #ddd;
    }

    .tabs-component-tab:hover {
        color: #666;
    }

    .tabs-component-tab.is-active {
        color: #000;
    }

    .tabs-component-tab.is-disabled * {
        color: #cdcdcd;
        cursor: not-allowed !important;
    }

    @media (min-width: 700px) {
        .tabs-component-tab {
            background-color: #fff;
            border: solid 1px #ddd;
            border-radius: 3px 3px 0 0;
            margin-right: .5em;
            transform: translateY(2px);
            transition: transform .3s ease;
        }

        .tabs-component-tab.is-active {
            border-bottom: solid 1px #fff;
            z-index: 2;
            transform: translateY(0);
        }
    }

    .tabs-component-tab-a {
        align-items: center;
        color: inherit;
        display: flex;
        padding: .75em 1em;
        text-decoration: none;
    }

    .tabs-component-panels {
        padding: 4em 0;
    }

    @media (min-width: 700px) {
        .tabs-component-panels {
            border-top-left-radius: 0;
            background-color: #fff;
            border: solid 1px #ddd;
            border-radius: 0 6px 6px 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .05);
            padding: 0.5em;
        }
    }
</style>
