<template>
    <div>
        <div class="modal fade show" tabindex="-1" role="dialog" v-if="userToDelete">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">User deleting</h5>
                        <button type="button" class="close" aria-label="Close" @click="userToDelete=null">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        You are about to delete user {{ userToDelete.email }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="userToDelete=null">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="deleteUser">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <tabs>
            <button class="btn btn-sm btn-success" style="position: fixed; top: 10%; z-index:99" @click="toggleSearch" v-show="!search">Search</button>
            <tab name="Manage">
                <div class="row py-0">
                    <div class="col-5 col-sm-4 col-md-3 col-xl-2" v-if="search">
                        <div class="search-form">
                            <div class="card-header">
                                Search
                                <span class="pull-right pointer" @click="toggleSearch">x</span>
                            </div>
                            <div class="filters">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" v-model="filters.first_name.use">
                                    <label class="form-check-label">First Name</label>
                                    <input type="text" class="form-control" placeholder="Type here"
                                           v-if="filters.first_name.use" v-model="filters.first_name.text">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" v-model="filters.last_name.use">
                                    <label class="form-check-label">Last Name</label>
                                    <input type="text" class="form-control" placeholder="Type here"
                                           v-if="filters.last_name.use" v-model="filters.last_name.text">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" v-model="filters.business_name.use">
                                    <label class="form-check-label">Business Name</label>
                                    <input type="text" class="form-control" placeholder="Type here"
                                           v-if="filters.business_name.use" v-model="filters.business_name.text">
                                </div>

                                <!--<input type="text" class="form-control" placeholder="First name" v-model="filters.first_name">-->
                                <button class="btn btn-primary" @click="applyFilters">Apply filters</button>
                                <button class="btn btn-text" @click="clearFilters">Clear</button>
                            </div>
                        </div>
                    </div>
                    <div :class="search ? 'col-7 col-sm-8 col-md-9 col-xl-10' : 'col-12'">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col" @click="setSortKey('email')">
                                    Email<i class="fa fa-fw fa-sort" :style="arrowOpacity('email')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('access_level')">
                                    Access<i class="fa fa-fw fa-sort" :style="arrowOpacity('access_level')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('first_name')">
                                    First Name<i class="fa fa-fw fa-sort" :style="arrowOpacity('first_name')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('last_name')">
                                    Last Name<i class="fa fa-fw fa-sort" :style="arrowOpacity('last_name')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('business_name')">
                                    Business Name<i class="fa fa-fw fa-sort" :style="arrowOpacity('business_name')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('suburb')">
                                    Suburb<i class="fa fa-fw fa-sort" :style="arrowOpacity('suburb')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('state')">
                                    State<i class="fa fa-fw fa-sort" :style="arrowOpacity('state')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('website')">
                                    Website<i class="fa fa-fw fa-sort" :style="arrowOpacity('website')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('network_visible')">
                                    Network<i class="fa fa-fw fa-sort" :style="arrowOpacity('network_visible')"></i>
                                </th>
                                <th scope="col" @click="setSortKey('lastSeen')">
                                    Last Seen<i class="fa fa-fw fa-sort" :style="arrowOpacity('lastSeen')"></i>
                                </th>
                                <th scope="col">
                                    &nbsp;
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in paginatedUsers">
                                <th scope="row">
                                    <i class="fa fa-edit" @click="editUser(user)" v-if="!user.edit"></i>
                                    <template v-else style="max-width: 20%;">
                                        <i class="fa fa-save" @click="updateUser(user)"></i>
                                        <i class="fa fa-close" @click="editUser(user)"></i>
                                    </template>
                                    <span v-if="!user.edit" v-text="user.email"></span>
                                    <input type="text" class="form-control form-control-sm"
                                           style="display: inline-block; max-width: 80%;"
                                           v-model="user.reserveCopy.email"
                                           v-else>
                                </th>
                                <td>
                                    <span v-if="!user.edit" v-text="user.access_level"></span>
                                    <select class="form-control form-control-sm" v-model="user.reserveCopy.access_level"
                                            v-else>
                                        <option v-for="n in 3" :value="n" v-text="n"></option>
                                    </select>
                                </td>
                                <td>
                                    <span v-if="!user.edit" v-text="user.first_name"></span>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="user.reserveCopy.first_name" v-else>
                                </td>
                                <td>
                                    <span v-if="!user.edit" v-text="user.last_name"></span>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="user.reserveCopy.last_name"
                                           v-else>
                                </td>
                                <td>
                                    <span v-if="!user.edit" v-text="user.business_name"></span>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="user.reserveCopy.business_name" v-else>
                                </td>
                                <td>
                                    <span v-if="!user.edit" v-text="user.suburb"></span>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="user.reserveCopy.suburb"
                                           v-else>
                                </td>
                                <td>
                                    <span v-if="!user.edit" v-text="user.state"></span>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="user.reserveCopy.state"
                                           v-else>
                                </td>
                                <td>
                                    <span v-if="!user.edit" v-text="user.website"></span>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="user.reserveCopy.website" v-else>
                                </td>
                                <td>
                                    <span v-if="!user.edit">{{ user.network_visible ? '+' : '-' }}</span>
                                    <input type="checkbox" class="form-control form-control-sm"
                                           v-model="user.reserveCopy.network_visible" v-else>
                                </td>
                                <td>
                                    <span>{{ user.lastSeen }}</span>
                                </td>
                                <td>
                                    <i class="fa fa-trash" style="cursor: pointer" @click="askToDeleteUser(user)"></i>
                                </td>
                            </tr>
                            <nav aria-label="...">
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.page > 2">
                                        <a class="page-link" @click="pagination.page=1">First</a>
                                    </li>
                                    <li :class="paginationClass(pagination.page-1)" v-if="pagination.showPrev">
                                        <a class="page-link" @click="pagination.page--">
                                            {{ pagination.page - 1 }}
                                        </a>
                                    </li>
                                    <li :class="paginationClass(pagination.page)">
                                        <a class="page-link">
                                            {{ pagination.page }}
                                        </a>
                                    </li>
                                    <li :class="paginationClass(pagination.page+1)" v-if="pagination.showNext">
                                        <a class="page-link" @click="pagination.page++">
                                            {{ pagination.page + 1 }}
                                        </a>
                                    </li>
                                    <li class="page-item" v-if="pagination.showNext">
                                        <a class="page-link" @click="pagination.page = pagination.maxPage">Last</a>
                                    </li>
                                </ul>
                            </nav>
                            </tbody>
                        </table>
                    </div>
                </div>
            </tab>
            <tab name="Add">
                <form>
                    <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail (*)</label>
                        <div class="col-md-6">
                            <input id="email" type="email" :class="getInputClass('email')" v-model="newUser.email"
                                   autofocus>
                            <span class="invalid-feedback" v-if="errors.creating.email">
                                <strong v-text="errors.creating.email[0]"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="first_name" class="col-sm-4 col-form-label text-md-right">First Name (*)'</label>
                        <div class="col-md-6">
                            <input id="first_name" type="text" :class="getInputClass('first_name')"
                                   v-model="newUser.first_name">
                            <span class="invalid-feedback" v-if="errors.creating.first_name">
                                <strong v-text="errors.creating.first_name[0]"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="last_name" class="col-sm-4 col-form-label text-md-right">Last Name (*)</label>
                        <div class="col-md-6">
                            <input id="last_name" type="text" :class="getInputClass('last_name')"
                                   v-model="newUser.last_name">
                            <span class="invalid-feedback" v-if="errors.creating.last_name">
                                <strong v-text="errors.creating.last_name[0]"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="business_name" class="col-sm-4 col-form-label text-md-right">Business Name</label>
                        <div class="col-md-6">
                            <input id="business_name" type="text" :class="getInputClass('business_name')"
                                   v-model="newUser.business_name">
                            <span class="invalid-feedback" v-if="errors.creating.business_name">
                                <strong v-text="errors.creating.business_name[0]"></strong>
                            </span>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="website"
                               class="col-sm-4 col-form-label text-md-right">Website</label>
                        <div class="col-md-6">
                            <input id="website" type="text" :class="getInputClass('website')"
                                   v-model="newUser.website">
                            <span class="invalid-feedback" v-if="errors.creating.website">
                                <strong v-text="errors.creating.website[0]"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="business_address"
                               class="col-sm-4 col-form-label text-md-right">Business Address</label>
                        <div class="col-md-6">
                            <input id="business_address" type="text" :class="getInputClass('business_address')"
                                   v-model="newUser.business_address">
                            <span class="invalid-feedback" v-if="errors.creating.business_address">
                                <strong v-text="errors.creating.business_address[0]"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Password (*)</label>
                        <div class="col-md-6">
                            <input id="password" type="password" :class="getInputClass('password')"
                                   v-model="newUser.password">
                            <span class="invalid-feedback" v-if="errors.creating.password">
                                <strong v-text="errors.creating.password[0]"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Repeat Password (*)</label>
                        <div class="col-md-6">
                            <input id="password_confirmation" type="password"
                                   :class="getInputClass('password_confirmation')"
                                   v-model="newUser.password_confirmation">
                            <span class="invalid-feedback" v-if="errors.creating.password_confirmation">
                                <strong v-text="errors.creating.password_confirmation[0]"></strong>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button @click="createUser" class="btn btn-primary">
                                Create user
                            </button>
                        </div>
                    </div>
                </form>
            </tab>
            <tab name="Stats">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Users statistics</h5>
                        <p class="card-text">Learn more about your users.</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Total users: <b v-text="stats.total"></b></li>
                        <li class="list-group-item">Level 3 users: <b v-text="stats.level3"></b></li>
                        <li class="list-group-item">Level 2 users: <b v-text="stats.level2"></b></li>
                        <li class="list-group-item">Level 1 users: <b v-text="stats.level1"></b></li>
                    </ul>
                </div>
            </tab>
        </tabs>
    </div>
</template>

<script>
    import {Tabs, Tab} from 'vue-tabs-component';
    import { cookies } from './mixins/cookies';

    export default {
        components: {Tabs, Tab},
        props: ['data'],
        mixins: [cookies],
        computed: {
            orderedUsers: function () {
                return _.orderBy(this.filteredUsers, this.sortKey, this.reverse ? 'desc' : 'asc')
            },
            paginatedUsers: function () {
                return this.orderedUsers.slice((this.pagination.page - 1) * this.pagination.perPage, this.pagination.page * this.pagination.perPage);
            },
            stats: function () {
                return {
                    total: this.users.length,
                    level1: this.users.filter(u => u.access_level === 1).length,
                    level2: this.users.filter(u => u.access_level === 2).length,
                    level3: this.users.filter(u => u.access_level === 3).length,
                }
            }
        },
        data() {
            return {
                users: [],
                filteredUsers: [],
                sortKey: 'access_level',
                reverse: true,
                newUser: {},
                userToDelete: null,
                search: true,
                pagination: {
                    page: 1,
                    perPage: 10,
                    showPrev: false,
                    showNext: false,
                    maxPage: 1
                },
                filters: {
                    first_name: {
                        use: false,
                        text: '',
                    },
                    last_name: {
                        use: false,
                        text: '',
                    },
                    business_name: {
                        use: false,
                        text: '',
                    },
                },
                errors: {
                    creating: {},
                }
            }
        },
        watch: {
            'orderedUsers': function(newValue) {
                this.pagination.showPrev = this.pagination.page > 1;
                this.pagination.showNext = this.orderedUsers.length > (this.pagination.page * this.pagination.perPage);
            },
            'pagination.page': function (newPagination) {
                this.pagination.showPrev = this.pagination.page > 1;
                this.pagination.showNext = this.orderedUsers.length > (this.pagination.page * this.pagination.perPage);
            }
        },
        created() {
            this.users = this.filteredUsers = _.map(this.data, function (user) {
                user.edit = false;
                user.reserveCopy = null;
                return user;
            });
            if (this.pagination.page === 1) {
                this.pagination.showPrev = false;
            }
            this.pagination.showNext = this.orderedUsers.length > (this.pagination.page * this.pagination.perPage);
            this.pagination.maxPage = ~~(this.orderedUsers.length / this.pagination.perPage) + 1;

            this.search = this.getCookie('search', true);
        },
        methods: {
            editUser(user) {
                if (user.edit) {
                    user.reserverCopy = null;
                    user.edit = false;
                } else {
                    user.edit = true;
                    user.reserveCopy = Object.assign({}, user);
                }
            },
            updateUser(user) {
                axios.patch('/users/' + user.id, user.reserveCopy).then(function (response) {
                    alert('Updated');
                    user.edit = false;
                    user.email = user.reserveCopy.email;
                    user.first_name = user.reserveCopy.first_name;
                    user.last_name = user.reserveCopy.last_name;
                    user.business_name = user.reserveCopy.business_name;
                    user.website = user.reserveCopy.website;
                    user.access_level = user.reserveCopy.access_level;
                    user.network_visible = user.reserveCopy.network_visible;
                    user.suburb = user.reserveCopy.suburb;
                    user.state = user.reserveCopy.state;
                    user.reserveCopy = null;
                }).catch(() => alert('Oops, error...'));
            },
            createUser() {
                axios.post('/users', this.newUser).then(r => {
                    this.users.push(r.data);
                    this.errors.creating = {};
                    this.newUser = {};
                    alert('User added');
                }).catch(e => {
                    this.errors.creating = e.response.data.errors;
                    alert('Error creating');
                });
            },
            getInputClass(key) {
                return ['form-control', this.errors.creating.hasOwnProperty(key) ? 'is-invalid' : ''];
            },
            paginationClass(page) {
                return this.pagination.page === page ? ['page-item', 'active'] : 'page-item';
            },
            setSortKey(key) {
                if (this.sortKey === key) {
                    this.reverse = !this.reverse;
                }
                else {
                    this.sortKey = key;
                    this.reverse = false;
                }
            },
            arrowOpacity(key) {
                return this.sortKey === key ? 'opacity: 1' : 'opacity: 0.1';
            },
            applyFilters() {
                this.filteredUsers = this.users;
                let filters = this.filters;
                if (this.filters.first_name.use) {
                    this.filteredUsers = _.filter(this.filteredUsers, f => f.first_name.includes(filters.first_name.text));
                }
                if (this.filters.last_name.use) {
                    this.filteredUsers = _.filter(this.filteredUsers, f => f.last_name.includes(filters.last_name.text));
                }
                if (this.filters.business_name.use) {
                    this.filteredUsers = _.filter(this.filteredUsers, f => (f.business_name && f.business_name.includes(filters.business_name.text)));
                }
            },
            askToDeleteUser(user) {
                this.userToDelete = user;
            },
            deleteUser() {
                axios.delete('/users/' + this.userToDelete.id).then(r => {
                    let index = this.users.indexOf(this.userToDelete);
                    this.users.splice(index, 1);
                    this.userToDelete = null;
                }).catch(e => {
                    if (e.response.status == 404) {
                        alert('This file is already deleted');
                        let index = this.currentFolder.files.indexOf(this.userToDelete);
                        this.currentFolder.files.splice(index, 1);
                        this.userToDelete = null;
                    } else {
                        alert(e.response.data.message);
                    }
                });
            },
            clearFilters() {
                this.filters = {
                    first_name: {
                        use: false,
                        text: '',
                    },
                    last_name: {
                        use: false,
                        text: '',
                    },
                    business_name: {
                        use: false,
                        text: '',
                    },
                };
                this.filteredUsers = this.users;
            },
            toggleSearch() {
                this.search = !this.search;
                this.setCookie('search', this.search);
            }
        }
    }
</script>
<style>
    tbody th i.fa {
        font-size: 20px;
    }

    thead tr th:not(:first-child) {
        cursor: pointer;
    }

    li a:hover {
        text-decoration: none;
        color: inherit;
    }

    form.search-form {
        box-sizing: border-box;
        border: 2px solid #dee2e6;
    }

    .search-form .card-header {
        font-weight: bold;
    }

    form.search-form .filters {
        padding: 5px;
        line-height: 1.6;
    }

    .form-check select,
    .form-check input {
        margin-left: -1.25rem;
    }

    .form-check input {
        margin-top: 5px;
    }

    .filters button {
        margin-top: 10px;
    }

    thead tr th {
        cursor: pointer;
    }

    li a:hover {
        text-decoration: none;
        color: inherit;
    }

    li {
        list-style: none;
    }

    img.glyphs {
        width: 20px;
        height: 20px;
    }
    i.fa.fa-trash {
        cursor: pointer;
        color: inherit;
        transition: .5s ease-in;
    }
    i.fa.fa-trash:hover {
        color: red;
        transition: .5s ease-in;
    }

    span.badge {
        cursor: pointer;
    }

    .tabs-component {
        margin: 0.5em 0;
    }

    .tabs-component-tabs {
        border: solid 1px #ddd;
        border-radius: 6px;
        margin-bottom: 5px;
    }

    @media (min-width: 700px) {
        .tabs-component-tabs {
            border: 0;
            align-items: stretch;
            display: flex;
            justify-content: flex-start;
            margin-bottom: -1px;
        }
    }

    .tabs-component-tab {
        color: #999;
        font-size: 14px;
        font-weight: 600;
        margin-right: 0;
        list-style: none;
    }

    .tabs-component-tabs:not(.is-active) .tabs-component-tab {
        border-bottom: none;
    }

    .tabs-component-tab:not(:last-child) {
        border-bottom: dotted 1px #ddd;
    }

    .tabs-component-tab:hover {
        color: #666;
    }

    .tabs-component-tab.is-active {
        color: #000;
    }

    .tabs-component-tab.is-disabled * {
        color: #cdcdcd;
        cursor: not-allowed !important;
    }

    @media (min-width: 700px) {
        .tabs-component-tab {
            background-color: #fff;
            border: solid 1px #ddd;
            border-radius: 3px 3px 0 0;
            margin-right: .5em;
            transform: translateY(2px);
            transition: transform .3s ease;
        }

        .tabs-component-tab.is-active {
            border-bottom: solid 1px #fff;
            z-index: 2;
            transform: translateY(0);
        }
    }

    .tabs-component-tab-a {
        align-items: center;
        color: inherit;
        display: flex;
        padding: .75em 1em;
        text-decoration: none;
    }

    .tabs-component-panels {
        padding: 4em 0;
    }

    @media (min-width: 700px) {
        .tabs-component-panels {
            border-top-left-radius: 0;
            background-color: #fff;
            border: solid 1px #ddd;
            border-radius: 0 6px 6px 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .05);
            padding: 1em;
        }
    }
    .modal {
        display: block;
    }
</style>
