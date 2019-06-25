<template>
    <div class="text-center">

        <img :src="image" class="img-responsive" @click="trigger" :style="imgStyle">

        <input ref="file" type="file" v-on:change="onFileChange" class="form-control" style="display: none;">

        <button v-if="!image" class="btn btn-info btn-lg mt-3" @click="trigger">{{ this.buttonText }}</button>
    </div>
</template>
<style scoped>
    img {
        cursor: pointer;
    }
</style>
<script>
    export default {
        props: {
            'img': String,
            'maxImageHeight': {
                type: Number,
                default: 0
            },
            'uploadOnChange': {
                type: Boolean,
                default: true
            },
            'actionUrl': {
                type: String,
                default: '/profile/logo'
            },
            'buttonText': {
                type: String,
                default: 'Choose logo',
            }
        },
        data() {
            return {
                image: '',
                sendRequest: false
            }
        },
        created() {
            if (this.img) {
                this.image = this.img;
            }
            if (this.uploadOnChange) {
                this.sendRequest = this.uploadOnChange;
            }
        },
        computed: {
            imgStyle() {
                if (this.maxImageHeight > 0) {
                    return {
                        'max-height': `${this.maxImageHeight}px`,
                    }
                }
                return {
                    width: '100%'
                }
            }
        },
        methods: {
            trigger() {
                this.$refs.file.click();
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                    if (vm.sendRequest) {
                        vm.upload();
                    } else {
                      this.$emit('onload', vm.image);
                    }
                };
                reader.readAsDataURL(file);
            },
            upload(){
                axios.post(this.actionUrl, {image: this.image}).then(response => {

                });
            }
        }
    }
</script>