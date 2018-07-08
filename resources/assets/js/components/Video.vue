<template>
    <div class="row">
        <div class="col-12" v-if="isAdmin">
            <div class="card">
                <div class="card-body">
                    Add new video.
                    <br>
                    Link should be like: <i>https://vimeo.com/278096174</i>

                    <div class="form-group row">
                        <label for="link" class="col-sm-2 col-form-label">Vimeo link</label>
                        <div class="col-sm-10">
                            <input v-model="link" type="text" class="form-control" id="link"
                                   placeholder="https://vimeo.com/278096174">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-2 col-form-label">Description (optional)</label>
                        <div class="col-sm-10">
                            <input v-model="description" type="text" class="form-control" id="description"
                                   placeholder="Info to show below the video">
                        </div>
                    </div>
                    <button class="btn btn-success" @click="save">Add webinar</button>
                </div>
            </div>
        </div>

        <template v-if="hasAccess">
            <div class="col-4 offset-1 py-1" v-for="(video, index) in videos">
                <iframe :src="video.link" height="360px" width="100%" frameborder="0" webkitallowfullscreen
                        mozallowfullscreen allowfullscreen></iframe>
                <p v-if="video.description" v-text="video.description"></p>

                <template v-if="isAdmin">
                    <p v-if="!video.display">
                        This video is visible only for admins. Click "Display" to make it visible.</p>
                    <button type="button" class="btn btn-sm btn-outline-primary"
                            v-text="video.display ? 'Hide' : 'Display'"
                            @click="toggleDisplay(video)">
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" @click="deleteVideo(video, index)">
                        Delete
                    </button>
                </template>

            </div>
        </template>
        <div class="col-6 offset-3" v-else>
            <div class="alert alert-info mx-auto mt-3" role="alert">
                Only users with Access Level 2 and higher can watch videos.
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                videos: [],
                user: null,
                link: '',
                description: '',
            }
        },
        created() {
            let self = this;
            axios.get('/user').then(r => self.user = r.data);
            axios.get('/videos?type=video').then(r => self.videos = r.data);
        },
        computed: {
            isAdmin() {
                return (this.user && this.user.access_level === 3);
            },
            hasAccess() {
                return (this.user && this.user.access_level >= 2);
            }
        },
        methods: {
            toggleDisplay(video) {
                axios.patch('/videos/' + video.id, {
                    display: video.display ? 0 : 1,
                }).then(r => {
                    video.display = r.data.display;
                }).catch(e => {
                    alert('Error!');
                });
            },
            deleteVideo(video, index) {
                let self = this;
                axios.delete('/videos/' + video.id).then(r => {
                    self.videos.splice(index, 1);
                }).catch(e => {
                    alert('Error!');
                });
            },
            save() {
                let self = this;
                axios.post('/videos', {
                    link: this.link,
                    description: this.description,
                    type: 'video'
                }).then(r => {
                    self.videos.push(r.data);
                }).catch(e => {
                    alert('Error!');
                });
            }
        }
    }
</script>