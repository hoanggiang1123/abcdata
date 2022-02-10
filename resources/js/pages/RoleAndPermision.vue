<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="listRole"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                        <v-toolbar-title>Role & Permision</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create Role</v-btn>
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
                <template #[`item.description`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'description')"
                        @save="handleSaveItem('description')"
                    >
                        <v-list-item>{{ item.description ? item.description : 'No Description' }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="description" label="Edit Description"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.permisions`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'permisions')"
                        @save="handleSaveItem('permision_ids')"
                       large

                    >
                        <div v-if="item.permisions.length">
                            <v-chip v-for="permision in item.permisions" :key="permision.id" small class="ma-2">
                                {{ permision.name }}
                            </v-chip>
                        </div>
                        <template #input>
                            <v-card max-width="600px" elevation="0">
                                <v-card-title>
                                    Edit Permision
                                </v-card-title>
                                <v-card-text style="max-height: 600px;" class="fix-height mb-4">
                                    <v-card v-for="permision in permisions" :key="permision.id" color="#313131" class="mb-2">
                                        <template v-if="permision.permision_children.length">
                                            <v-card-title>
                                                <v-checkbox
                                                    :label="permision.description"
                                                    color="red"
                                                    :value="1"
                                                    hide-details
                                                    @change="handleSelectAllPermision($event, permision)"
                                                >
                                                </v-checkbox>
                                            </v-card-title>
                                            <v-divider horizontal></v-divider>
                                            <v-card-text>
                                                <v-row>
                                                    <v-col cols="4" v-for="per in permision.permision_children" :key="per.id">
                                                        <v-checkbox
                                                            v-model="permision_ids"
                                                            :label="per.name"
                                                            color="red"
                                                            :value="per.id"
                                                            hide-details
                                                            >
                                                        </v-checkbox>
                                                    </v-col>
                                                </v-row>
                                            </v-card-text>
                                        </template>
                                    </v-card>
                                </v-card-text>
                            </v-card>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.action`]="{item}">
                    <v-btn color="red" outlined @click="handleDeleteRole(item.id)">Delete</v-btn>
                 </template>
            </v-data-table>
        </v-col>
        <CreateRoleAndPermisionDialog :permisions="permisions" :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" />
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import CreateRoleAndPermisionDialog from '../components/CreateRoleAndPermisionDialog.vue';

export default {
    components: {
        CreateRoleAndPermisionDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '5%' },
                { text: 'Name', value: 'name', align: 'center', width: '15%'},
                { text: 'Description', value: 'description', align: 'center', width: '20%'},
                { text: 'Permisions', value: 'permisions', align: 'center', width: '50%', sortable: false},
                { text: 'Action', value: 'action', align: 'center',  width: '10%', sortable: false},
            ],
            loading: false,
            openCreateDialog: false,
            permision_ids: [],
            name: '',
            description: '',
            editItem: {}
        }
    },
    computed: {
        ...mapGetters('roleAndPermision', {
            listRole: 'getListRole',
            permisions: 'getListPermision'
        })
    },
    created () {
        this.loadPageData();
    },
    methods: {
        ...mapActions('roleAndPermision', ['getListRole', 'getListPermision', 'updateRoleAndPermision']),
        async loadPageData () {
            try {
                await Promise.all([
                    this.getListRole(),
                    this.getListPermision()
                ]);
            } catch (err) {
                console.log(err);
            }
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.loadPageData();
        },
        handleDeleteRole (id) {
            console.log(id)
        },
        handelOpenEditItem(item, index) {
            this.editItem = item;
            if (index === 'permisions') {
                const permision_ids = item.permisions.map(item => {
                    return item.id
                });
                this.permision_ids = permision_ids;
            } else {
                this[index] = item[index];
            }

        },
        handleSaveItem (index) {
            const update = {...this.editItem, name: this.name, description: this.description, permision_ids: this.permision_ids };
            delete update.permisions;
            this.loading = true;
            this.updateRoleAndPermision(update)
                .then(resp => {
                    this.resetPageData();
                    this.loadPageData();
                    this.loading = false;
                })
                .catch(err => {
                    this.resetPageData();
                    this.loading = false;
                    console.log(err)
                });
        },
        handleSelectAllPermision (value, permision) {
            const data = permision.permision_children;
            const permisionIds = [];
            data.forEach(item => {
                permisionIds.push(item.id);
            });
            if (value) {
                this.permision_ids = this.permision_ids.filter(item => !permisionIds.includes(item));
                this.permision_ids = [...this.permision_ids, ...permisionIds]
            } else {
                this.permision_ids = this.permision_ids.filter(item => !permisionIds.includes(item));
            }
        },
        resetPageData () {
            this.name = '',
            this.description = ''
            this.permision_ids = [];
            this.editItem = {};
        }

    }
}
</script>

<style>

</style>
