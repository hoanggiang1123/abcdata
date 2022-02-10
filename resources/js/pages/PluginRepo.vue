<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="plugins"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                        <v-toolbar-title>Plugin Repository</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-btn color="primary" @click="openCreateDialog = true"><v-icon>mdi-plus</v-icon>Create Plugin</v-btn>
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
                            <v-text-field v-model="name" label="Edit Name"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.author`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'author')"
                        @save="handleSaveItem('author')"
                    >
                        <v-list-item>{{ item.author }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="author" label="Edit author"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.author_profile`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'author_profile')"
                        @save="handleSaveItem('author_profile')"
                    >
                        <v-list-item>{{ item.author_profile }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="author_profile" label="Edit Author Profile"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.version`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'version')"
                        @save="handleSaveItem('version')"
                    >
                        <v-list-item>{{ item.version }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="version" label="Edit Version"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.requires`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'requires')"
                        @save="handleSaveItem('requires')"
                    >
                        <v-list-item>{{ item.requires }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="requires" label="Edit Requires"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.tested`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'tested')"
                        @save="handleSaveItem('tested')"
                    >
                        <v-list-item>{{ item.tested }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="tested" label="Edit tested"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.requires_php`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'requires_php')"
                        @save="handleSaveItem('requires_php')"
                    >
                        <v-list-item>{{ item.requires_php }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="requires_php" label="Edit Require Php"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.slug`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'slug')"
                        @save="handleSaveItem('slug')"
                    >
                        <v-list-item>{{ item.slug }}</v-list-item>
                        <template #input>
                            <v-text-field v-model="slug" label="Edit Slug"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.download_url`]="{item}">
                    <v-btn color="warning" :href="item.download_url + '?ver=' + item.version" class="ma-2" small>Download</v-btn>
                    <v-btn color="pink" small class="ma-2" @click="handleClickUpload(item)"><v-icon left >mdi-cloud-upload</v-icon> Upload</v-btn>
                </template>
            </v-data-table>
        </v-col>
        <input type="file" hidden ref="pluginFile" @change="handleUploadPlugin" />
        <CreatePluginRepoDialog :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" />
    </v-row>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import CreatePluginRepoDialog from '../components/CreatePluginRepoDialog.vue'
export default {
    components: {
        CreatePluginRepoDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '5%' },
                { text: 'Name', value: 'name', align: 'center', width: '10%'},
                { text: 'Slug', value: 'slug', align: 'center', width: '10%'},
                { text: 'Author', value: 'author', align: 'center', width: '10%', sortable: false},
                { text: 'Author Profile', value: 'author_profile', align: 'center', width: '10%', sortable: false},
                { text: 'Version', value: 'version', align: 'center', width: '10%', sortable: false},
                { text: 'Require', value: 'requires', align: 'center',  width: '10%', sortable: false},
                { text: 'Tested', value: 'tested', align: 'center',  width: '10%', sortable: false},
                { text: 'Php Ver', value: 'requires_php', align: 'center',  width: '10%', sortable: false},
                { text: 'Download', value: 'download_url', align: 'center',  width: '15%', sortable: false},
            ],
            loading: false,
            name: '',
            slug: '',
            author: '',
            author_profile: '',
            version: '',
            download_url: '',
            requires: '',
            tested: '',
            requires_php: '',
            sections: '',
            banners: '',
            openCreateDialog: false,
            edititem: {}
        }
    },
    computed: {
        ...mapGetters('pluginRepo', {
            plugins: 'getPluginRepo'
        })
    },
    created () {
        this.loadPageData()
    },
    methods: {
        ...mapActions('pluginRepo', ['getPluginRepo', 'uploadPluginFile', 'updatePluginRepo']),
        async loadPageData () {
            try {
                await Promise.all([
                    this.getPluginRepo()
                ])
            } catch (err) {
                console.log(err);
            }
        },
        handleCloseCreateDialog (value) {
            this.openCreateDialog = false;
            if (value) this.loadPageData();
        },
        handleUploadPlugin () {
            const plugin = this.$refs.pluginFile.files[0];
            const testzip = ((plugin.name).split('.')).pop();
            if (testzip !== 'zip') {
                alert('Only Zip file allowed');
                return false;
            }
            const data = new FormData();
            data.append('plugin', plugin);

            this.uploadPluginFile(data)
                .then (resp => {
                    this.download_url = resp;
                    this.handleSaveItem('download_url');
                })
                .catch(err => {
                    console.log(err)
                })
        },
        handleClickUpload (item) {
            this.editItem = item;
            this.$refs.pluginFile.click();
        },
        handelOpenEditItem (item, index) {
            this.editItem = item;
            this[index] = item[index];
        },
        handleSaveItem (index) {
            const update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updatePluginRepo(update)
                .then(resp => {
                    this.loadPageData();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });
        },
    }
}
</script>

<style>

</style>
