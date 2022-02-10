<template>
     <v-row>
        <v-col cols="12">
            <v-data-table
                v-model="selected"
                :headers="headers"
                :items="agents"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                show-select
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                    <v-toolbar-title>Lines</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <div class="d-flex ma-1" style="max-width: 150px;">
                        <v-autocomplete
                            :items="fields"
                            v-model="fieldName"
                            item-text="name"
                            item-value="value"
                            label="Search Field"
                            hide-details=""
                            solo
                            outlined
                            dense
                            clear-icon="mdi-close"
                            clearable
                        >
                        </v-autocomplete>
                    </div>
                    <v-text-field
                        v-model="fieldVal"
                        label="Search"
                        outlined
                        dense
                        hide-details=""
                        append-icon="mdi-feature-search-outline"
                        @click:append="handleSearchByField"
                        class="ma-1"
                        :disabled="!fieldName"
                    >
                    </v-text-field>
                    <v-btn color="success" class="ma-1" @click="resetPageData"><v-icon>mdi-reload</v-icon></v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="red" class="mr-2" @click="handleDeleteMany" :disabled="!selected.length">Delete</v-btn>
                    <v-btn color="primary" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create New</v-btn>
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
                <template #[`item.line`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'line_id')"
                        @save="handleSaveItem('line_id')"
                    >
                        <v-list-item>
                            {{ item.line && item.line.name ?  item.line.name : ''}}
                        </v-list-item>
                        <template #input>
                            <v-autocomplete
                                v-model="line_id"
                                :items="lines"
                                label="Lines"
                                item-text="name"
                                item-value="id"
                            >
                            </v-autocomplete>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.action`]="{item}">
                    <v-btn class="ma-1" color="pink" small @click="handleGoToTeleSale(item.name)">View ({{ item.telesales_count }})</v-btn>
                    <v-btn color="red" small class="ma-1" @click="handleDeleteLine(item.id)"><v-icon>mdi-delete</v-icon></v-btn>
                </template>
            </v-data-table>
            <div class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="currentPage" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
        </v-col>
        <CreateTeleSaleAgentDialog :openCreateDialog="openCreateDialog" :lines="lines" @close-create-dialog="handleCloseCreateDialog" />
     </v-row>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import CreateTeleSaleAgentDialog from '../components/CreateTeleSaleAgentDialog';
export default {
    components: {
        CreateTeleSaleAgentDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center'},
                { text: 'Name', value: 'name', align: 'center', width: '15%'},
                { text: 'Description', value: 'description', align: 'center'},
                { text: 'Line', value: 'line', align: 'center'},
                { text: 'Action', value: 'action', align: 'center'}
            ],
            selected: [],
            loading: false,
            fields: [
                {
                    name: 'Name', value: 'name'
                },
                {
                    name: 'Description', value: 'description'
                },
                {
                    name: 'Line', value: 'line'
                }
            ],
            fieldName: '',
            fieldVal: '',
            editItem: {},
            name: '',
            description: '',
            line: '',
            line_id: null,
            currentPage: 1,
            per_page: 30,
            openCreateDialog: false
        }
    },
    computed: {
        ...mapGetters('teleSale', {
            agents: 'getTeleSaleAgentList',
            pagination: 'getTeleSaleAgentPagination',
            lines: 'getTeleSaleLineList'
        })
    },
    created () {
        this.loadPageData();
        this.getTeleSaleLineOwn()
    },
    methods: {
        ...mapActions('teleSale', ['getTeleSaleAgent', 'updateTeleSaleAgent', 'deleteTeleSaleAgent', 'getTeleSaleLineOwn']),
        async loadPageData () {
            this.loading = true;
            try {
                await this.getTeleSaleAgent({
                    name: this.name,
                    description: this.description,
                    page: this.currentPage,
                    line_id: this.line_id,
                    line: this.line,
                    per_page: this.per_page
                });
                this.loading = false;
            } catch (err) {
                console.log(err);
                this.loading = false;
            }
        },
        handleSearchByField () {
            if (this.fieldVal) {
                this[this.fieldName] = this.fieldVal;
                this.loadPageData();
            }
        },
        handleDeleteMany () {
            const deleteConfirm = confirm('Are you sure want to delete, it might affect to tele sale data ?');

            if (deleteConfirm) {
                const ids = this.selected.map(item => item.id);
                this.loading = true;
                this.deleteTeleSaleAgent({ ids })
                .then(resp => {
                    this.loadPageData()
                    this.loading = false;
                }).catch (err => {
                    this.loading = false;
                    console.log(err)
                })
            }

        },
        handelOpenEditItem(item, index) {
            this.editItem = item;
            if (index === 'line_id') {
                this.line_id = item.line.id
            } else {
                this[index] = item[index];
            }

        },
        handleSaveItem (index) {
            let update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateTeleSaleAgent(update)
                .then(resp => {
                    this.resetPageData();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                });
        },
        handleGoToTeleSale (name) {
            this.$router.push('/data?agent=' + name);
        },
        handleDeleteLine (id) {
             const deleteConfirm = confirm('Are you sure want to delete, it might affect to tele sale data ?');

            if (deleteConfirm) {
                this.loading = true;
                this.deleteTeleSaleAgent({ ids: [id] })
                .then(resp => {
                    this.loadPageData()
                    this.loading = false;
                }).catch (err => {
                    this.loading = false;
                    console.log(err)
                })
            }
        },
        resetPageData () {
            this.name = '',
            this.description = '',
            this.line_id = null;
            this.fieldName = '';
            this.fieldVal = '';
            this.line = '';
            this.loadPageData();
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.loadPageData();
        },
        handleNewPage (currentPage) {
            this.currentPage = currentPage;
            this.loadPageData();
        }
    }
}
</script>

<style>

</style>
