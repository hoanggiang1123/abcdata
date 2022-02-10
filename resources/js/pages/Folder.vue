<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="folderList"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                :expanded.sync="expanded"
                item-key="name"
                show-expand
                :single-expand="true"
                class="elevation-1"
                show-select
                v-model="selected"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                    <v-toolbar-title>Folders</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-menu
                        offset-y
                    >
                        <template  v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                class="mr-2"
                                >
                                {{ per_page }}
                                <v-icon right>
                                    mdi-chevron-down
                                </v-icon>

                            </v-btn>
                        </template>
                        <v-list class="fix-height" color="primary">
                            <v-list-item v-for="per in perPageOpts" :key="per" @click="handleSelectPerPage(per)">
                                {{ per }}
                            </v-list-item>
                        </v-list>
                    </v-menu>
                    <div class="d-flex" style="max-width: 400px;">
                        <v-autocomplete
                                :items="descriptions"
                                v-model="descriptionName"
                                label="Description"
                                hide-details=""
                                solo
                                background-color="warning"
                                class="mr-2"
                                dense
                                clear-icon="mdi-close"
                                clearable
                                @change="handleSelectDescription"
                            >
                        </v-autocomplete>
                        <v-autocomplete
                                :items="names"
                                v-model="nameName"
                                label="Name"
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
                    <!-- <v-menu
                        offset-y
                        persistent
                    >
                        <template  v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="info"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                class="mr-2"
                                >
                                {{ descriptionName ? descriptionName : 'Description' }}
                            </v-btn>
                        </template>
                        <v-list class="fix-height">
                            <v-list-item v-for="(des, index) in descriptions" :key="index" @click="handleSelectDescription(des)">
                                {{ des }}
                            </v-list-item>
                        </v-list>
                    </v-menu> -->
                    <!-- <v-menu
                        offset-y
                    >
                        <template  v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="warning"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                class="mr-2"
                                >
                                {{ nameName ? nameName : 'Name' }}
                            </v-btn>
                        </template>
                        <v-list class="fix-height">
                            <v-list-item v-for="(n, index) in names" :key="index" @click="handleSelectName(n)">
                                {{ n }}
                            </v-list-item>
                        </v-list>
                    </v-menu> -->
                    <v-btn color="red" @click="handleClearFilter()">Clear Filter</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="red" class="mr-2" @click="handleDeleteFolders">Delete</v-btn>
                    <v-btn color="indigo" class="mr-2" @click="handleCreateFolderShortcode"><v-icon left>mdi-xml</v-icon>ShortCode</v-btn>
                    <v-btn color="primary mr-2" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create New</v-btn>
                    </v-toolbar>
                </template>
                <template v-slot:expanded-item="{ headers, item }">
                    <td :colspan="headers.length" class="pa-4">
                        <v-card>
                            <v-card-title>
                                <v-btn color="warning" outlined @click="handleEditMessage(item)">Edit Message</v-btn>
                            </v-card-title>
                            <v-card-text v-html="item.message ? item.message : 'No Content'">
                            </v-card-text>
                        </v-card>
                    </td>
                </template>
                <template #[`item.description`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'description')" @save="saveItem('description')">
                        <v-list-item>{{ item.description }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="description" label="Edit Description" single-line />
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.name`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'name')" @save="saveItem('name')">
                        <v-list-item>{{ item.name }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="name" label="Edit Name" single-line />
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.feature`]="{item}">
                    <div class="d-flex align-center justify-center">
                        <v-switch :input-value="item.feature" @click="handleChangeFeature(item)" />
                    </div>
                </template>
                <template #[`item.header_color`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'header_color')" @save="saveItem('header_color')">
                        <div class="d-flex align-center justify-center">
                            <div :style="`width: 30px; height: 30px; border-radius: 4px; background: ${ item.header_color ? item.header_color : '#FFF' }`" />
                        </div>
                        <template #input>
                            <v-text-field v-model="header_color" single-line>
                                <template v-slot:append>
                                    <v-menu top nudge-bottom="105" nudge-left="16" :close-on-content-click="false">
                                        <template v-slot:activator="{ on }">
                                            <div :style="`background: ${ header_color ? header_color : '#FFF' }; cursor: 'pointer'; height: 30px; width: 30px; border-radius: 4px;`" v-on="on" />
                                        </template>
                                        <v-card>
                                            <v-card-text class="pa-0">
                                                <v-color-picker v-model="header_color" flat mode="hexa" />
                                            </v-card-text>
                                        </v-card>
                                    </v-menu>
                                </template>
                            </v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.title_color`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'title_color')" @save="saveItem('title_color')">
                        <div class="d-flex align-center justify-center">
                            <div :style="`width: 30px; height: 30px; border-radius: 4px; background: ${ item.title_color ? item.title_color : '#FFF' }`" />
                        </div>
                        <template #input>
                            <v-text-field v-model="title_color" single-line>
                                <template v-slot:append>
                                    <v-menu top nudge-bottom="105" nudge-left="16" :close-on-content-click="false">
                                        <template v-slot:activator="{ on }">
                                            <div :style="`background: ${ title_color ? title_color : '#FFF' }; cursor: 'pointer'; height: 30px; width: 30px; border-radius: 4px;`" v-on="on" />
                                        </template>
                                        <v-card>
                                            <v-card-text class="pa-0">
                                                <v-color-picker v-model="title_color" flat mode="hexa" />
                                            </v-card-text>
                                        </v-card>
                                    </v-menu>
                                </template>
                            </v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.action`]="{item}">
                    <v-btn small outlined :to="`/folder/${item.id}`" color="pink" class="ma-2"><v-icon>mdi-eye</v-icon> View</v-btn>
                    <v-btn color="info" small class="ma-2" @click="handleCopyScript(item.script_url)">Copy SC</v-btn>
                </template>
                <!-- <template #[`item.data-table-expand`]="{ expand, isExpanded }">
                    <v-btn small outlined color="warning" @click="expand(!isExpanded)">Edit Message</v-btn>
                </template> -->
            </v-data-table>
            <div class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="currentPage" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
        </v-col>
        <CreateEnterLinkFolderDialog :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" />
        <div class="backdrop"  v-if="dialog"></div>
        <transition name="scale">
            <div class="ckeditor-modal" v-if="dialog">
                <v-card class="pa-2">
                    <v-card-text>
                        <Ckeditor v-model="message" />
                        <v-row class="mt-4">
                            <v-col>
                                <v-btn color="primary" @click="handleSaveMessage" block><v-icon left>mdi-content-save</v-icon> Save</v-btn>
                            </v-col>
                            <v-col>
                                <v-btn color="pink" @click="handleCancelEditMessage" block>Cancel</v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </div>
         </transition>
        <!-- <v-dialog
            max-width="680px"
            v-model="dialog"
            persistent
        >
            <v-card class="pa-2">
                <v-card-text>
                    <Ckeditor v-model="message" />
                    <v-row class="mt-4">
                        <v-col>
                            <v-btn color="primary" @click="handleSaveMessage" block><v-icon left>mdi-content-save</v-icon> Save</v-btn>
                        </v-col>
                        <v-col>
                            <v-btn color="pink" @click="handleCancelEditMessage" block>Cancel</v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog> -->
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import CreateEnterLinkFolderDialog from '../components/CreateEnterLinkFolderDialog.vue';
import Ckeditor from '../components/Ckeditor.vue';
export default {
    components: {
        CreateEnterLinkFolderDialog,
        Ckeditor
    },
    data () {
        return {
            selected: [],
            expanded: [],
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true },
                { text: 'Name', value: 'name', align: 'center'},
                { text: 'Description', value: 'description', align: 'center'},
                { text: 'Feature', value: 'feature', align: 'center'},
                { text: 'Header Color', value: 'header_color', align: 'center'},
                { text: 'Title Color', value: 'title_color', align: 'center'},
                { text: 'Total Vote', value: 'total_vote', align: 'center'},
                { text: 'Action', value: 'action', align: 'center', width: '20%'},
                { text: '', value: 'data-table-expand' },
            ],
            loading: false,
            description: '',
            name: '',
            header_color: '',
            title_color: '',
            openCreateDialog: false,
            descriptionName: '',
            nameName: '',
            currentPage: 1,
            per_page: 20,
            message: '',
            dialog: false,
            perPageOpts: [20, 40, 60, 80, 100, 1000],
            shortcodeUrl: 'https://link.cado.pro/api/folder/shortcode'
        }
    },
    computed: {
        ...mapGetters('enterLink', {
            folderList: 'getFolderLinkList',
            folderFilter: 'getFolderFilter',
            pagination: 'getFolderLinkPagination'
        }),
        descriptions () {
            if(this.folderFilter && this.folderFilter.description && this.folderFilter.description.length) {
                return this.folderFilter.description;
                return res;
            }
            return []
        },
        names () {
            if(this.folderFilter && this.folderFilter.name && this.folderFilter.name.length) {
                return this.folderFilter.name;
            }
            return []
        },
    },
    created () {
        this.loadLinkList()
    },

    methods: {
        ...mapActions('enterLink', ['getFolderLinkList', 'updateFolder', 'getFolderFilter', 'deleteFolder']),
        handelOpenEditItem (item, index) {
            this.editItem = item;
            this[index] = item[index];
        },
        saveItem (index) {
            const update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateFolder(update)
                .then(resp => {
                    this.loadFolder();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.getFolderLinkList();
        },
        handleCopyScript (script_url) {
            this.$clipboard(`[enterlink url=\"${script_url}\"]`);
            alert('copy success')
        },
        async loadLinkList () {
            try {
                this.loading = true;
                await Promise.all([
                    this.getFolderLinkList({ page: this.currentPage, per_page: this.per_page }),
                    this.getFolderFilter()
                ]);
                this.loading = false;
            } catch (err) {
                this.loading = false;
                console.log(err)
            }
        },
        async loadFolder () {
            try {
                this.getFolderLinkList({
                    page: this.currentPage,
                    per_page: this.per_page,
                    description: this.descriptionName,
                    name: this.nameName
                })
            } catch (err) {
                console.log(err);
            }
        },
        handleSelectDescription () {
            // this.descriptionName = des;
            this.loadFolder();
        },
        handleSelectName () {
            // this.nameName = name;
            this.loadFolder();
        },
        handleClearFilter () {
            this.descriptionName = '';
            this.nameName = '';
            this.per_page = 20;
            this.loadFolder();
        },
        handleNewPage (currentPage) {
            this.currentPage = currentPage;
            this.loadLinkList();
        },
        handleEditMessage (item) {
            this.handelOpenEditItem(item, 'message');
            this.dialog = true;
        },
        async handleSaveMessage () {
            try {

                await this.saveItem('message');
                this.handleCancelEditMessage();
            } catch (err) {
                this.handleCancelEditMessage();
                alert(err.message ? err.message : 'An Error Occur');
            }
        },
        handleCancelEditMessage () {
            this.editItem = {};
            this.message = '';
            this.dialog = false;
        },
        handleChangeFeature (item) {
            const data = { ...item, feature: item.feature ? 0 : 1 };
            this.loading = true;
            this.updateFolder(data)
                .then(resp => {
                    this.loadFolder();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });
        },
        handleSelectPerPage (per_page) {
            this.per_page = per_page;
            this.loadFolder();
        },
        handleCreateFolderShortcode () {
            let idStr = 'all';
            if (this.selected.length) {
                const idArr = [];
                this.selected.forEach((item) => {
                    idArr.push(item.id)
                })
                idStr = idArr.join(',');
            }
            const url = this.shortcodeUrl + '?ids=' + idStr;
            this.$clipboard(`[foldersc url=\"${url}\"]`);
            alert('copy success')

        },
        handleDeleteFolders () {
            let result = confirm('Are you sure to delete?');

            if (! this.selected.length) {
                alert ('Pls select at least one to delete');
                return false;
            }
            if (result) {
                const ids = this.selected.map(item => item.id);
                this.deleteFolder({ ids })
                    .then(resp => {
                        this.loadLinkList();
                    })
                    .catch(err => {
                        if (err.response && err.response.data && err.response.data.message) {
                            alert(err.response.data.message);
                        }
                    })
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
