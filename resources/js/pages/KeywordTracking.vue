<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12">
                <v-toolbar>
                    <v-toolbar-title>
                        Keyword Chart
                    </v-toolbar-title>
                    <v-divider vertical class="mx-4" inset></v-divider>
                    <v-menu
                        offset-y
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                >
                                {{ currentOpt.name }}
                                <v-icon right>
                                    mdi-chevron-down
                                </v-icon>
                            </v-btn>
                        </template>
                        <v-list class="fix-height" color="primary">
                            <v-list-item v-for="(opt, index) in dropItems" :key="index" @click="handleSelectOption(opt)">
                                {{ opt.name }}
                            </v-list-item>
                        </v-list>
                    </v-menu>

                    <v-menu
                        offset-y
                        :close-on-content-click="false"

                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="success"
                                dark
                                v-bind="attrs"
                                v-on="on"
                                class="ml-4"
                                >
                                {{ dateRange }}
                                <v-icon right>
                                    mdi-chevron-down
                                </v-icon>
                            </v-btn>
                        </template>
                         <v-date-picker
                            v-model="dates"
                            range
                        ></v-date-picker>
                    </v-menu>
                    <v-btn color="success" class="ml-2" @click="handleLoadLinkHitByDateRange">
                        Go
                    </v-btn>
                </v-toolbar>
            </v-col>
            <v-col cols="8">
                <v-card>
                    <v-card-title>
                        Top Keyword
                    </v-card-title>
                    <v-card-text>
                        <LineChart :chart-data="clickChartDataset" :options="optionChartBar" />
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="4">
                <v-card>
                    <v-card-title>
                        Most Use Device
                    </v-card-title>
                    <v-card-text>
                        <PieChart :chart-data="pieChartDatasetDevice" :options="optionChartPie" />
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12">
                <v-data-table
                        :headers="headers"
                        :items="listTracking"
                        :hide-default-footer="true"
                        disable-pagination
                        :loading="loading"
                        class="elevation-1"
                    >
                    <template #[`top`]>
                        <v-toolbar>
                            <v-toolbar-title>Link Hit</v-toolbar-title>
                            <v-divider class="mx-4" inset vertical></v-divider>
                            <!-- <v-btn @click="handleCreateEmbededCode" color="accent"><v-icon left>mdi-code-json</v-icon> Create Embeded Code</v-btn> -->
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
                            <v-menu
                                offset-y
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="primary"
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        class="mr-4"
                                        >
                                        {{ currentOptLink.name ? currentOptLink.name : 'Time Range' }}
                                        <v-icon right>
                                            mdi-chevron-down
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <v-list class="fix-height" color="primary">
                                    <v-list-item v-for="(opt, index) in dropItemLinks" :key="index" @click="handleSelectTimeRangeOpt(opt)">
                                        {{ opt.name }}
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                            <v-menu
                                offset-y
                            >
                                <template  v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="success"
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        class="mr-2"
                                        >
                                        {{ browserName ? browserName : 'Browser' }}
                                        <v-icon right>
                                            mdi-chevron-down
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <v-list class="fix-height" color="success">
                                    <v-list-item v-for="b in browser" :key="b" @click="handleSelectBrowser(b)">
                                        {{ b }}
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                            <v-menu
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
                                        {{ deviceName ? deviceName : 'Device' }}
                                        <v-icon right>
                                            mdi-chevron-down
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <v-list class="fix-height" color="warning">
                                    <v-list-item v-for="de in device" :key="de" @click="handleSelectDevice(de)">
                                        {{ de }}
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                            <v-menu
                                offset-y
                            >
                                <template  v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="info"
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        class="mr-2"
                                        >
                                        {{ osName ? osName : 'Os' }}
                                        <v-icon right>
                                            mdi-chevron-down
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <v-list  class="fix-height" color="info">
                                    <v-list-item v-for="os in os_platform" :key="os" @click="handleSelectOs(os)">
                                        {{ os }}
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                            <v-menu
                                offset-y
                            >
                                <template  v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="purple"
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        class="mr-2"
                                        >
                                        {{ keywordName ? keywordName: 'Keyword' }}
                                        <v-icon right>
                                            mdi-chevron-down
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <v-list class="fix-height" color="purple">
                                    <v-list-item v-for="keyw in keyword" :key="keyw" @click="handleSelectKeyword(keyw)">
                                        {{ keyw }}
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                            <v-text-field
                                outlined label="Referal"
                                dense hide-details=""
                                v-model="referalName"
                                style="max-width: 250px;"
                                class="mr-4"
                                append-icon="mdi-feature-search-outline"
                                @click:append="handleSelectReferal"
                            />
                            <v-btn color="red" @click="handleClearFilter"><v-icon>mdi-cancel</v-icon></v-btn>
                        </v-toolbar>
                    </template>
                    <template #[`item.referal`]="{item}">
                        <div class="text-truncate" style="max-width: 250px; width: 100%: text-align:center; margin: 0 auto; cursor: pointer; over-flow:hidden;" :title="item.referal">
                            <v-btn small color="indigo" v-clipboard="item.referal" class="text-lowercase"><v-icon left >mdi-content-copy</v-icon>{{ item.referal }}</v-btn>
                        </div>
                    </template>
                    <template #[`item.keyword`]="{item}">
                        <div style="max-width: 150px; overflow-hidden; width: 100%; margin: 0 auto;">
                            <v-list-item class="text-truncate">
                                {{ item.keyword && item.keyword.name ? item.keyword.name : '' }}
                            </v-list-item>
                        </div>
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
    </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import LineChart from '../components/LineChart.vue';
