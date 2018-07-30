<template>
    <div class="row px-2">
        <div class="col-6 col-md-4" v-for="user in users">
            <div class="row mt-4 network-user">
                <div class="col-6">
                    <img class="card-img-top" :src="usersLogo(user)" alt="Logo">
                </div>
                <div class="col-6">
                    <div class="card-body">
                        <h5 class="card-title">{{ user.business_name || 'Business name' }}</h5>
                        <p class="card-text">
                            <template v-if="user.phone">Phone: {{ user.phone }}</template>
                            <a :href="user.website" v-text="user.website" v-if="user.website" target="_blank"></a>
                            <br>
                            Membership Level: <span :class="membershipWord(user)" v-text="membershipWord(user)"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                users: [],
            }
        },
        created() {
            let self = this;
            axios.get('/network/users').then(r => self.users = r.data);
        },
        methods: {
            membershipWord(user) {
                switch (user.access_level) {
                    case 2:
                        return 'Gold';
                    case 3:
                        return 'Some'; //@todo change
                    default:
                        return 'Bronze';
                }
            },
            usersLogo(user) {
                return user.logo || '/images/logos/default.png';
            },
            address(user) {
                let result = '';
                if (user.suburb) {
                    result += user.suburb;
                }
                if (user.state) {
                    result += ((result.length ? ', ' : '') + user.state);
                }
                return result;
            },
        }
    }
</script>
<style scoped>
    .card-img-top {
        margin-top: 0.5rem;
        max-height: 250px;
    }
    .network-user {
        border: 2px solid rgb(237, 239, 241);
    }
    .network-user > .col-6 {
        height: 250px;
        padding-left: 0.5rem;
    }
    .Gold {
        font-weight: bold;
        color: #FFD700;
    }
    .Bronze {
        font-weight: bold;
        color: #CD7F32;
    }
</style>