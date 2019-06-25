<template>
    <div :class="[{ 'partner-deleted': partnerObject.deleted }, 'row']">
        <div class="col-2 d-flex align-items-center">
            <logo-uploader
                    v-if="manage"
                    :actionUrl=updateLogoUrl
                    :img="img">
            </logo-uploader>
            <img v-else class="img-fluid vertical-center" :src="img" alt="Logo">
        </div>
        <div class="col-10">
            <b><u><slot name="title"></slot></u></b><br>
            <b>Service Offering: </b><slot name="service"></slot><br>
            <b>About:</b> <slot name="about"></slot><br>
            <b>HR Coach Network Member Benefit:</b> <slot name="benefit"></slot><br>

            <div class="row">
                <div class="col-1">
                    <b>Contact:</b>
                </div>
                <div class="col-11">
                    <template v-if="!manage">{{ contact }}<br></template>
                    <input type="text" v-model="partnerObject.contact" class="form-control" v-else>

                    <template v-if="!manage">
                        <a :href="mailTo" v-text="partnerObject.email"></a>
                        <br>
                    </template>
                    <input type="email" v-model="partnerObject.email" class="form-control" v-else>

                    <template v-if="!manage">{{ phone }}<br></template>
                    <input type="text" v-model="partnerObject.phone" class="form-control" v-else>


                    <a :href="getUrl" target="_blank" v-text="url" v-if="!manage"></a>
                    <input type="url" v-model="partnerObject.url" class="form-control" v-else>
                </div>
            </div>
            <div>
                <slot name="buttons"></slot>
            </div>
        </div>
        <hr v-if="divider">

    </div>
</template>

<script>
    import LogoUploader from './LogoUploader.vue';
    export default {
        components: {
          'logo-uploader': LogoUploader
        },
        data() {
            return {
                partnerObject: null,
            }
        },
        created() {
            this.partnerObject = this.partner;
        },
        computed: {
            updateLogoUrl() {
              return `/api/partners/${this.partnerObject.id}/logo`;
            },
            mailTo() {
                return "mailto:" + this.mail;
            },
            getUrl() {
                return "//" + this.url;
            }
        },
        props: {
            'img': String,
            'partner': Object,
            'contact': String,
            'mail': String,
            'phone': String,
            'url': String,
            'divider': {
                type: Boolean,
                default: true,
            },
            'manage': {
                type: Boolean,
                default: false,
            }
        },
    }
</script>

<style scoped>
    hr {
        height: 1px;
        background-color: rgb(0, 85, 165);
        margin-top: 10px;
        margin-bottom: 10px;
        width: 100%;
        border-top: none;
    }
    .row {
        margin-left: 0;
    }
    .partner-deleted {
        background-color: #859571;
    }
</style>