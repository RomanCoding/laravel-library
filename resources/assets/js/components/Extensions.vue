<template>
    <div class="card card-default">
        <div class="card-header">
            Extensions
        </div>
        <div class="card-body">
            <div class="row">
                <div class="list-group col-6">
                    <a class="list-group-item clearfix" v-for="(ext, index) in allExtensions">
                        {{ ext.ext }}
                        <span class="pull-right">
                        <button class="btn btn-xs" @click.prevent="destroy(ext, index)">x</button>
                    </span>
                    </a>
                </div>
                <div class="col-6">
                    <h4>Add extension</h4>
                    <div class="form-group mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">.</div>
                            </div>
                            <input type="text" class="form-control" placeholder="Type extension" v-model="ext">
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-info" type="button" @click="save">Add</button>
                            </div>
                        </div>
                        <small class="text-muted">
                            Must be 1-12 characters long.
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        props: ['extensions'],
        computed: {},
        data() {
            return {
                allExtensions: [],
                ext: '',
            }
        },
        created() {
            this.allExtensions = this.extensions;
        },
        methods: {
            destroy(ext, index) {
                let self = this;
                axios.delete('/manage/extensions/' + ext.id).then(r => {
                    self.allExtensions.splice(index, 1);
                });
            },
            save() {
                if (this.ext.length > 0 && this.ext.length <= 12) {
                    let self = this;
                    axios.post('/manage/extensions', {
                        'ext': this.ext
                    }).then(r => {
                        if (!self.allExtensions.find((e) => e.ext === this.ext)) {
                            self.allExtensions.push(r.data.extension);
                        }
                        self.ext = '';
                    });
                } else {
                    alert('Extension should be 1-12 characters long!');
                }
            }
        }
    }
</script>
<style scoped>
    .list-group-item {
        padding: 0.25rem 1.25rem;
        line-height: 2.5rem;
    }
    textarea:focus,
    textarea.form-control:focus,
    input.form-control:focus,
    input[type=text]:focus,
    [type=text].form-control:focus,
    button.btn:focus {
        box-shadow: inset 0 0px 0 #ddd;
    }
</style>