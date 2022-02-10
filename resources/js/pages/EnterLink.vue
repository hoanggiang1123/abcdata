<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="linkEnterLists"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                class="elevation-1"
                show-select
                v-model="selected"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                    <v-toolbar-title>Banners</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <!-- <v-btn @click="handleCreateEmbededCode" color="accent"><v-icon left>mdi-code-json</v-icon> Create Embeded Code</v-btn> -->

                    <div class="d-flex" style="max-width: 600px;">
                        <v-autocomplete
                                :items="folder"
                                v-model="folderId"
                                item-text="name"
                                item-value="id"
                                label="Folder"
                                hide-details=""
                                solo
                                background-color="purple"
                                class="mr-2"
                                dense
                                clear-icon="mdi-close"
                                clearable
                                @change="handleSelectFolder"
                            >
                        </v-autocomplete>
                        <v-autocomplete
                                :items="names"
                                v-model="nameName"
                                label="Name"
                                hide-details=""
                                solo
                                background-color="warning"
                                class="mr-2"
                                dense
                                clear-icon="mdi-close"
                                clearable
                                @change="handleSelectName"
                            >
                        </v-autocomplete>
                        <v-autocomplete
                                :items="links"
                                v-model="linkName"
                                label="Link"
                                hide-details=""
                                solo
                                background-color="success"
                                class="mr-2"
                                dense
                                clear-icon="mdi-close"
                                clearable
                                @change="handleSelectName"
                            >
                        </v-autocomplete>
                    </div>
                    <v-btn color="red" @click="handleClearFilter()">Clear Filter</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="red" class="mr-2" @click="handleDeleteMany">Delete</v-btn>
                    <v-btn color="purple" class="mr-2" @click="handleCloneLink"><v-icon left>mdi-content-copy</v-icon> Clone</v-btn>
                    <v-btn color="primary" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create New</v-btn>
                    </v-toolbar>
                </template>
                <template #[`item.link`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'link')" @save="saveItem('link')">
                        <v-list-item class="text-truncate" style="width: 200px; max-width: 100%;">{{ item.link }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="link" label="Edit Link" single-line />
                        </template>
                    </v-edit-dialog>
                </template>

                <template #[`item.name`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'name')" @save="saveItem('name')">
                        <v-list-item class="text-truncate" style="width: 200px; max-width: 100%;">{{ item.name }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="name" label="Edit Name" single-line />
                        </template>
                    </v-edit-dialog>
                </template>

                <template #[`item.folder_id`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'folder_id')" @save="saveItem('folder_id')">
                        <v-list-item>{{ item.folder && item.folder.name ? item.folder.name : '' }}</v-list-item>
                        <template #input>
                            <v-autocomplete
                                v-model="folder_id"
                                :items="folderList"
                                label="Folder"
                                item-text="name"
                                item-value="id"
                                class="mt-4"
                            >
                            </v-autocomplete>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.blocked`]="{item}">
                    <v-switch :input-value="item.blocked" @click="handleChangeFeature(item, 'blocked')" />
                </template>
                <template #[`item.feature`]="{item}">
                    <v-switch :input-value="item.feature" @click="handleChangeFeature(item, 'feature')" />
                </template>
                <template #[`item.init_vote_count`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'init_vote_count')" @save="saveInitVote()">
                        <v-list-item>{{ item.init_vote_count }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="init_vote_count" label="Edit Init Vote" single-line />
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.action`]="{item}">
                    <v-btn small outlined color="accent" :to="`/link/${item.id}`">
                        <v-icon left>mdi-eye</v-icon> View
                    </v-btn>
                    <v-btn small color="red" class="ml-2" @click="handleDeleteLink(item.id)" >
                        <v-icon>mdi-delete</v-icon>
                    </v-btn>
                </template>
            </v-data-table>
            <div class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="currentPage" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
            <CreateEnterLinkDialog :openCreateDialog="openCreateDialog" :folderList="folderList"  @close-create-dialog="handleCloseCreateDialog"/>
        </v-col>
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import CreatedUpdatedTime from '../components/CreatedUpdatedTime.vue';
import CreateEnterLinkDialog from '../components/CreateEnterLinkDialog.vue';
import Media from '../components/Media.vue';

export default {
    components: {
        CreatedUpdatedTime,
        CreateEnterLinkDialog,
        Media
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '5%' },
                { text: 'Name', value: 'name', align: 'center', width: '15%'},
                { text: 'Link', value: 'link', align: 'center', width: '20%'},
                { text: 'Click', value: 'hit', align: 'center',  width: '10%'},
                 { text: 'Blocked', value: 'blocked', align: 'center',  width: '5%'},
                { text: 'Feature', value: 'feature', align: 'center',  width: '5%'},
                { text: 'Folder', value: 'folder_id', align: 'center',  width: '10%'},
                { text: 'Init Vote', value: 'init_vote_count', align: 'center',  width: '5%'},
                { text: 'Vote', value: 'vote_count', align: 'center',  width: '5%'},
                // { text: 'Time', value: 'time', align: 'center'},
                { text: 'Action', value: 'action', align: 'center',  width: '20%', sortable: false},
            ],
            selected: [],
            loading: false,
            name: '',
            link: '',
            editItem: {},
            currentPage: 1,
            per_page: 20,
            openCreateDialog: false,
            folder_id: '',
            folderId: '',
            nameName: '',
            linkName: '',
            featureVal: '',
            init_vote_count: ''
        }
    },
    computed: {
        ...mapGetters('enterLink', {
            linkEnterLists: 'getEnterLinkList',
            pagination: 'getEnterLinkPaination',
            folderList: 'getFolderLinkList',
            // embededUrl: 'getEmbededUrl',
            enterLinkFilter: 'getEnterLinkFilter'
        }),
        folder () {
            if(this.enterLinkFilter && this.enterLinkFilter.folder && Object.keys(this.enterLinkFilter.folder).length) {
                const res = [];
                Object.keys(this.enterLinkFilter.folder).forEach(item => {
                    res.push({ id: item, name: this.enterLinkFilter.folder[item] })
                });
                return res;
            }
            return []
        },
        names () {
            if(this.enterLinkFilter && this.enterLinkFilter.name && this.enterLinkFilter.name.length) {
                return this.enterLinkFilter.name;
            }
            return []
        },
        links () {
            if(this.enterLinkFilter && this.enterLinkFilter.link && this.enterLinkFilter.link.length) {
                return this.enterLinkFilter.link;
            }
            return []
        },
        folderName () {
            let folder = 'Folder'
            if (Object.keys(this.folder).length) {
                for (let key in this.folder) {
                    if (key === this.folderId) {
                        folder = this.folder[key];
                        break;
                    }
                }
            }
            return folder;
        },

    },
    async created () {
        try {
            await Promise.all([
                this.loadLinkList(),
                this.getFolderLinkList({ per_page: 1000 }),
                this.getEnterLinkFilter()
            ]);
        } catch (err) {
            console.log(err)
        }

    },
    methods: {
        ...mapActions('enterLink', ['getEnterLinkList', 'getEnterLinkFilter', 'getFolderLinkList', 'updateEnterLink', 'createManyEnterLink', 'updateInitVoteCount', 'deleteManyLink', 'deleteLink']),
        handelOpenEditItem (item, index) {
            this.editItem = item;
            this[index] = item[index];
        },
        async saveItem (index) {
            const update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateEnterLink(update)
                .then(resp => {
                    this.loadLinkList();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });
        },
        handleChangeFeature (item, index) {
            let feature, blocked;

            if (index === 'blocked') {

                if (item.blocked === 1) {
                    blocked = 0
                    feature = item.feature;
                }
                else {
                    blocked = 1;
                    feature = 0;
                }
            }

            if (index === 'feature') {

                if (item.feature === 1) {
                    feature = 0
                    blocked = item.blocked
                }
                else {
                    feature = 1;
                    blocked = 0;
                }
            }

            const data = { ...item, feature, blocked };
            this.loading = true;
            this.updateEnterLink(data)
                .then(resp => {
                    this.loadLinkList();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });
        },
        handleChangeBlock (item) {

            if (item.feature === 1) {
                alert('Pls Unfeature before blocked this link');
                return false;
            }
            const data = { ...item, blocked: item.blocked ? 0 : 1 };

             this.updateEnterLink(data)
                .then(resp => {
                    this.loadLinkList();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.loadLinkList();
        },
        async loadLinkList () {
            try {
                this.loading = true;
                await this.getEnterLinkList({
                    page: this.currentPage,
                    per_page: this.per_page,
                    folder: this.folderId,
                    name: this.nameName,
                    link: this.linkName
                });
                this.loading = false;
            } catch (err) {
                this.loading = false;
                console.log(err)
            }
        },
        handleNewPage (currentPage) {
            this.currentPage = currentPage;
            this.loadLinkList();
        },
        handleSelectFolder () {
            // this.folderId = folder;
            this.loadLinkList();
        },
        handleSelectName () {
            // this.nameName = name;
            this.loadLinkList();
        },
        handleSelectLink (link) {
            this.linkName = link;
            this.loadLinkList();
        },
        handleClearFilter () {
            this.folderId = '';
            this.linkName = '';
            this.nameName = '';
            this.loadLinkList();
        },
        handleCloneLink () {
            if (!this.selected.length) {
                alert('Pls select at least 1 row to clone');
                return;
            }

            this.createManyEnterLink(this.selected)
                .then(resp => {
                    this.selected = [];
                    this.loadLinkList();
                })
                .catch(err => {
                    console.log(err);
                })
        },
        saveInitVote () {
            if (this.init_vote_count === '' || isNaN(this.init_vote_count)) {
                return false;
            }

            const update = { id: this.editItem.id, init_vote_count: this.init_vote_count };
            this.loading = true;
            this.updateInitVoteCount(update)
                .then(resp => {
                    this.loadLinkList();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });

        },
        handleDeleteMany () {
            let result = confirm('Are you sure to delete?');

            if (! this.selected.length) {
                alert ('Pls select at least one to delete');
                return false;
            }
            if (result) {
                const ids = this.selected.map(item => item.id);
                this.deleteManyLink({ ids })
                    .then(resp => {
                        this.loadLinkList();
                    })
                    .catch(err => {
                        console.log(err)
                    })
            }
        },
        handleDeleteLink (id) {

            let result = confirm('Are you sure to delete?');

            if (result) {
                this.deleteLink(id)
                    .then(resp => {
                        this.loadLinkList();
                    })
                    .catch(err => {
                        console.log(err)
                    })
            }
        }
    }
};
</script>

<style></style>
