<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                v-model="selected"
                :headers="headers"
                :items="listPopup"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                show-select
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                        <v-toolbar-title>Popup</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-text-field
                            v-model="domain_filter"
                            label="Search Domain"
                            outlined
                            dense
                            hide-details=""
                            append-icon="mdi-feature-search-outline"
                            @click:append="handleSearchDomain"
                            class="mr-2"
                        ></v-text-field>
                        <v-btn color="red" @click="handleClearFilter"><v-icon>mdi-close</v-icon></v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="indigo" @click="handleEditAllPost" class="mr-2"><v-icon>mdi-edit</v-icon> Edit All Popup</v-btn>
                        <v-btn :disabled="!selected.length" color="primary" @click="handleClonePost" class="mr-2"><v-icon>mdi-content-copy</v-icon> Clone</v-btn>
                        <v-btn :disabled="!selected.length" color="pink" @click="handleDeleteMany" class="mr-2">Delete</v-btn>
                        <v-btn color="primary" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create Post</v-btn>
                    </v-toolbar>
                </template>
                <template #[`item.content`]="{item}">
                   <v-btn small color="warning" @click="handleEditContent(item)">Edit Content</v-btn>
                </template>
                <template #[`item.domain`]="{item}">
                     <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'domain')"
                        @save="handleSaveItem('domain')"
                    >
                        <v-list-item>{{ item.domain }}</v-list-item>
                        <template #input>
                            <v-text-field label="Domain" v-model="domain"></v-text-field>
                        </template>
                    </v-edit-dialog>
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
                <template #[`item.retarget`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'retarget')"
                        @save="handleSaveItem('retarget')"
                    >
                        <v-list-item>
                            {{ mapTarget[item.retarget] ? mapTarget[item.retarget] : ''  }}
                        </v-list-item>
                        <template #input>
                            <v-autocomplete
                                v-model="retarget"
                                :items="targets"
                                label="Assign To User"
                                item-text="name"
                                item-value="value"
                            >
                            </v-autocomplete>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.action`]="{item}">
                    <v-btn color="red" text small @click="handleDeleteItem(item.id)"><v-icon>mdi-delete</v-icon></v-btn>
                </template>
            </v-data-table>
            <div v-if="listPopup.length" class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="currentPage" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
        </v-col>
        <CreatePopupDialog :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" />
        <div class="backdrop"  v-if="dialog"></div>
        <transition name="scale">
            <div class="ckeditor-modal" v-if="dialog">
                <v-card class="pa-2">
                    <v-card-text>
                        <v-text-field v-if="editAllPost" label="Name" v-model="name_all" outlined></v-text-field>
                        <Ckeditor v-model="content" />
                        <v-row class="mt-4">
                            <v-col>
                                <v-btn v-if="!editAllPost" color="primary" @click="handleSaveContent" block><v-icon left>mdi-content-save</v-icon> Save</v-btn>
                                <v-btn v-if="editAllPost" color="primary" @click="handleSaveAllContent" block><v-icon left>mdi-content-save</v-icon> Save</v-btn>
                            </v-col>
                            <v-col>
                                <v-btn color="pink" @click="handleCancelEditContent" block>Cancel</v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </div>
        </transition>
    </v-row>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import Ckeditor from '../components/Ckeditor.vue';
import CreatePopupDialog from '../components/CreatePopupDialog.vue';
export default {
    components: {
        Ckeditor,
        CreatePopupDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '5%' },
                { text: 'Domain', value: 'domain', align: 'center', width: '15%'},
                { text: 'Name', value: 'name', align: 'center', width: '25%'},
                { text: 'Content', value: 'content', align: 'center', width: '25%'},
                { text: 'Retarget', value: 'retarget', align: 'center', width: '5%'},
                { text: 'Action', value: 'action', align: 'center',  width: '20%', sortable: false},
            ],
            domain: '',
            content: '',
            name: '',
            retarget: '',
            per_page: 30,
            currentPage: 1,
            loading: false,
            editItem: {},
            dialog: false,
            selected: [],
            openCreateDialog: false,
            domain_filter: '',
            editAllPost: false,
            name_all: '',
            mapTarget: { '1': 'Retarget', '0': 'Once' },
            targets: [{ name: 'Retarget', value: 1}, { name: 'Once', value: 0 }]
        }
    },
    computed: {
        ...mapGetters('popup', {
            listPopup: 'getListPopup',
            pagination: 'getListPopupPagination'
        })
    },
    created () {
        this.loadPageData();
    },
    methods: {
        ...mapActions('popup', ['getListPopup', 'updatePopup', 'deletePopup', 'createMany', 'updateAllPopup']),
        async loadPageData () {
            try {
                this.loading = true;
                await this.getListPopup({
                    per_page: this.per_page,
                    page: this.currentPage,
                    domain: this.domain_filter,
                    position: this.position_filter
                });
                this.loading = false;
            } catch (err) {
                this.loading = false;
                console.log(err)
            }
        },
        resetPageData () {
            this.domain = '';
            this.content = '';
        },
        handelOpenEditItem(item, index) {
            this.editItem = item;
            this[index] = item[index];

        },
        handleSaveItem (index) {
            let update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updatePopup(update)
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
        handleEditContent (item) {
            this.handelOpenEditItem(item, 'content');
            this.dialog = true;
        },
        handleCancelEditContent () {
            this.editItem = {};
            this.content = '';
            this.dialog = false;
            this.editAllPost = false;
            this.name_all = '';
        },

        async handleSaveContent () {
            try {
                await this.handleSaveItem('content');
                this.handleCancelEditContent();
            } catch (err) {
                this.handleCancelEditContent();
                alert(err.message ? err.message : 'An Error Occur');
            }
        },
        handleDeleteItem (id) {
            const deleteConfirm = confirm('are you sure to delete ?');

            if (deleteConfirm) {
                this.deletePopup({ ids: [id] })
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
                    this.deletePopup({ ids })
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
            this.currentPage = currentPage;
            this.loadPageData();
        },
        handleClonePost () {
            if (!this.selected.length) {
                alert('Pls select at least 1 row to clone');
                return;
            }

            this.createMany(this.selected)
                .then(resp => {
                    this.selected = [];
                    this.loadPageData();
                })
                .catch(err => {
                    console.log(err);
                })
        },
        handleSelectPosition () {
            this.loadPageData();
        },
        handleSearchDomain () {
            this.loadPageData();
        },
        handleClearFilter () {
            this.position_filter = '';
            this.domain_filter = '';
            this.loadPageData();
        },
        handleEditAllPost () {
            this.content = '';
            this.dialog = true;
            this.editAllPost = true;

        },
        handleSaveAllContent () {
            if (this.content === '' && this.name_all === '') alert('Content & Name can not be empty');
            this.updateAllPopup({ content: this.content, name: this.name_all }).then(resp => {
                console.log(resp);
                this.dialog = false;
                this.editAllPost = false;
                this.content = '';
                this.name_all = '';
                this.resetPageData();
                this.loadPageData();
            }).catch(err => {
                this.resetPageData();
                alert(err.message ? err.message : 'An Error Occur');
            })
        }
    }
}
</script>

<style scoped>
.backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9998;
}
.ckeditor-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    max-width: 678px;
}
.scale-enter-active, .scale-leave-active {
  transition: all .3s;
}
.scale-enter, .scale-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
