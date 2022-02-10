<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                v-model="selected"
                :headers="headers"
                :items="listKeywords"
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
                        <!-- <v-text-field
                            v-model="domain_filter"
                            label="Search Domain"
                            outlined
                            dense
                            hide-details=""
                            append-icon="mdi-feature-search-outline"
                            @click:append="handleSearchDomain"
                            class="mr-2"
                        ></v-text-field> -->
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
                        <v-text-field
                            v-model="code"
                            label="KeyCode"
                            outlined
                            dense
                            hide-details=""
                            class="mr-2"
                            append-icon="mdi-content-save"
                            @click:append="handleSaveKeyCode"
                            :disabled="disableCode"
                        >

                        </v-text-field>
                        <v-btn v-if="disableCode" color="warning" class="mr-2" @click="disableCode = false">Edit Code</v-btn>
                        <v-btn class="mr-2" color="primary" @click="handleCopySc('popup')">Popup Sc</v-btn>
                        <v-btn class="mr-2" color="warning" @click="handleCopySc('getcode')">Get code Sc</v-btn>
                        <v-btn :disabled="!selected.length" color="primary" @click="handleClonePost" class="mr-2"><v-icon>mdi-content-copy</v-icon> Clone</v-btn>
                        <v-btn :disabled="!selected.length" color="pink" @click="handleDeleteMany" class="mr-2">Delete</v-btn>
                        <v-btn color="primary" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create Post</v-btn>
                    </v-toolbar>
                </template>
                <template #[`item.content`]="{item}">
                   <v-btn small color="warning" @click="handleEditContent(item)">Edit Content</v-btn>
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
                <template #[`item.appearable_time`]="{item}">
                     <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'appearable_time')"
                        @save="handleSaveItem('appearable_time')"
                    >
                        <v-list-item>{{ item.appearable_time }}</v-list-item>
                        <template #input>
                            <v-text-field label="Appearable Time" v-model="appearable_time"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.action`]="{item}">
                    <v-btn color="red" text small @click="handleDeleteItem(item.id)"><v-icon>mdi-delete</v-icon></v-btn>
                </template>
            </v-data-table>
            <div v-if="listKeywords.length" class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="current_page" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
        </v-col>
        <CreateKeywordDialog :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" />
        <div class="backdrop"  v-if="dialog"></div>
        <transition name="scale">
            <div class="ckeditor-modal" v-if="dialog">
                <v-card class="pa-2">
                    <v-card-text>
                        <Ckeditor v-model="content" />
                        <v-row class="mt-4">
                            <v-col>
                                <v-btn color="primary" @click="handleSaveContent" block><v-icon left>mdi-content-save</v-icon> Save</v-btn>
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
import CreateKeywordDialog from '../components/CreateKeywordDialog.vue';
export default {
    components: {
        Ckeditor,
        CreateKeywordDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true },
                { text: 'Name', value: 'name', align: 'center', width: '15%'},
                { text: 'Content', value: 'content', align: 'center', width: '50%'},
                { text: 'Appearable Time', value: 'appearable_time', align: 'center', width:'10%'},
                { text: 'Action', value: 'action', align: 'center',  width: '10%', sortable: false}
            ],
            name: '',
            content: '',
            appearable_time: 0,
            appear_time: '',
            per_page: 30,
            page: 1,
            current_page: 1,
            openCreateDialog: false,
            dialog: false,
            selected: [],
            loading: false,
            name_search: '',
            code: '',
            disableCode: true
        }
    },
    computed: {
        ...mapGetters('keyword', {
            listKeywords: 'getListKeywords',
            pagination: 'getListKeywordPagination',
            keycode: 'getKeyCode'
        })
    },
    watch: {
        keycode (val) {
            this.code = val.code ? val.code : '';
        }
    },
    created () {
        this.loadPageData();
        this.getKeyCode()
    },
    methods: {
        ...mapActions('keyword', ['getListKeywords', 'createMany', 'deleteKeyword', 'updateKeyword', 'getKeyCode', 'updateKeyCode']),
        async loadPageData () {
            try {
                this.loading = true;
                await this.getListKeywords({
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
            this.content = '';
            this.appearable_time = 0;
        },
        handelOpenEditItem(item, index) {
            this.editItem = item;
            this[index] = item[index];

        },
        handleSaveItem (index) {
            let update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateKeyword(update)
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
                this.deleteKeyword({ ids: [id] })
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
                    this.deleteKeyword({ ids })
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

            this.createMany(this.selected)
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
        },
        handleCopySc (type, keyword) {
            if (type === 'popup') {
                this.$clipboard('[etpopup title="Title for PopUp" name="Name of Unlock Button"]HIDE_CONTENT_HERE[/etpopup]' );
            }
            else {
                this.$clipboard('[etgetcode name="Name of Get Code Button" time="60"]');
            }
        },
        async handleSaveKeyCode () {
            if (this.code === '') {
                alert('Pls enter key code');
                return;
            }
            const data = { ...this.keycode, code: this.code };
            try {
                this.loading = true;
                await this.updateKeyCode(data);
                this.getKeyCode();
                this.loading = false;
                this.disableCode = true;
            } catch (err) {
                console.log(err);
                alert('An error occurr')
                this.loading = false;
            }
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
