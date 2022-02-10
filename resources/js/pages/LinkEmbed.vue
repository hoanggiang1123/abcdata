<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                :headers="headers"
                :items="activeEmbed === 1 ? linkEmbed : linkEmbedTrash"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                class="elevation-1"
            >
                 <template #[`top`]>
                    <v-toolbar flat color="dark">
                    <v-toolbar-title>Link Embeded</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-btn color="primary" @click="activeEmbed = 1">All {{ linkEmbed.length ? `(${linkEmbed.length})` : '' }}</v-btn>
                    <v-btn color="warning" class="ml-4" @click="activeEmbed = 2"> <v-icon left>mdi-delete</v-icon> Trash {{ linkEmbedTrash.length ? `(${linkEmbedTrash.length})` : '' }}</v-btn>
                    </v-toolbar>
                </template>
                <template #[`item.name`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'name')" @save="saveItem('name')">

                        <template v-if="item.name">
                            <v-list-item>{{ item.name }}</v-list-item>
                        </template>
                        <template v-else>
                            <v-btn color="pink" small>Add Name</v-btn>
                        </template>
                        <template #input>
                            <v-text-field v-model="name" label="Edit Script Name" single-line />
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.action`]="{item}">
                    <template v-if="activeEmbed === 1">
                        <v-btn color="error" small @click="handleDeleteItem(item.id)"><v-icon left>mdi-delete</v-icon> Delete</v-btn>
                    </template>
                    <template v-else>
                        <div class="d-flex flex-column">
                            <v-btn color="success" class="ma-2"  @click="handleRestoreItem(item.id)"><v-icon left>mdi-recycle</v-icon> Restore</v-btn>
                            <v-btn color="error" class="ma-2"  @click="handleDeleteItemPermanent(item.id)"><v-icon left>mdi-delete</v-icon> Delete permanent</v-btn>
                        </div>
                    </template>
                </template>
                <template #[`item.link_ids`]="{item}">
                    <v-edit-dialog :return-value.sync="item.id" @open="handelOpenEditItem(item, 'link_ids')" @save="saveItem('link_ids')">
                        <LinkTo :ids="item.link_ids" />
                        <v-btn color="warning">Edit Link</v-btn>
                        <template #input>
                            <v-text-field v-model="link_ids" label="Edit Link Images" single-line />
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.script_url`]="{item}">
                    <LinkEmbedScript :script_url="item.script_url" />
                </template>

            </v-data-table>
        </v-col>
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import LinkTo from '../components/LinkTo.vue';
import LinkEmbedScript from '../components/LinkEmbedScript.vue';
import 'codemirror/lib/codemirror.css'
export default {
    components: {
        LinkEmbedScript,
        LinkTo
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '10%' },
                { text: 'Name', value: 'name', align: 'center', sortable: true,  width: '20%' },
                { text: 'Redirect Link', value: 'link_ids', align: 'center', sortable: true,  width: '20%' },
                { text: 'Script', value: 'script_url', sortable: true,  width: '40%' },
                { text: 'Actions', value: 'action', align: 'center', sortable: true,  width: '10%' },
            ],
            loading: false,
            name: '',
            link_ids: '',
            editItem: {},
            expanded: [],
            activeEmbed: 1
        }
    },
    computed: {
        ...mapGetters('link', {
            linkEmbed: 'getLinkEmbedList',
            linkEmbedTrash: 'getLinkEmbedListTrash'
        })
    },
    async created () {
        this.loadEmbedLinkData()
    },
    methods: {
        ...mapActions('link', ['getLinkEmbedList', 'updateLinkEmbed', 'deleteLinkEmbed', 'getLinkEmbedListTrash', 'restoreEmbedLink', 'deleteLinkEmbedPermanent']),
        handelOpenEditItem (item, index) {
            this.editItem = item;
            this[index] = item[index];
        },
        async saveItem (index) {
            const update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateLinkEmbed(update)
                .then(resp => {
                    this.loadEmbedLinkData();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err)
                });
        },
        handleDeleteItem (id) {
            const result = confirm('Are you sure to delete?');
            if (result) {
                this.loading = true;
                this.deleteLinkEmbed(id)
                    .then(resp => {
                        this.loadEmbedLinkData();
                        this.loading = false;
                    })
                    .catch (err => {
                        this.loading = false;
                        console.log(err);
                    })
            }
        },
        async loadEmbedLinkData () {
            try {
                await Promise.all([
                    this.getLinkEmbedList(),
                    this.getLinkEmbedListTrash()
                ]);
            } catch (err) {
                console.log(err)
            }
        },
        handleRestoreItem (id) {
            this.restoreEmbedLink(id)
                .then(resp => {
                    this.loadEmbedLinkData();
                })
                .catch(err => {
                    console.log(err)
                });
        },
        handleDeleteItemPermanent (id) {
            this.deleteLinkEmbedPermanent(id)
                .then(resp => {
                    this.loadEmbedLinkData();
                })
                .catch(err => {
                    console.log(err)
                });
        }
    }
}
</script>

<style>

</style>
