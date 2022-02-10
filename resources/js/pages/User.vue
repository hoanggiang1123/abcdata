<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="users"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                        <v-toolbar-title>List User</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create User</v-btn>
                    </v-toolbar>
                </template>
                <template #[`item.name`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'name')"
                        @save="handleSaveItem('name')"
                    >
                        <v-list-item>{{ item.name ? item.name : 'No Name' }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="name" label="Edit Name"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.email`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'email')"
                        @save="handleSaveItem('email')"
                    >
                        <v-list-item>{{ item.email ? item.email : 'No Email' }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="email" label="Edit Email"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.roles`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'roles')"
                        @save="handleSaveItem('role_ids')"
                        large
                    >
                        <div v-if="item.roles.length">
                            <v-chip v-for="role in item.roles" :key="role.id" small class="ma-2">
                                {{ role.name }}
                            </v-chip>
                        </div>
                        <v-list-item v-else>No Role</v-list-item>
                        <template #input>
                            <v-card width="500px" elevation="0">
                                <v-card-title>
                                    Edit Role
                                </v-card-title>
                                <v-card-text style="max-height: 600px;" class="fix-height mb-4">
                                    <v-row>
                                        <v-col cols="4" v-for="rol in roles" :key="rol.id">
                                            <v-checkbox
                                                v-model="role_ids"
                                                :label="rol.name"
                                                color="red"
                                                :value="rol.id"
                                                hide-details
                                                >
                                            </v-checkbox>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.password`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'password')"
                        @save="handleSaveItem('password')"
                    >
                        <v-list-item>*****</v-list-item>
                        <template #input>
                            <v-text-field v-model="password" label="Edit Password"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.action`]="{item}">
                    <v-btn color="red" outlined @click="handleDeleteRole(item.id)">Delete</v-btn>
                 </template>
            </v-data-table>
        </v-col>
        <CreateUserDialog :roles="roles" :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" />
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import CreateUserDialog from '../components/CreateUserDialog.vue';

export default {
    components: {
        CreateUserDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '5%' },
                { text: 'Name', value: 'name', align: 'center', width: '10%'},
                { text: 'User Name', value: 'username', align: 'center', width: '15%'},
                { text: 'Email', value: 'email', align: 'center', width: '20%', sortable: false},
                { text: 'Password', value: 'password', align: 'center', width: '15%', sortable: false},
                { text: 'Roles', value: 'roles', align: 'center', width: '20%', sortable: false},
                { text: 'Action', value: 'action', align: 'center',  width: '15%', sortable: false},
            ],
            loading: false,
            openCreateDialog: false,
            role_ids: [],
            name: '',
            email: '',
            password: '',
            editItem: {},
            openCreateDialog: false
        }
    },
    computed: {
        ...mapGetters('user', {
            users: 'getListUser'
        }),
        ...mapGetters('roleAndPermision', {
            roles: 'getListRole'
        })
    },
    created () {
        this.loadPageData();
    },
    methods: {
        ...mapActions('user', ['getListUser', 'updateUser']),
        ...mapActions('roleAndPermision', ['getListRole']),
        async loadPageData () {
            try {
                await Promise.all([
                    this.getListUser(),
                    this.getListRole()
                ])
            } catch (err) {
                console.log(err)
            }
        },
        handelOpenEditItem(item, index) {
            this.editItem = item;
            if (index === 'roles') {
                const role_ids = item.roles.map(item => {
                    return item.id
                });
                this.role_ids = role_ids;
            } else {
                this[index] = item[index];
            }

        },
        handleSaveItem (index) {
            let update = {...this.editItem, name: this.name, email: this.email, role_ids: this.role_ids };
            if (this.password) update = { ...update, password: this.password }
            delete update.roles;
            this.loading = true;
            this.updateUser(update)
                .then(resp => {
                    this.resetPageData();
                    this.loadPageData();
                    this.loading = false;
                })
                .catch(err => {
                    this.resetPageData();
                    this.loading = false;
                    console.log(err.response)
                    alert(err.response && err.response.data && err.response.data.message ? err.response.data.message : 'err');
                });
        },
        resetPageData () {
            this.name = '',
            this.email = ''
            this.role_ids = [];
            this.password = '';
            this.editItem = {};
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.loadPageData();
        }
    }
}
</script>

<style>

</style>
