<template>
     <v-row>
        <v-col cols="12">
            <v-data-table
                v-model="selected"
                :headers="headers"
                :items="leagues"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                show-select
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                    <v-toolbar-title>Leagues</v-toolbar-title>
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
                    <v-btn color="warning" class="ma-1" @click="getShortCodeAll()" title="short code all tools">SC ALL</v-btn>
                    <v-btn color="purple" class="ma-1" @click="getShortCodeAll('ranking')" title="short code for all football ranking">Sc Ranking</v-btn>
                    <v-btn color="pink" class="ma-1" @click="getShortCodeAll('schedule')" title="short code for all football schedule">SC Schedule</v-btn>
                    <v-btn color="indigo" class="ma-1" @click="getShortCodeAll('odd')" title="short code for all football odd">Sc Odd</v-btn>
                    <v-btn color="red" class="ma-1" @click="getShortCodeAll('result')" title="short code for all football result">SC Result</v-btn>
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
                <template #[`item.slug`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.slug"
                        @open="handelOpenEditItem(item, 'slug')"
                        @save="handleSaveItem('slug')"
                    >
                        <v-list-item>{{ item.slug }}</v-list-item>
                        <template #input>
                            <v-text-field label="Slug" v-model="slug"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.ranking`]="{item}">
                    <v-btn small color="warning" @click="getShortCode('ranking', item.slug)">Ranking Sc</v-btn>
                </template>
                <template #[`item.schedule`]="{item}">
                    <v-btn small color="primary" @click="getShortCode('schedule', item.slug)">Schedule Sc</v-btn>
                </template>
                <template #[`item.result`]="{item}">
                    <v-btn small color="pink" @click="getShortCode('result', item.slug)">Result Sc</v-btn>
                </template>
                <template #[`item.action`]="{item}">
                    <v-btn color="red" small class="ma-1" @click="handleDeleteLeague(item.id)"><v-icon>mdi-delete</v-icon></v-btn>
                </template>
            </v-data-table>
            <div class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="currentPage" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
        </v-col>
        <CreateFbLeagueDialog :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" />
     </v-row>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import CreateFbLeagueDialog from '../components/CreateFbLeagueDialog';
export default {
    components: {
        CreateFbLeagueDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center'},
                { text: 'Name', value: 'name', align: 'center', width: '15%'},
                { text: 'Slug', value: 'slug', align: 'center', width: '15%'},
                { text: 'Description', value: 'description', align: 'center'},
                { text: 'Ranking', value: 'ranking', align: 'center'},
                { text: 'Schedule', value: 'schedule', align: 'center'},
                { text: 'Result', value: 'result', align: 'center'},
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
                }
            ],
            fieldName: '',
            fieldVal: '',
            editItem: {},
            name: '',
            description: '',
            slug: '',
            currentPage: 1,
            per_page: 30,
            openCreateDialog: false
        }
    },
    computed: {
        ...mapGetters('fbStat', {
            leagues: 'getLeagues',
            pagination: 'getLeaguesPagination'
        })
    },
    created () {
        this.loadPageData();
    },
    methods: {
        ...mapActions('fbStat', ['getLeagues', 'updateLeague', 'deleteLeagues']),
        async loadPageData () {
            try {
                await this.getLeagues({
                    name: this.name,
                    description: this.description,
                    page: this.currentPage,
                    per_page: this.per_page
                })
            } catch (err) {
                console.log(err)
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
                this.deleteLeagues({ ids })
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
            this[index] = item[index];

        },
        handleSaveItem (index) {
            let update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateLeague(update)
                .then(resp => {
                    this.resetPageData();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                });
        },
        handleGoToTeleSale (id) {
            this.$router.push('/data?line_id=' + id);
        },
        handleDeleteLeague (id) {
             const deleteConfirm = confirm('Are you sure want to delete, it might affect to tele sale data ?');

            if (deleteConfirm) {
                this.loading = true;
                this.deleteLeagues({ ids: [id] })
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
            this.slug = '',
            this.description = '',
            this.fieldName = '';
            this.fieldVal = '';
            this.loadPageData();
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.loadPageData();
        },
        handleNewPage (currentPage) {
            this.currentPage = currentPage;
            this.loadPageData();
        },
        getShortCode (type, slug) {
            const scArr = { ranking: 'fbstats-bxh', schedule: 'fbstats-schedule', result: 'fbstats-result' };
            const sc = scArr[type];

            this.$clipboard('['+ sc +' league="' + slug + '"]');
            alert('copy success')
        },
        getShortCodeAll (type = 'all') {

            const scArr = { all: 'fbstats', ranking: 'fbstats-bxh', odd: 'fbstats-odd', schedule: 'fbstats-schedule', result: 'fbstats-result' };

            const sc = scArr[type];

            this.$clipboard('[' + sc + ']');
            alert('copy success')
        }
    }
}
</script>

<style>

</style>
