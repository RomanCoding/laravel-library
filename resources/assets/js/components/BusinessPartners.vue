<template>
    <div class="container-fluid bc-white">
        <div class="col-12 pt-2">
            <p>
                HR Coach has built a strong network of Business Partners to help compliment your HR coaching business.
                The below list provides an overview of their service offering and the benefits you receive from using their products or services.
                To find marketing collateral and referral process notes, please visit the Business Partners section of the <u>toolkit</u>.
            </p>
            <button class="btn btn-success" v-if="isAdmin" @click="manage = !manage">
                Manage partners
            </button>
        </div>

        <partner v-for="(partner, index) in partners"
                 :img="partner.img"
                 :contact="partner.contact"
                 :mail="partner.email"
                 :phone="partner.phone"
                 :url="partner.url"
                 :divider="(index !== partners.length - 1)">
            <template slot="title">{{ partner.title }}</template>
            <template slot="service">{{ partner.service }}</template>
            <template slot="about">{{ partner.about }}</template>
            <template slot="benefit">{{ partner.benefit }}</template>
        </partner>
        <footer class="px-4 m-4">
            © HR Coach Australasia 2018. This website and content is the intellectual property of HR Coach Australasia. Any disclosure, copying, display or misuse of this information is strictly prohibited without the written consent of HR Coach Australasia.
        </footer>
    </div>
</template>

<script>
    import Partner from './Partner.vue';
    export default {
        components: {
            'partner': Partner,
        },
        data() {
            return {
                user: null,
                partners: [],
                manage: false
            }
        },
        computed: {
            isAdmin() {
                return (this.user && this.user.access_level === 3);
            }
        },
        created() {
            axios.get('/user').then(r => this.user = r.data);
            axios.get('/api/partners').then(r => this.partners = r.data);
        },
    }
</script>

<style scoped>
    footer {
        font-size: 90%;
    }
</style>