<template>
    <div>
        <form enctype="multipart/form-data" novalidate v-if="isInitial || isSaving" >
            <h1 style="text-align: center;">
                Upload files
                <button type="button" class="close" aria-label="Close" @click="$emit('closed')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h1>
            <div class="dropbox">
                <input type="file" multiple :name="uploadFieldName" :disabled="isSaving"
                       @change="filesChange($event.target.name, $event.target.files); fileCount = $event.target.files.length"
                       :accept="extensions"
                        class="input-file">
                <!--accept=".docx,.pptx,.pdf,.doc,.csv,.jpg,.png,.mp4"-->
                <p v-if="isInitial">
                    Drag your file(s) here to begin<br> or click to browse
                </p>
                <p v-if="isSaving">
                    Uploading {{ fileCount }} files...
                </p>
            </div>
        </form>

        <div class="modal fade show" v-if="isSuccess" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Uploaded {{ uploadedFiles.length }} file(s).</h5>
                        <button type="button" class="close" aria-label="Close" @click="$emit('closed')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-unstyled" v-if="failedUploads.length">
                            <h3>Failed uploads:</h3>
                            <li v-for="item in failedUploads">
                                <p>{{ item }}</p>
                            </li>
                        </ul>
                        <p v-else>
                            All files successfully uploaded!
                        </p>
                        <p>
                            <a href="javascript:void(0)" @click="reset()">Upload more</a>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="$emit('closed')">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" v-if="isFailed" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload failed.</h5>
                        <button type="button" class="close" aria-label="Close" @click="$emit('closed')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p v-if="uploadError.message" v-text="uploadError.message"></p>
                        <ul v-if="uploadError.errors">
                            <li v-for="error in uploadError.errors" v-text="error[0]"></li>
                        </ul>
                        <p>
                            <a href="javascript:void(0)" @click="reset()">Try again</a>
                        </p>
                        <p>Or refresh page</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="$emit('closed')">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {upload} from '../file-upload.service';

    const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

    export default {
        props: ['folder', 'extensions'],
        data() {
            return {
                uploadedFiles: [],
                failedUploads: [],
                uploadError: null,
                currentStatus: null,
                uploadFieldName: 'files'
            }
        },

        computed: {
            isInitial() {
                return this.currentStatus === STATUS_INITIAL;
            },
            isSaving() {
                return this.currentStatus === STATUS_SAVING;
            },
            isSuccess() {
                return this.currentStatus === STATUS_SUCCESS;
            },
            isFailed() {
                return this.currentStatus === STATUS_FAILED;
            }
        },
        methods: {
            reset() {
                // reset form to initial state
                this.currentStatus = STATUS_INITIAL;
                this.uploadedFiles = [];
                this.uploadError = null;
            },
            save(formData) {
                // upload data to the server
                this.currentStatus = STATUS_SAVING;
                upload(formData)
                    .then(x => {
                        this.uploadedFiles = [].concat(x.success);
                        this.failedUploads = [].concat(x.failed);
                        this.currentStatus = STATUS_SUCCESS;
                        this.$emit('uploaded', this.uploadedFiles);
                    })
                    .catch(error => {
                        this.uploadError = error.response.data;
                        this.currentStatus = STATUS_FAILED;
                    });
            },
            filesChange(fieldName, fileList) {
                // handle file changes
                const formData = new FormData();

                if (!fileList.length) return;

                // append the files to FormData
                Array
                    .from(Array(fileList.length).keys())
                    .map(x => {
                        formData.append('files[]', fileList[x], fileList[x].name);
                    });
                formData.append('folder_id', this.folder.id);
                // save it
                this.save(formData);
            }
        },
        mounted() {
            this.reset();
        },
    }

</script>

<!-- SASS styling -->
<style lang="scss">
    .modal {
        display: block;
    }

    .dropbox {
        outline: 2px dashed grey; /* the dash box */
        outline-offset: -10px;
        background: lightcyan;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px; /* minimum height */
        position: relative;
        cursor: pointer;
    }

    .input-file {
        opacity: 0; /* invisible but it's there! */
        width: 100%;
        height: 200px;
        position: absolute;
        cursor: pointer;
    }

    .dropbox:hover {
        background: lightblue; /* when mouse over to the drop zone, change color */
    }

    .dropbox p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 0;
    }
</style>