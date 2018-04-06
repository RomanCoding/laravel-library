<template>
    <div>
        <tabs>
            <tab name="Manage">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col" @click="setSortKey('email')">
                            Email<i class="fa fa-fw fa-sort" :style="arrowOpacity('email')"></i>
                        </th>
                        <th scope="col" @click="setSortKey('access_level')">
                            Access Level<i class="fa fa-fw fa-sort" :style="arrowOpacity('access_level')"></i>
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
                        <th scope="col" @click="setSortKey('business_address')">
                            Business Address<i class="fa fa-fw fa-sort" :style="arrowOpacity('business_address')"></i>
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
                                   style="display: inline-block; max-width: 80%;" v-model="user.email" v-else>
                        </th>
                        <td>
                            <span v-if="!user.edit" v-text="user.access_level"></span>
                            <select class="form-control form-control-sm" v-model="user.access_level" v-else>
                                <option v-for="n in 3" :value="n" v-text="n"></option>
                            </select>
                        </td>
                        <td>
                            <span v-if="!user.edit" v-text="user.first_name"></span>
                            <input type="text" class="form-control form-control-sm" v-model="user.first_name" v-else>
                        </td>
                        <td>
                            <span v-if="!user.edit" v-text="user.last_name"></span>
                            <input type="text" class="form-control form-control-sm" v-model="user.last_name" v-else>
                        </td>
                        <td>
                            <span v-if="!user.edit" v-text="user.business_name"></span>
                            <input type="text" class="form-control form-control-sm" v-model="user.business_name" v-else>
                        </td>
                        <td>
                            <span v-if="!user.edit" v-text="user.business_address"></span>
                            <input type="text" class="form-control form-control-sm" v-model="user.business_address" v-else>
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
                        <label for="business_name"
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
                        <label for="password" class="col-md-4 col-form-label text-md-right">Repeat Password (*)</label>
                        <div class="col-md-6">
                            <input id="password_confirmation" type="password" :class="getInputClass('password_confirmation')"
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

    export default {
        components: {Tabs, Tab},
        props: ['data'],
        computed: {
            orderedUsers: function() {
                return _.orderBy(this.users, this.sortKey, this.reverse ? 'desc' : 'asc')
            },
            paginatedUsers: function() {
                return this.orderedUsers.slice((this.pagination.page - 1) * this.pagination.perPage, this.pagination.page*this.pagination.perPage);
            },
            stats: function() {
                return {
                    total: this.users.length,
                    level1: this.users.filter(function(user) {
                        return user.access_level === 1;
                    }).length,
                    level2: this.users.filter(function(user) {
                        return user.access_level === 2;
                    }).length,
                    level3: this.users.filter(function(user) {
                        return user.access_level === 3;
                    }).length,
                }
            }
        },
        data() {
            return {
                users: [],
                sortKey: 'access_level',
                reverse: true,
                newUser: {},
                pagination: {
                    page: 1,
                    perPage: 10,
                    showPrev: false,
                    showNext: false,
                    maxPage: 1
                },
                errors: {
                    creating: {},
                }
            }
        },
        watch: {
            'pagination.page': function(newPagination) {
                this.pagination.showPrev = this.pagination.page > 1;
                this.pagination.showNext = this.orderedUsers.length > (this.pagination.page * this.pagination.perPage);
            }
        },
        created() {
            this.users = _.map(this.data, function (user) {
                user.edit = false;
                user.reserveCopy = null;
                return user;
            });
            if (this.pagination.page === 1) {
                this.pagination.showPrev = false;
            }
            this.pagination.showNext = this.orderedUsers.length > (this.pagination.page * this.pagination.perPage);
            this.pagination.maxPage = ~~(this.orderedUsers.length / this.pagination.perPage) + 1;
        },
        methods: {
            editUser(user) {
                if (user.edit) {
                    user.email = user.reserveCopy.email;
                    user.first_name = user.reserveCopy.first_name;
                    user.last_name = user.reserveCopy.last_name;
                    user.business_name = user.reserveCopy.business_name;
                    user.business_address = user.reserveCopy.business_address;
                    user.access_level = user.reserveCopy.access_level;
                    user.edit = false;
                } else {
                    user.edit = true;
                    user.reserveCopy = Object.assign({}, user);
                }
            },
            updateUser(user) {
                axios.patch('/users/' + user.id, user).then(function (response) {
                    alert('Updated');
                    user.edit = false;
                }).catch(function (error) {
                    alert('Oops, error...');
                    user.email = user.reserveCopy.email;
                    user.first_name = user.reserveCopy.first_name;
                    user.business_name = user.reserveCopy.business_name;
                    user.business_address = user.reserveCopy.business_address;
                    user.access_level = user.reserveCopy.access_level;
                    user.edit = false;
                });
            },
            createUser() {
                let self = this;
                axios.post('/users', this.newUser).then(function (response) {
                    self.users.push(response.data);
                    self.errors.creating = {};
                    self.newUser = {};
                    alert('User added');
                }).catch(function (error) {
                    self.errors.creating = error.response.data.errors;
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
            padding: 4em 2em;
        }
    }
</style>
