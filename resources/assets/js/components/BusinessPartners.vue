<template>
    <div class="container-fluid bc-white">
        <div class="modal fade show" tabindex="-1" role="dialog" aria-hidden="true" style="display: block;" v-if="showAddModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newPartnerModalLabel">New partner</h5>
                        <div class="col-6">
                            <logo-uploader @onload="onImageChange"
                                           :uploadOnChange=false
                                           :img="newPartner.logo"
                                           :maxImageHeight="100"
                                           buttonText="Partner logo">
                            </logo-uploader>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group mb-0">
                                <label for="newPartnerTitle" class="col-form-label">Title</label>
                                <input type="text" class="form-control" id="newPartnerTitle" v-model="newPartner.title">
                            </div>
                            <div class="form-group mb-0">
                                <label for="newPartnerService" class="col-form-label">Service offering</label>
                                <input type="text" class="form-control" id="newPartnerService" v-model="newPartner.service">
                            </div>
                            <div class="form-group mb-0">
                                <label for="newPartnerAbout" class="col-form-label">About</label>
                                <textarea class="form-control" id="newPartnerAbout" v-model="newPartner.about"></textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label for="newPartnerBenefit" class="col-form-label">Benefit</label>
                                <input type="text" class="form-control" id="newPartnerBenefit" v-model="newPartner.benefit">
                            </div>
                            <div class="form-group mb-0">
                                <div class="row">
                                    <div class="col">
                                        <label for="newPartnerContact" class="col-form-label">Contact</label>
                                        <input type="text" class="form-control" id="newPartnerContact" v-model="newPartner.contact">
                                    </div>
                                    <div class="col">
                                        <label for="newPartnerWebsite" class="col-form-label">Website</label>
                                        <input type="url" class="form-control" id="newPartnerWebsite" v-model="newPartner.url">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="row">
                                    <div class="col">
                                        <label for="newPartnerEmail" class="col-form-label">Email</label>
                                        <input type="email" class="form-control" id="newPartnerEmail" v-model="newPartner.email">
                                    </div>
                                    <div class="col">
                                        <label for="newPartnerPhone" class="col-form-label">Phone</label>
                                        <input type="text" class="form-control" id="newPartnerPhone" v-model="newPartner.phone">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="showAddModal = false">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" @click="addPartner">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 pt-2">
            <p>
                HR Coach has built a strong network of Business Partners to help compliment your HR coaching business.
                The below list provides an overview of their service offering and the benefits you receive from using
                their products or services.
                To find marketing collateral and referral process notes, please visit the Business Partners section of
                the <u>toolkit</u>.
            </p>
            <button class="btn btn-success" v-if="isAdmin" @click="manage = !manage">
                Manage partners
            </button>
            <button class="btn btn-info" @click="showAddModal = true">
                Add new
            </button>
        </div>

        <partner v-for="(partner, index) in partners"
                 :partner="partner"
                 :key="partner.id"
                 :img="partner.img"
                 :contact="partner.contact"
                 :mail="partner.email"
                 :phone="partner.phone"
                 :url="partner.url"
                 :manage="manage"
                 :divider="(index !== partners.length - 1)">
            <template slot="title">
                <input type="text" v-model="partner.title" class="form-control" v-if="manage">
                <template v-else>{{ partner.title }}</template>
            </template>
            <template slot="service">
                <input type="text" v-model="partner.service" class="form-control" v-if="manage">
                <template v-else>{{ partner.service }}</template>
            </template>
            <template slot="about">
                <textarea v-model="partner.about" class="form-control" v-if="manage"></textarea>
                <template v-else>{{ partner.about }}</template>
            </template>
            <template slot="benefit">
                <input type="text" v-model="partner.benefit" class="form-control" v-if="manage">
                <template v-else>{{ partner.benefit }}</template>
            </template>
            <template slot="buttons">
                <button class="btn btn-sm btn-danger" v-if="isAdmin && manage" @click="softDelete(partner, index)">
                    {{ partner.deleted ? 'Show' : 'Hide' }}
                </button>
                <button class="btn btn-sm btn-danger" v-if="partner.deleted && isAdmin && manage"
                        @click="hardDelete(partner, index)">
                    Delete
                </button>
                <button class="btn btn-sm btn-success" v-if="isAdmin && manage" @click="update(partner.id, partner)">
                    Save
                </button>
            </template>
        </partner>
        <footer class="px-4 m-4">
            © HR Coach Australasia 2018. This website and content is the intellectual property of HR Coach Australasia.
            Any disclosure, copying, display or misuse of this information is strictly prohibited without the written
            consent of HR Coach Australasia.
        </footer>
    </div>
</template>

<script>
    import Partner from './Partner.vue';
    import LogoUploader from './LogoUploader.vue';

    export default {
        components: {
            'partner': Partner,
            'logo-uploader': LogoUploader
        },
        data() {
            return {
                user: null,
                partners: [],
                manage: false,
                showAddModal: false,
                newPartner: {
                    title: '',
                    service: '',
                    about: '',
                    benefit: '',
                    contact: '',
                    email: '',
                    phone: '',
                    url: '',
                    logo: '',
                }
            }
        },
        computed: {
            isAdmin() {
                return (this.user && this.user.access_level === 3);
            }
        },
        created() {
            axios.get('/user')
                .then(r => this.user = r.data)
                .then(() => {
                  axios.get('/api/partners', {
                    params: {
                      deleted: this.isAdmin ? 0 : 1
                    }
                  }).then(r => this.partners = r.data);
                });
        },
        methods: {
            softDelete(partner, index) {
                axios.delete('/api/partners/' + partner.id).then(r => {
                    partner.deleted = !partner.deleted;
                }).catch(e => {
                    alert('Error!');
                });
            },
            hardDelete(partner, index) {
                let self = this;
                axios.delete(`/api/partners/${partner.id}?hard=1`).then(r => {
                    self.partners.splice(index, 1);
                }).catch(e => {
                    alert('Error!');
                });
            },
            update(id, partner) {
                let self = this;
                axios.patch('/api/partners/' + id, partner)
                    .then(() => {
                        alert('Updated!');
                        self.manage = false;
                    })
                    .catch(() => alert('Error!'));
            },
            addPartner() {
                axios.post('/api/partners', this.newPartner)
                    .then(r => {
                        alert('Done');
                    })
                    .catch(e => {
                        alert('Error');
                    });
            },
            onImageChange(value) {
                let self = this;
                self.newPartner.logo = value;
            },
        }
    }
</script>

<style scoped>
    footer {
        font-size: 90%;
    }
</style>