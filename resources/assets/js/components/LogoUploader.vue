<template>
    <div class="text-center">

        <img :src="image" class="img-responsive">

        <input ref="file" type="file" v-on:change="onFileChange" class="form-control" style="display: none;">

        <button class="btn btn-info btn-lg mt-3" @click="trigger">Choose logo</button>
    </div>
</template>
<style scoped>
    img {
        width: 100%;
    }
</style>
<script>
    export default {
        props: ['img'],
        data() {
            return {
                image: ''
            }
        },
        created() {
            if (this.img) {
                this.image = this.img;
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
                    vm.upload();
                };
                reader.readAsDataURL(file);
            },
            upload(){
                axios.post('/profile/logo', {image: this.image}).then(response => {

                });
            }
        }
    }
</script>