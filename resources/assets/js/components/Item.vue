<template>
    <div>
        <div class="row">
            <div class="col-md-6" v-if="isFolder()">
                <img class="glyphs pointer" :src="glyphSrc" @click="toggle">
                {{ model.name }}
            </div>
            <div class="col-md-6" v-else>
                <img class="glyphs" src="/glyphs/si-glyph-document.svg">
                <b v-text="model.filename" @click="requestDownload(model)" class="pointer" title="Click to download"></b>
            </div>
            <div class="col-md-3">
                {{ model.extension ? model.extension : '&nbsp;' }}
            </div>
            <div class="col-md-2">
                {{ isFolder() ? '&nbsp;' : getFileSize(model.filesize) }}
            </div>
            <div class="col-md-1" v-if="this.permissions">
                <div class="checkbox" v-if="isFolder()">
                    <label style="font-size: 1.5em" title="Allow access to Bronze Coaches">
                        <input type="checkbox" v-model="model.accessible_1" @click="togglePermission(model)">
                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                    </label>
                </div>
            </div>
            <div class="col-md-1 text-right" v-else>
                <div class="checkbox">
                    <label style="font-size: 1.5em" v-if="!isFolder()">
                        <input type="checkbox" value="" @click="addToDownloads(model)" :disabled="isFolder()">
                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                    </label>
                    <label style="font-size: 1em" v-else>&nbsp;</label>
                </div>
            </div>
        </div>
        <ul v-if="showChildren">
            <item v-for="(model, index) in model.children" :key="index" :model="model" :permissions="permissions"
                  @permissions="togglePermission"
                  @requestedDownload="requestDownload"
                  @addedToDownloads="addToDownloads">
            </item>
        </ul>
    </div>

</template>

<script>
    import Item from './Item.vue';

    export default {
        components: {Item},
        props: ['model', 'permissions', 'accessible'],
        computed: {
            orderedChildren: function () {
                return _.orderBy(this.model.children, this.sortKey, this.reverse ? 'desc' : 'asc')
            },
        },
        data() {
            return {
                showChildren: false,
                glyphSrc: "/glyphs/si-glyph-folder-plus.svg",
            };
        },

        methods: {
            isFolder() {
                return this.model.hasOwnProperty('parent_id');
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
//                let size = 0;
//                let self = this;
//                folder.files.forEach(function (file) {
//                    size += file.filesize;
//                });
//                folder.folders.forEach(function (childrenFolder) {
//                    size += self.getFolderSize(childrenFolder, false);
//                });
//                return formatted ? this.getFileSize(size) : size;
            },
            toggle() {
                if (!this.model.children || !this.model.children.length) {
                    let model = this.model;
                    axios.get('/folders/' + model.id).then(r => {
                        Vue.set(model, 'children', r.data);
                    });
                }
                this.showChildren = !this.showChildren;
                this.glyphSrc = this.showChildren ? '/glyphs/si-glyph-folder-open.svg' : '/glyphs/si-glyph-folder-plus.svg';
            },
            togglePermission(model) {
                this.$emit('permissions', model);
            },
            requestDownload(model) {
                this.$emit('requestedDownload', model);
            },
            addToDownloads(model) {
                this.$emit('addedToDownloads', model);
            },
        }
    }
</script>
<style scoped>
    img.glyphs {
        width: 20px;
        height: 20px;
    }

    ul {
        padding-left: 1em;
        list-style-type: none;
    }

    .checkbox label:after {
        content: '';
        display: table;
        clear: both;
    }

    .checkbox .cr {
        position: relative;
        display: inline-block;
        border: 1px solid #a9a9a9;
        border-radius: .25em;
        width: 1.3em;
        height: 1.3em;
        float: left;
        margin-right: .5em;
    }

    .checkbox .cr .cr-icon {
        position: absolute;
        font-size: .8em;
        line-height: 0;
        top: 50%;
        left: 20%;
    }

    .checkbox label input[type="checkbox"] {
        display: none;
    }

    .checkbox label input[type="checkbox"] + .cr > .cr-icon {
        transform: scale(3) rotateZ(-20deg);
        opacity: 0;
        transition: all .3s ease-in;
    }

    .checkbox label input[type="checkbox"]:checked + .cr > .cr-icon {
        transform: scale(1) rotateZ(0deg);
        opacity: 1;
    }

    .checkbox label input[type="checkbox"]:disabled + .cr {
        opacity: .5;
    }
</style>
