<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="categoryList"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                    <v-toolbar-title>Categories</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <div class="d-flex" style="max-width: 400px;">
                        <v-autocomplete
                                :items="descriptions"
                                v-model="descriptionName"
                                label="Description"
                                hide-details=""
                                solo
                                background-color="purple"
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
                                background-color="warning"
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
                    <v-btn color="primary mr-2" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create New</v-btn>
                    </v-toolbar>
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
                 <template #[`item.action`]="{item}">
                    <v-btn small outlined :to="`/category/${item.id}`" color="pink" class="ma-2"><v-icon>mdi-eye</v-icon> View</v-btn>
                    <v-btn color="info" small class="ma-2" @click="handleCopyScript(item.script_url)">Copy Script</v-btn>
                    <v-btn color="red" small class="ma-2" @click="handleDestroyDirectory(item.id)">Delete</v-btn>
                </template>
            </v-data-table>
            <div class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="currentPage" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
        </v-col>
        <CreateLinkDirectoryDialog :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" />
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import CreateLinkDirectoryDialog from '../components/CreateLinkDirectoryDialog.vue';
export default {
    components: {
        CreateLinkDirectoryDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true },
                { text: 'Name', value: 'name', align: 'center'},
                { text: 'Description', value: 'description', align: 'center'},
                // { text: 'Time', value: 'time', align: 'center'},
                { text: 'Action', value: 'action', align: 'center', width: '30%'},
            ],
            loading: false,
            description: '',
            name: '',
            openCreateDialog: false,
            descriptionName: '',
            nameName: '',
            currentPage: 1
        }
    },
    computed: {
        ...mapGetters('link', {
            categoryList: 'getCategoryList',
            categoryFilter: 'getCategoryFilter',
            pagination: 'getCategoryPagination'
        }),
        descriptions () {
            if(this.categoryFilter && this.categoryFilter.description && this.categoryFilter.description.length) {
                return this.categoryFilter.description;
            }
            return []
        },
        names () {
            if(this.categoryFilter && this.categoryFilter.name && this.categoryFilter.name.length) {
                return this.categoryFilter.name;
            }
            return []
        },
    },
    created () {
        this.loadLinkList()
    },

    methods: {
        ...mapActions('link', ['getCategoryList', 'updateCategory', 'getCategoryFilter', 'deleteLinkDirectoryBanner']),
        handelOpenEditItem (item, index) {
            this.editItem = item;
            this[index] = item[index];
        },
        async saveItem (index) {
            const update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateCategory(update)
                .then(resp => {
                    this.getCategoryList();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.getCategoryList();
        },
        handleCopyScript (script_url) {
            this.$clipboard("<script>\n(function(link){\n    var lscript = document.createElement('script');\n    lscript.type = 'text/javascript';\n    lscript.src = link;\n    let head = document.head || document.getElementsByTagName('head')[0];\n    head.appendChild(lscript);\n})('" + script_url + "');\n<\/script>");
            alert('copy success')
        },
        async loadLinkList () {
            try {
                this.loading = true;
                await Promise.all([
                    this.getCategoryList({ page: this.currentPage }),
                    this.getCategoryFilter()
                ]);
                this.loading = false;
            } catch (err) {
                this.loading = false;
                console.log(err)
            }
        },
        async loadCategory () {
            try {
                this.getCategoryList({
                    per_page: this.per_page,
                    description: this.descriptionName,
                    name: this.nameName
                })
            } catch (err) {
                console.log(err);
            }
        },
        handleSelectDescription () {
            //this.descriptionName = des;
            this.loadCategory();
        },
        handleSelectName () {
            // this.nameName = name;
            this.loadCategory();
        },
        handleClearFilter () {
            this.descriptionName = '';
            this.nameName = '';
            this.loadCategory();
        },
        handleNewPage (currentPage) {
            this.currentPage = currentPage;
            this.loadLinkList();
        },
        async handleDestroyDirectory (id) {
            try {
                const confirmD = confirm('Are you sure to delete');

                if (confirmD) {
                    await this.deleteLinkDirectoryBanner({ id });
                    this.loadLinkList();
                }
            } catch (err) {
                console.log(err)
            }
        }
    }
}
</script>

<style>

</style>
