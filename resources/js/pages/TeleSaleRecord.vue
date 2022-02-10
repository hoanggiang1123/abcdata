<template>
    <v-row>
        <v-col cols="12">
            <v-toolbar flat color="dark">
                <v-toolbar-title>Call History</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <div class="d-flex ma-1" style="max-width: 150px;">
                    <v-autocomplete
                        :items="from"
                        v-model="filterFrom"
                        label="From"
                        hide-details=""
                        solo
                        outlined
                        dense
                        clear-icon="mdi-close"
                        clearable
                        background-color="success"
                        @change="handleSelectFilterTele"
                    >
                    </v-autocomplete>
                </div>
                <div class="d-flex ma-1" style="max-width: 150px;">
                    <v-autocomplete
                        :items="to"
                        v-model="filterTo"
                        label="To (Internal Only)"
                        hide-details=""
                        solo
                        outlined
                        dense
                        clear-icon="mdi-close"
                        clearable
                        background-color="warning"
                        @change="handleSelectFilterTele"
                    >
                    </v-autocomplete>
                </div>
                <div class="d-flex ma-1" style="max-width: 150px;">
                    <v-autocomplete
                        :items="statuses"
                        v-model="filterStatus"
                        label="Status"
                        hide-details=""
                        solo
                        outlined
                        dense
                        clear-icon="mdi-close"
                        clearable
                        background-color="purple"
                        @change="handleSelectFilterTele"
                    >
                    </v-autocomplete>
                </div>
                <div class="d-flex ma-1 mr-4" style="max-width: 150px;">
                    <v-autocomplete
                        :items="directions"
                        v-model="filterDirection"
                        label="Direction"
                        hide-details=""
                        solo
                        outlined
                        dense
                        clear-icon="mdi-close"
                        clearable
                        background-color="pink"
                        @change="handleSelectFilterTele"
                    >
                    </v-autocomplete>
                </div>
                <v-menu
                    offset-y
                    :close-on-content-click="false"
                    v-model="openFilter"
                    >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="pink"
                            dark
                            v-bind="attrs"
                            v-on="on"
                            class="mr-2"
                        >
                        Filter Date
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item v-for="filter in dateFilters" :key="filter.name">
                            <v-checkbox
                                v-model="checkboxDateFilter"
                                :label="filter.name"
                                :value="filter"
                                hide-details
                                >
                            </v-checkbox>
                        </v-list-item>
                        <v-list-item v-if="customRange">
                            <v-list-item-content>
                                <div class="d-flex align-center">
                                    <div class="flex-1 mr-2">
                                        <v-menu
                                            ref="menu1"
                                            v-model="menuFrom"
                                            :close-on-content-click="false"
                                            transition="scale-transition"
                                            offset-y
                                            max-width="290px"
                                            min-width="auto"
                                            >
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field
                                                v-model="dateFromFormat"
                                                label="From"
                                                v-bind="attrs"
                                                v-on="on"
                                                @blur="date = parseDate(dateFromFormat)"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                v-model="dateFrom"
                                                no-title
                                                @input="menuFrom = false"
                                            ></v-date-picker>
                                        </v-menu>
                                    </div>
                                    <div class="flex-1 ml-2">
                                        <v-menu
                                            ref="menu1"
                                            v-model="menuTo"
                                            :close-on-content-click="false"
                                            transition="scale-transition"
                                            offset-y
                                            max-width="290px"
                                            min-width="auto"
                                            >
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field
                                                v-model="dateToFormat"
                                                label="To"
                                                v-bind="attrs"
                                                v-on="on"
                                                @blur="date = parseDate(dateToFormat)"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                v-model="dateTo"
                                                no-title
                                                @input="menuTo = false"
                                            ></v-date-picker>
                                        </v-menu>
                                    </div>
                                </div>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item>
                            <v-list-item-content>
                                <div class="d-flex align-center">
                                    <div class="flex-1 mr-2">
                                        <v-btn @click="handleSelectFilter" color="pink" block>Show</v-btn>
                                    </div>
                                    <div class="flex-1 mr-2">
                                        <v-btn color="warning" block @click="openFilter = false">Cancel</v-btn>
                                    </div>
                                </div>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-toolbar>
        </v-col>
        <v-col cols="3">
            <v-card>
                <v-card-text>
                    <v-list-item>
                        <v-list-item-action>
                            <v-btn fab small color="primary"><v-icon>mdi-phone</v-icon></v-btn>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Total Call</v-list-item-title>
                            <v-list-item-subtitle>{{ statistic_call.total_call }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="3">
            <v-card>
                <v-card-text>
                    <v-list-item>
                        <v-list-item-action>
                            <v-btn fab small color="warning"><v-icon>mdi-clock-time-three-outline</v-icon></v-btn>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Avg. call duration</v-list-item-title>
                            <v-list-item-subtitle>{{ statistic_call.average_call | toMinutes }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="3">
            <v-card>
                <v-card-text>
                    <v-list-item>
                        <v-list-item-action>
                            <v-btn fab small color="success"><v-icon>mdi-phone-outgoing</v-icon></v-btn>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Total Outbound Call</v-list-item-title>
                            <v-list-item-subtitle>{{ statistic_call.total_outbound }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="3">
            <v-card>
                <v-card-text>
                    <v-list-item>
                        <v-list-item-action>
                            <v-btn fab small color="indigo"><v-icon>mdi-phone-return</v-icon></v-btn>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Total Inbound Call</v-list-item-title>
                            <v-list-item-subtitle>{{ statistic_call.total_inbound }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12">
            <v-card>
                <v-card-title>
                    Chart
                </v-card-title>
                <v-card-text>
                    <BarChart v-if="barChartData" :chartData="barChartData" :options="optionChartBar" />
                </v-card-text>
            </v-card>
        </v-col>
        <v-col cols="12">
            <v-data-table
                v-model="selected"
                :headers="headers"
                :items="records"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                show-select
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-toolbar flat color="dark">
                        <v-toolbar-title>Call Record</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-btn color="red" class="mr-2" @click="handleDeleteMany" :disabled="!selected.length">Delete</v-btn>
                    </v-toolbar>
                </template>
                <template #[`item.remark`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'remark')"
                        @save="handleSaveItem('remark')"
                    >
                        <v-list-item>{{ item.name }}</v-list-item>
                        <template #input>
                            <v-text-field label="Remark" v-model="remark"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.duration`]="{item}">
                    <v-list-item>{{ item.duration | toMinutes }}</v-list-item>
                </template>
                <template #[`item.recording_url`]="{item}">
                    <audio v-if="item.recording_url" controls style="height: 30px;" preload="none">
                        <source :src="item.recording_url" type="audio/mpeg">
                    </audio>
                    <v-list-item v-else>No Audio</v-list-item>
                </template>
                <template #[`item.action`]="{item}">
                    <v-btn color="red" small class="ma-1" @click="handleDeleteRecord(item.id)"><v-icon>mdi-delete</v-icon></v-btn>
                </template>
            </v-data-table>
            <div class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="currentPage" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
        </v-col>
     </v-row>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import BarChart from '../components/BarChart.vue'
export default {
    components: {
        BarChart
    },
    data (vm) {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center'},
                { text: 'From', value: 'from', align: 'center'},
                { text: 'To', value: 'to', align: 'center'},
                { text: 'Call Id', value: 'call_number', align: 'center'},
                { text: 'Duration', value: 'duration', align: 'center'},
                { text: 'Start time', value: 'start_time', align: 'center'},
                { text: 'End time', value: 'end_time', align: 'center'},
                { text: 'Direction', value: 'direction', align: 'center'},
                { text: 'Status', value: 'status', align: 'center'},
                { text: 'Recording Audio', value: 'recording_url', align: 'center'},
                { text: 'Remark', value: 'remark', align: 'center'},
                { text: 'Action', value: 'action', align: 'center'}
            ],
            selected: [],
            loading: false,
            editItem: {},
            remark: '',
            currentPage: 1,
            per_page: 20,
            filterFrom: '',
            filterTo: '',
            filterDirection: '',
            filterStatus: '',
            filterRemark: '',
            optionChartBar: { responsive: true, maintainAspectRatio: false },
            barChartData: null,
            dateFilters: [
                { name: 'Today', value: 0, param: 'day' },
                { name: 'Yesterday', value: 1, param: 'day' },
                { name: 'Last 2 days', value: 2, param: 'day' },
                { name: 'Last 30 days', value: 30, param: 'day' },
                { name: 'Last month', value: 1, param: 'month' },
                { name: 'Last 3 months', value: 3, param: 'month' },
                { name: 'Custom range', value: null, param: null },
            ],
            menuFrom: false,
            dateFromFormat: vm.formatDate((new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)),
            dateFrom: '',
            menuTo: false,
            dateToFormat: vm.formatDate((new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)),
            dateTo: '',
            openFilter: false,
            customRange: false,
            checkboxDateFilter: { name: 'Last 30 days', value: 30, param: 'day' }
        }
    },
    computed: {
        ...mapGetters('teleSale', {
            records: 'getTeleSaleRecord',
            pagination: 'getTeleSaleRecordPagination',
            statistic_call: 'getTeleSaleRecordStatisticCall',
            // statistic_chart: 'getTeleSaleRecordStatisticChart',
            filter: 'getTeleSaleRecordFilter'
        }),
        from () {
            if (this.filter.from && this.filter.from.length) {
                return this.filter.from;
            }
            return [];
        },
        to () {
            if (this.filter.to && this.filter.to.length) {
                return this.filter.to;
            }
            return [];
        },
        statuses () {
            if (this.filter.status && this.filter.status.length) {
                return this.filter.status;
            }
            return [];
        },
        directions () {
            if (this.filter.direction && this.filter.direction.length) {
                return this.filter.direction;
            }
            return [];
        },

    },
    watch: {
        dateFrom (val) {
            this.dateFromFormat = this.formatDate(this.dateFrom)
        },
        dateTo (val) {
            this.dateToFormat = this.formatDate(this.dateTo)
        },
        checkboxDateFilter (val) {
            if (val && val.value === null && val.param === null) {
                this.customRange = true
            } else {
                this.customRange = false
            }
        }
    },
    filters: {
        toMinutes (mins) {
            let h = Math.floor(mins / 60);
            let m = mins % 60;
            h = h < 10 ? '0' + h : h; // (or alternatively) h = String(h).padStart(2, '0')
            m = m < 10 ? '0' + m : m; // (or alternatively) m = String(m).padStart(2, '0')
            return `${h}:${m}`;
        }
    },
    created () {
        this.loadPageData();
    },
    methods: {
        ...mapActions('teleSale', ['getTeleSaleRecord', 'updateTeleSaleRecord', 'deleteTeleSaleRecord', 'getTeleSaleRecordStatistic', 'getTeleSaleRecordFilter']),
        async loadPageData () {
            this.loading = true;
            try {
                const params = {
                    from: this.filterFrom,
                    to: this.filterTo,
                    direction: this.filterDirection,
                    status: this.filterStatus,
                    remark: this.filterRemark,
                    per_page: this.per_page,
                    page: this.currentPage,
                };

                if (Object.keys(this.checkboxDateFilter).length) {
                    if (!this.checkboxDateFilter.param && !this.checkboxDateFilter.value) {
                        if (this.dateFrom && this.dateTo) {
                            params.from_date = this.dateFrom;
                            params.to_date = this.dateTo;
                        } else {
                            alert('Pls Select Date')
                            return false;
                        }
                    }
                    else {
                        params[this.checkboxDateFilter.param] = this.checkboxDateFilter.value
                    }
                }

                const resp = await Promise.all([this.getTeleSaleRecord(params), this.getTeleSaleRecordStatistic(params), this.getTeleSaleRecordFilter()])
                this.barChartData = resp[1].statistic_chart ? resp[1].statistic_chart : null;
                this.loading = false;
            } catch (err) {
                console.log(err);
                this.loading = false;
            }
        },
        async loadPageSingle () {

            try {
                const params = {
                    from: this.filterFrom,
                    to: this.filterTo,
                    direction: this.filterDirection,
                    status: this.filterStatus,
                    remark: this.filterRemark,
                    per_page: this.per_page,
                    page: this.currentPage,
                };
                if (Object.keys(this.checkboxDateFilter).length) {
                    if (!this.checkboxDateFilter.param && !this.checkboxDateFilter.value) {
                        if (this.dateFrom && this.dateTo) {
                            params.from_date = this.dateFrom;
                            params.to_date = this.dateTo;
                        } else {
                            alert('Pls Select Date')
                            return false;
                        }
                    }
                    else {
                        params[this.checkboxDateFilter.param] = this.checkboxDateFilter.value
                    }
                }
                this.loading = true;
                await this.getTeleSaleRecord(params)
                this.loading = false;
            } catch (err) {
                console.log(err);
                this.loading = false;
            }
        },
        async loadPage () {
            try {
                const params = {
                    from: this.filterFrom,
                    to: this.filterTo,
                    direction: this.filterDirection,
                    status: this.filterStatus,
                    remark: this.filterRemark,
                    per_page: this.per_page
                };
                if (Object.keys(this.checkboxDateFilter).length) {
                    if (!this.checkboxDateFilter.param && !this.checkboxDateFilter.value) {
                        if (this.dateFrom && this.dateTo) {
                            params.from_date = this.dateFrom;
                            params.to_date = this.dateTo;
                        } else {
                            alert('Pls Select Date')
                            return false;
                        }
                    }
                    else {
                        params[this.checkboxDateFilter.param] = this.checkboxDateFilter.value
                    }
                }
                this.loading = true;
                const resp = await Promise.all([this.getTeleSaleRecord(params), this.getTeleSaleRecordStatistic(params)]);
                this.barChartData = resp[1].statistic_chart ? resp[1].statistic_chart : null;
                this.loading = false;
            } catch (err) {
                console.log(err);
                this.loading = false;
            }
        },
        handleSearchByField () {
            if (this.fieldVal) {
                this[this.fieldName] = this.fieldVal;
                this.loadPage();
            }
        },
        handleDeleteMany () {
            const deleteConfirm = confirm('Are you sure want to delete, it might affect to tele sale data ?');

            if (deleteConfirm) {
                const ids = this.selected.map(item => item.id);
                this.loading = true;
                this.deleteTeleSaleRecord({ ids })
                .then(resp => {
                    this.loadPage()
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
            this.updateTeleSaleRecord(update)
                .then(resp => {
                    this.resetPageData();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                });
        },
        handleDeleteRecord (id) {
             const deleteConfirm = confirm('Are you sure want to delete, it might affect to tele sale data ?');

            if (deleteConfirm) {
                this.loading = true;
                this.deleteTeleSaleRecord({ ids: [id] })
                .then(resp => {
                    this.loadPage()
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
            this.loadPage();
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.loadPage();
        },
        handleNewPage (currentPage) {
            this.currentPage = currentPage;
            this.loadPageSingle();
        },
        handleSelectFilterTele (value) {
            this.loadPage();
        },
        parseDate (date) {
            if (!date) return null

            const [month, day, year] = date.split('/')
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
        },
        formatDate (date) {
            if (!date) return null

            const [year, month, day] = date.split('-')
            return `${month}/${day}/${year}`
        },
        handleSelectFilter () {
            this.loadPage();
        }
    }
}
</script>

<style>

</style>
