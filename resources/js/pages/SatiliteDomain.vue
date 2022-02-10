<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                v-model="selected"
                :headers="headers"
                :items="listDomain"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                show-select
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                        <v-toolbar-title>Post</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-text-field
                            v-model="name_search"
                            label="Search"
                            outlined
                            dense
                            hide-details=""
                            append-icon="mdi-feature-search-outline"
                            @click:append="handleSearchName"
                            class="ma-1"
                        >
                        </v-text-field>
                        <v-btn color="red" @click="handleClearFilter"><v-icon>mdi-close</v-icon></v-btn>
                        <v-spacer></v-spacer>
                        <v-btn :disabled="!selected.length" color="primary" @click="handleClonePost" class="mr-2"><v-icon>mdi-content-copy</v-icon> Clone</v-btn>
                        <v-btn :disabled="!selected.length" color="pink" @click="handleDeleteMany" class="mr-2">Delete</v-btn>
                        <v-btn color="primary" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create Post</v-btn>
                    </v-toolbar>
                </template>
                <template #[`item.name`]="{item}">
                     <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'name')"
                        @save="handleSaveItem('name')"
                    >
                        <v-list-item>{{ item.name }}</v-list-item>
                        <template #input>
                            <v-text-field label="Name" v-model="name"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.description`]="{item}">
                     <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'description')"
                        @save="handleSaveItem('description')"
                    >
                        <v-list-item>{{ item.description }}</v-list-item>
                        <template #input>
                            <v-text-field label="Description" v-model="description"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.action`]="{item}">
                    <v-btn color="red" text small @click="handleDeleteItem(item.id)"><v-icon>mdi-delete</v-icon></v-btn>
                </template>
            </v-data-table>
            <div v-if="listDomain.length" class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="current_page" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
        </v-col>
        <CreateSatiliteDomainDialog :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" />
    </v-row>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import CreateSatiliteDomainDialog from '../components/CreateSatiliteDomainDialog.vue';
export default {
    components: {
        CreateSatiliteDomainDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '10%' },
                { text: 'Name', value: 'name', align: 'center', width: '40%'},
                { text: 'Description', value: 'description', align: 'center', width: '40%'},
                { text: 'Action', value: 'action', align: 'center',  width: '10%', sortable: false}
            ],
            name: '',
            description: '',
            page: 1,
            current_page: 1,
            openCreateDialog: false,
            selected: [],
            loading: false,
            name_search: ''
        }
    },
    computed: {
        ...mapGetters('keyword', {
            listDomain: 'getListSatiliteDomain',
            pagination: 'getListSatiliteDomainPagination'
        })
    },
    created () {
        this.loadPageData();
    },
    methods: {
        ...mapActions('keyword', ['getListSatiliteDomain', 'updateSatiliteDomain', 'deleteSatiliteDomain', 'createManySatiliteDomain']),
        async loadPageData () {
            try {
                this.loading = true;
                await this.getListSatiliteDomain({
                    per_page: this.per_page,
                    page: this.current_page,
                    name: this.name ? this.name : this.name_search ? this.name_search : ''
                });
                this.loading = false;
            } catch (err) {
                this.loading = false;
                console.log(err)
            }
        },
        resetPageData () {
            this.name = '';
            this.description = '';
        },
        handelOpenEditItem(item, index) {
            this.editItem = item;
            this[index] = item[index];

        },
        handleSaveItem (index) {
            let update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateSatiliteDomain(update)
                .then(resp => {
                    this.resetPageData();
                    this.loadPageData();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    this.resetPageData();
                });
        },
        handleDeleteItem (id) {
            const deleteConfirm = confirm('are you sure to delete ?');

            if (deleteConfirm) {
                this.deleteSatiliteDomain({ ids: [id] })
                    .then(resp => {
                        this.loadPageData();
                    })
                    .catch (err => {
                        console.log(err);
                    })
            }
        },
        handleDeleteMany () {
            if (this.selected.length) {
                const deleteConfirm = confirm('are you sure to delete ?');
                const ids = this.selected.map(item => item.id);
                if (deleteConfirm) {
                    this.deleteSatiliteDomain({ ids })
                        .then(resp => {
                            this.loadPageData();
                        })
                        .catch (err => {
                            console.log(err);
                        })
                }
            }
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.loadPageData();
        },
        handleNewPage (currentPage) {
            this.current_page = currentPage;
            this.loadPageData();
        },
        handleClonePost () {
            if (!this.selected.length) {
                alert('Pls select at least 1 row to clone');
                return;
            }

            this.createManySatiliteDomain(this.selected)
                .then(resp => {
                    this.selected = [];
                    this.loadPageData();
                })
                .catch(err => {
                    console.log(err);
                })
        },
        handleClearFilter () {
            this.name_search = '';
            this.loadPageData();
        },
        handleSearchName () {
            if(this.name_search === '') alert('Pls enter keywords to search...');
            this.loadPageData();
        }
    }
}
</script>

<style scoped>
</style>
