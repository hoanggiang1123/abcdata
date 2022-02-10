<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="linkLists"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                class="elevation-1"
                v-model="selected"
                show-select
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                        <v-btn text left class="mr-2" @click="$router.push('/category')"><v-icon>mdi-arrow-left</v-icon></v-btn>
                        <v-toolbar-title>{{ category.name }} (Link)</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <!-- <v-btn @click="handleCreateEmbededCode" color="accent"><v-icon left>mdi-code-json</v-icon> Create Embeded Code</v-btn> -->
                        <div class="d-flex" style="max-width: 600px;">
                            <v-autocomplete
                                    :items="descriptions"
                                    v-model="descriptionName"
                                    label="Description"
                                    hide-details=""
                                    solo
                                    background-color="primary"
                                    class="mr-2"
                                    dense
                                    clear-icon="mdi-close"
                                    clearable
                                    @change="handleSelectDescription"
                                >
                            </v-autocomplete>
                            <v-autocomplete
                                    :items="positions"
                                    v-model="positionName"
                                    label="Position"
                                    hide-details=""
                                    solo
                                    background-color="warning"
                                    class="mr-2"
                                    dense
                                    clear-icon="mdi-close"
                                    clearable
                                    @change="handleSelectPos"
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
                                    @change="handleSelectLink"
                                >
                            </v-autocomplete>
                            <v-autocomplete
                                    :items="statuses"
                                    v-model="statusVal"
                                    label="Status"
                                    hide-details=""
                                    solo
                                    background-color="indigo"
                                    class="mr-2"
                                    dense
                                    clear-icon="mdi-close"
                                    clearable
                                    @change="handleSelectStatus"
                                >
                            </v-autocomplete>
                        </div>
                        <v-btn color="red" @click="handleClearFilter()">Clear Filter</v-btn>
                        <v-spacer></v-spacer>
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

                <template #[`item.description`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'description')" @save="saveItem('description')">
                        <v-list-item class="text-truncate" style="width: 200px; max-width: 100%;">{{ item.description }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="description" label="Edit Description" single-line />
                        </template>
                    </v-edit-dialog>
                </template>

                <template #[`item.link_directory_id`]="{item}">
                    <v-list-item style="color: gray;">{{ item.category && item.category.name ? item.category.name : '' }}</v-list-item>
                </template>
                <template #[`item.position`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'position')" @save="saveItem('position')">
                        <v-list-item>{{ item.position }}</v-list-item>
                        <template #input>
                            <v-autocomplete
                                v-model="position"
                                :items="positionList"
                                label="Position"
                                class="mt-4"
                            >
                            </v-autocomplete>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.image`]="{item}">
                    <v-dialog
                        v-if="item.image"
                        max-width="600px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn small color="accent" v-bind="attrs" v-on="on" class="ma-2">Preview</v-btn>
                        </template>
                        <v-card class="pa-4">
                            <img :src="item.image" alt="" style="max-width: 100%; display:block; margin: 0 auto;">
                        </v-card>
                    </v-dialog>
                    <v-btn color="warning" small class="ma-2" @click="handleOpenMedia(item)">Upload</v-btn>

                </template>
                <!-- <template #[`item.time`]="{item}">
                    <CreatedUpdatedTime :item="item" />
                </template> -->
                <template #[`item.status`]="{item}">
                    <v-switch :input-value="item.status" @click="handleChangeStatus(item)" />
                </template>
                <template #[`item.action`]="{item}">
                    <v-btn small outlined color="accent" :to="`/banner/${item.id}`">
                        <v-icon left>mdi-eye</v-icon> View
                    </v-btn>
                </template>
            </v-data-table>
            <v-pagination v-if="pagination.page_count > 1" v-model="currentPage" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            <CreateLinkDialog :openCreateDialog="openCreateDialog" :categoryList="categoryList" :category="category" @close-create-dialog="handleCloseCreateDialog"/>
            <Media :openMedia="openMedia" @close-media="handleCloseMedia" />
        </v-col>
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import CreatedUpdatedTime from '../components/CreatedUpdatedTime.vue';
import CreateLinkDialog from '../components/CreateLinkDialog.vue';
import Media from '../components/Media.vue'

export default {
    components: {
        CreatedUpdatedTime,
        CreateLinkDialog,
        Media
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '5%' },
                { text: 'Description', value: 'description', align: 'center', width: '15%'},
                { text: 'Link', value: 'link', align: 'center', width: '20%'},
                { text: 'Image', value: 'image', align: 'center', width: '10%'},
                { text: 'Position', value: 'position', align: 'center', width: '10%'},
                { text: 'Click', value: 'hit', align: 'center',  width: '10%'},
                { text: 'Status', value: 'status', align: 'center',  width: '10%'},
                { text: 'Category', value: 'link_directory_id', align: 'center',  width: '10%'},
                // { text: 'Time', value: 'time', align: 'center'},
                { text: 'Action', value: 'action', align: 'center',  width: '10%'},
            ],
            selected: [],
            loading: false,
            link: '',
            image: '',
            description: '',
            editItem: {},
            currentPage: 1,
            per_page: 20,
            openCreateDialog: false,
            link_directory_id: '',
            previewDialog: false,
            position: '',
            positionList: ['middle_left', 'middle_right', 'bottom_left', 'bottom_right'],
            openMedia: false,
            descriptionName: '',
            positionName: '',
            linkName: '',
            statusVal: ''
        }
    },
    computed: {
        ...mapGetters('link', {
            linkLists: 'getLinkList',
            pagination: 'getPaination',
            categoryList: 'getCategoryList',
            embededUrl: 'getEmbededUrl',
            linkFilter: 'getLinkFilter'
        }),
        category () {
            if (this.linkLists.length && this.linkLists[0] && this.linkLists[0].category) {
                return this.linkLists[0].category
            }
            return {};
        },
        descriptions () {
            if(this.linkFilter && this.linkFilter.description && this.linkFilter.description.length) {
                return this.linkFilter.description;
            }
            return []
        },
        links () {
            if(this.linkFilter && this.linkFilter.link && this.linkFilter.link.length) {
                return this.linkFilter.link;
            }
            return []
        },
        positions () {
            if(this.linkFilter && this.linkFilter.position && this.linkFilter.position.length) {
                return this.linkFilter.position;
            }
            return []
        },
        statuses () {
            if(this.linkFilter && this.linkFilter.status && this.linkFilter.status.length) {
               const statuses = [];
                Object.keys(this.linkFilter.status).forEach(item => {
                    statuses.push({ text: this.linkFilter.status[item] === 1 ? 'Active' : 'InActive', value: this.linkFilter.status[item] })
                })
                return statuses;
            }
            return []
        },
        categoryName () {
            let category = 'Category'
            if (Object.keys(this.category).length) {
                for (let key in this.category) {
                    if (key === this.categoryId) {
                        category = this.category[key];
                        break;
                    }
                }
            }
            return category;
        },
        statusName () {
            if (this.statusVal === 1) return 'Active';
            if (this.statusVal === 0) return 'Inactive';
            return 'Status';
        }
    },
    async created () {
        try {
            await Promise.all([
                this.loadLinkList(),
                this.getCategoryList(),
                this.getLinkFilter({ id: this.$route.params.id })
            ]);
        } catch (err) {
            console.log(err)
        }

    },
    methods: {
        ...mapActions('link', ['getLinkList', 'updateLink', 'getCategoryList', 'createEmbedLink', 'getLinkFilter', 'createLink', 'createManyLink']),
        handelOpenEditItem (item, index) {
            this.editItem = item;
            this[index] = item[index];
        },
        async saveItem (index) {
            const update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateLink(update)
                .then(resp => {
                    this.loadLinkList();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });
        },
        handleChangeStatus (item) {
            const data = { ...item, status: item.status ? 0 : 1 };
            this.loading = true;
            this.updateLink(data)
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
                await this.getLinkList({
                    page: this.currentPage,
                    per_page: this.per_page,
                    description: this.descriptionName,
                    link: this.linkName,
                    status: this.statusVal,
                    position: this.positionName,
                    directory: this.$route.params.id
                });
                this.loading = false;
            } catch (err) {
                this.loading = false;
                console.log(err)
            }
        },
        handleCreateEmbededCode () {
            if (!this.selected.length) {
                alert('Pls select link to create embeded code');
                return;
            }
            const ids = this.selected.map(item => item.id);
            const data = {
                link_ids: ids.join(',')
            }
            this.createEmbedLink(data)
                .then(resp => {
                    this.embededDialog = true;
                    this.code = this.generateCode(resp.script_url);
                })
                .catch(err => {
                    console.log(err);
                })
        },
        handleCloseEmbededDialog () {
            this.embededDialog = false;
            this.code = '';
        },
        generateCode (link) {
            const codeStr = "<script>\n(function(link){\n    var lscript = document.createElement('script');\n    lscript.type = 'text/javascript';\n    lscript.src = link;\n    let head = document.head || document.getElementsByTagName('head')[0];\n    head.appendChild(lscript);\n})('" + link + "');\n<\/script>";
            return codeStr;
        },
        handleOpenMedia (item) {
            this.openMedia = true;
            this.editItem = item;
        },
        handleCloseMedia (item) {
            this.openMedia = false;
            if (item) {
                this.image = item.path;
                this.saveItem('image');
            }
        },
        handleSelectDescription () {
            // this.descriptionName = des;
            this.loadLinkList();
        },
        handleSelectPos () {
            // this.positionName = pos;
            this.loadLinkList();
        },
        handleSelectLink () {
            // this.linkName = link;
            this.loadLinkList();
        },
        handleSelectStatus () {
            // this.statusVal = status;
            this.loadLinkList();
        },
        handleClearFilter () {
            this.categoryId = '';
            this.positionName = '';
            this.linkName = '';
            this.statusVal = '';
            this.descriptionName = '';
            this.loadLinkList();
        },
        handleCloneLink () {
            if (!this.selected.length) {
                alert('Pls select at least 1 row to clone');
                return;
            }

            this.createManyLink(this.selected)
                .then(resp => {
                    this.selected = [];
                    this.loadLinkList();
                })
                .catch(err => {
                    console.log(err);
                })
        },
        handleNewPage (currentPage) {
            this.currentPage = currentPage;
            this.loadLinkList();
        },
    }
}
</script>

<style>

</style>