import PieChart from '../components/PieChart.vue'

export default {
    components: {
        LineChart,
        PieChart
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '5%' },
                { text: 'Browser', value: 'browser', align: 'center', sortable: true,  width: '10%' },
                { text: 'Device', value: 'device', align: 'center', sortable: true,  width: '10%' },
                { text: 'OS', value: 'os_platform', align: 'center', sortable: true,  width: '10%' },
                { text: 'Referal', value: 'referal', align: 'center', sortable: true,  width: '20%' },
                { text: 'Ip Address', value: 'ip', align: 'center', sortable: true,  width: '15%' },
                { text: 'Time', value: 'nice_date', align: 'center', sortable: true,  width: '15%' },
                { text: 'Keyword', value: 'keyword', align: 'center', sortable: true,  width: '15%' },
            ],
            page: 1,
            per_page: 20,
            perPageOpts: [20, 40, 60, 80, 100],
            browserName: '',
            deviceName: '',
            osName: '',
            positionName: '',
            referalName: '',
            keywordName: '',
            order_by: '',
            order: '',
            loading: false,
            currentPage: 1,
            optionChartBar: { responsive: true, maintainAspectRatio: false },
            optionChartPie: {
                responsive: true, maintainAspectRatio: false,
                tooltips: {
                    enabled: true,
                    callbacks: {
                        afterLabel: ((tooltipItems, data) => {
                            let dataset = data.datasets[tooltipItems.datasetIndex];
                            return dataset.label
                        })
                    }
                }
            },
            setColor: [
                {
                    border: 'rgba(255, 0, 0, 1)',
                    background: 'rgba(255, 0, 0, 0.5)',
                },
                {
                    border: 'rgba(54, 195, 115, 1)',
                    background: 'rgba(54, 195, 115, 0.5)',
                },
                {
                    border: 'rgba(54, 195, 255, 1)',
                    background: 'rgba(54, 195, 255, 0.5)',
                },
                {
                    border: 'rgba(196, 48, 255, 1)',
                    background: 'rgba(196, 48, 255, 0.5)',
                },
                {
                    border: 'rgba(196, 148, 255, 1)',
                    background: 'rgba(196, 148, 255, 0.5)',
                },
                {
                    border: 'rgba(196, 148, 39, 1)',
                    background: 'rgba(196, 148, 39, 0.5)',
                },

            ],
            dropItems: [
                { name: 'This Week', val: 'thisweek' },
                { name: 'Last 2 Weeks', val: 'twoweek' },
                { name: 'Last 3 Weeks', val: 'threeweek' },
                { name: 'This Month', val: 'onemonth' },
                { name: 'Last 3 months', val: 'threemonth' },
                { name: 'Last 6 months', val: 'sixmonth' },
                { name: 'This Year', val: 'oneyear' }
                // { name: 'All', val: 'all' }
            ],
            dropItemLinks: [
                { name: 'Today', val: 'today' },
                { name: 'This Week', val: 'thisweek' },
                { name: 'Last 2 Weeks', val: 'twoweek' },
                { name: 'Last 3 Weeks', val: 'threeweek' },
                { name: 'This Month', val: 'onemonth' },
                { name: 'Last 3 months', val: 'threemonth' },
                { name: 'Last 6 months', val: 'sixmonth' },
                { name: 'This Year', val: 'oneyear' }
                // { name: 'All', val: 'all' }
            ],
            currentOpt: { name: 'This Week', val: 'thisweek' },
            currentOptLink: {},
            dates: []
        }
    },
    computed: {
        ...mapGetters('keyword', {
            listTracking: 'getTracking',
            trackingFilter: 'getFilter',
            pagination: 'getTrackingPagination',
            chartClick: 'getClickChart'
        }),
        browser () {
            if(this.trackingFilter && this.trackingFilter.browser && Object.keys(this.trackingFilter.browser).length) {
                return this.trackingFilter.browser;
            }
            return {}
        },
        device () {
            if(this.trackingFilter && this.trackingFilter.device && Object.keys(this.trackingFilter.device).length) {
                return this.trackingFilter.device;
            }
            return {}
        },
        os_platform () {
            if(this.trackingFilter && this.trackingFilter.os_platform && Object.keys(this.trackingFilter.os_platform).length) {
                return this.trackingFilter.os_platform;
            }
            return {}
        },
        keyword () {
            if(this.trackingFilter && this.trackingFilter.keyword && Object.keys(this.trackingFilter.keyword).length) {
                return this.trackingFilter.keyword;
            }
            return {}
        },
        clickChartDataset () {
            if (Object.keys(this.chartClick).length) {
                return this.createClickChartData(this.chartClick, 'link');
            }
            return {};
        },
        dateRange () {
            if (this.dates.length === 2) {
                const from = ((new Date(this.dates[0])).getTime()) > ((new Date(this.dates[1])).getTime()) ? this.formatDate(this.dates[1]) : this.formatDate(this.dates[0]);
                const to = ((new Date(this.dates[0])).getTime()) > ((new Date(this.dates[1])).getTime()) ? this.formatDate(this.dates[0]) : this.formatDate(this.dates[1]);
                return `From ${from} - To ${to}`;
            }
            return 'Choose Date Range';
        },
        pieChartDatasetDevice () {
            if (Object.keys(this.chartClick).length) {
                return this.createPieChartData(this.chartClick, 'device');
            }
            return {};
        }
    },
    created () {
        this.initPageData();
    },
    methods: {
        ...mapActions('keyword', [
            'getTracking', 'getFilter', 'getClickChart'
        ]),
        async initPageData () {
            try {
                await Promise.all([
                    this.getTracking({
                        page: this.page,
                        per_page: this.per_page,
                        browser: this.browserName,
                        device: this.deviceName,
                        os_platform: this.osName,
                        referal: this.referalName,
                        order_by: this.order_by,
                        order: this.order

                    }),
                    this.getFilter(),
                    this.getClickChart({ day: this.currentOpt.val })
                ])
            } catch (err) {
                // if ((this.$store.getters['user/getScreen']).includes('banner') === false) {
                //     this.$router.push('/welcome');
                // }
                console.log(err);
            }
        },
        async loadPageData () {
            try {
                this.loading = true;
                await this.getTracking({
                    page: this.currentPage,
                    per_page: this.per_page,
                    browser: this.browserName,
                    device: this.deviceName,
                    os_platform: this.osName,
                    referal: this.referalName,
                    position: this.positionName,
                    keyword: this.keywordName,
                    day: this.currentOptLink.val,
                    order_by: this.order_by,
                    order: this.order

                });
                this.loading = false;
            } catch (err) {
                this.loading = false;
                alert(`No Result For Filter ${ this.browserName }, ${ this.deviceName }, ${ this.osName }, ${ this.positionName }, ${ this.referalName }`)
                console.log(err);
            }
        },
        handleNewPage () {
            this.loadPageData();
        },
        handleSelectBrowser (b) {
            this.browserName = b;
            this.loadPageData();
        },
        handleSelectDevice (d) {
            this.deviceName = d;
            this.loadPageData();
        },
        handleSelectOs (os) {
            this.osName = os;
            this.loadPageData();
        },
        handleSelectPosition (po) {
            this.positionName = po;
            this.loadPageData();
        },
        handleSelectKeyword (keyword) {
            this.keywordName = keyword;
            this.loadPageData()
        },
        handleSelectPerPage (per_page) {
            this.per_page = per_page;
            this.loadPageData()
        },
        handleSelectTimeRangeOpt (opt) {
            this.currentOptLink = opt;
            this.loadPageData()
        },
        handleClearFilter () {
            this.browserName = '';
            this.osName = '';
            this.deviceName = '';
            this.positionName = '';
            this.referalName = '';
            this.keywordName = '';
            this.loadPageData();
        },
        handleSelectReferal () {
            if (this.referalName !== '') {
                this.currentPage = 1;
                this.loadPageData();
            }
        },
        createClickChartData (data, indexStr) {
            const dates = [];
            const dataSetObject = {};
            const reverse = {...data};

            Object.keys(reverse).forEach(key => {
                if (data[key]) {
                    dates.push(key);
                    const setData = {};
                    data[key].forEach(item => {
                        if (item[indexStr]) {
                            if (!setData[item[indexStr]]) setData[item[indexStr]] = [];
                            setData[item[indexStr]] = [ ...setData[item[indexStr]], item ];
                        }

                    })
                    Object.keys(setData).forEach(index => {
                        if (!dataSetObject[index]) dataSetObject[index] = [];
                        dataSetObject[index].push(setData[index].length);
                    })
                }
            });
            const dataSet = [];
            let count = 0;
            Object.keys(dataSetObject).forEach(key => {
                dataSet.push({
                    label: key,
                    data: dataSetObject[key],
                    borderColor: this.setColor[count].border,
                    backgroundColor: this.setColor[count].background
                });
                count++;
            });
            return {
                labels: dates,
                datasets: dataSet
            }
        },
        createPieChartData (data, indexStr) {
            const mdata = { ...data };
            const fData = {};
            Object.keys(data).forEach(key => {
                if(mdata[key]) {
                    mdata[key].forEach(item => {
                        if (!fData[item.keyword]) fData[item.keyword] = [];
                        fData[item.keyword].push(item);
                    })
                }
            });

            const dataSet = {};
            const labels = ['Desktop', 'Mobile', 'Tablet'];


            Object.keys(fData).forEach(key => {
                const setData = {};
                fData[key].forEach((item) => {
                    const device = item.device;
                    if (!setData[item.device]) setData[item.device] = 0;
                    setData[item.device] = setData[item.device] + 1;
                });
                dataSet[key] = setData;
            });
            const dataCollection = [];
            Object.keys(dataSet).forEach(key => {
                const data = [];
                const bg = [];
                let count = 0;
                labels.forEach(item => {

                    if (!dataSet[key][item]) {

                        data.push(0)

                    } else {
                        data.push(dataSet[key][item]);
                    }

                    bg.push(this.setColor[count].border);

                    count++
                });

                dataCollection.push({
                    label: key,
                    data,
                    backgroundColor: bg
                });

            });

            return {
                labels,
                datasets: dataCollection
            };

        },
        randomColor () {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        },

        getDomain (url) {
            let domain = (new URL(url));
            console.log(domain.protocol, domain.hostname);
            return domain.protocol + '//' + domain.hostname;
        },
        handleSelectOption (opt) {
            this.currentOpt = opt;
            this.getChartClick({ day: this.currentOpt.val })
                .catch(err => console.log(err))
        },
        handleLoadLinkHitByDateRange () {
            if (this.dates.length < 2) {
                alert('Pls select date range');
                return;
            }
            const from = ((new Date(this.dates[0])).getTime()) > ((new Date(this.dates[1])).getTime()) ? this.dates[1] : this.dates[0];
            const to = ((new Date(this.dates[0])).getTime()) > ((new Date(this.dates[1])).getTime()) ? this.dates[0] : this.dates[1];
            this.getChartClick({ from, to })
                .catch (err => {
                    console.log(err);
                })
        },
        formatDate(date) {
            const [year, month, day] = date.split('-');
            return day + '/' + month + '/' + year
        }
    }
};
</script>

<style></style>
