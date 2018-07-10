<template>
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3">
        <div class="row mt-4">
            <div class="col-6">
                <logo-uploader :img="user.logo"></logo-uploader>
            </div>
            <div class="col-6">
                <div class="card-body">
                    <h5 class="card-title">{{ fullName }}</h5>
                    <form>
                        <div class="form-row">
                            <div class="form-check col-12">
                                <input class="form-check-input" type="checkbox" id="changePassword"
                                       v-model="passwordChange">
                                <label class="form-check-label" for="changePassword">
                                    I want to change password
                                </label>
                            </div>
                            <div class="form-group col-md-6" v-if="passwordChange">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Password"
                                       v-model="password">
                            </div>
                            <div class="form-group col-md-6" v-if="passwordChange">
                                <label for="confirmPassword">Confirm password</label>
                                <input type="password" v-model="password_confirmation" class="form-control"
                                       id="confirmPassword" placeholder="Repeat password">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="suburb">Suburb</label>
                                <input type="text" class="form-control" id="suburb" v-model="user.suburb">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputState">State</label>
                                <input type="text" class="form-control" id="inputState" v-model="user.state">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Address</label>
                            <input type="text" class="form-control" id="inputAddress" v-model="user.business_address">
                        </div>
                        <div class="alert alert-secondary" role="alert">
                            Full address will be displayed as:
                            <br>
                            {{ fullAddress }}
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck"
                                       v-model="user.network_visible">
                                <label class="form-check-label" for="gridCheck">
                                    Show me on Network Page
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary" @click.prevent="updateProfile">Update profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import LogoUploader from './LogoUploader.vue';
    export default {
        props: ['auth'],
        components: {
            'logo-uploader': LogoUploader
        },
        computed: {
            fullName() {
                return this.user.first_name + ' ' + this.user.last_name;
            },
            fullAddress() {
                return this.user.business_address + ', ' + this.user.suburb + ', ' + this.user.state;
            }
        },
        data() {
            return {
                user: null,
                passwordChange: false,
                password: '',
                password_confirmation: '',
            }
        },
        created() {
            document.title = 'Profile';
            this.user = this.auth;
        },
        methods: {
            updateProfile() {
                if (this.passwordChange && (this.password !== this.password_confirmation || this.password.length <= 6)) {
                    alert('Passwords are not the same or too short!');
                } else {
                    if (this.passwordChange) {
                        this.user.password = this.password;
                        this.user.password_confirmation = this.password_confirmation;
                    }
                    axios.post('/profile', this.user).then((r) => {
                        alert('Success');
                    }).catch((e) => {
                        alert('Error');
                    });
                }
            }
        }
    }
</script>
<style>
    .card {
        width: 100%;
    }
</style>