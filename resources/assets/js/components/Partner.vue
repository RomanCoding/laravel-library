<template>
    <div class="row">
        <div class="col-2 d-flex align-items-center">
            <img class="img-fluid vertical-center" :src="img" alt="Acacia connection">
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
                    {{ contact }}<br>
                    <a :href="mailTo" v-text="mail"></a><br>
                    {{ phone }}<br>
                    <a :href="getUrl" target="_blank" v-text="url"></a>
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
    export default {
        computed: {
            mailTo() {
                return "mailto:" + this.mail;
            },
            getUrl() {
                return "//" + this.url;
            }
        },
        props: {
            'img': String,
            'contact': String,
            'mail': String,
            'phone': String,
            'url': String,
            'divider': {
                type: Boolean,
                default: true,
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
</style>