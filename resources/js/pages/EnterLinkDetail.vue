<template>
    <v-row>
        <v-col cols="12">
            <v-toolbar>
                <v-toolbar-title>
                    <v-btn text @click="$router.go(-1)">
                        <v-icon left size="30">mdi-arrow-left</v-icon>
                    </v-btn>
                    {{ enterLinkDetail.slug }}
                </v-toolbar-title>
            </v-toolbar>
        </v-col>
        <v-col cols="12">
            <v-form @submit.prevent="handleUpdateLink">
               <v-row>
                   <v-col cols="8">
                        <v-card class="pa-4">
                            <div class="d-flex">
                                <v-spacer></v-spacer>
                                <v-btn color="warning" @click="formDisable = false" :disabled="!formDisable"><v-icon left>mdi-pencil</v-icon> Edit</v-btn>
                            </div>
                            <v-card-text>
                                <v-text-field :value="redirector" disabled label="Redirect url" />
                                <v-text-field v-model="link" label="Link" :disabled="formDisable" />
                                <v-text-field v-model="name" label="Name" :disabled="formDisable" />
                                <v-row>
                                    <v-col cols="6">
                                        <v-autocomplete
                                            v-model="folder_id"
                                            :items="folderList"
                                            label="Folder"
                                            item-text="name"
                                            item-value="id"
                                            class="mt-4"
                                            :disabled="formDisable"
                                        >
                                        </v-autocomplete>
                                    </v-col>
                                </v-row>

                            </v-card-text>
                        </v-card>
                   </v-col>
                   <v-col cols="4">
                       <v-card>
                           <v-card-text>

                                <v-row>
                                    <v-col>
                                        <v-text-field :value="createTime" label="Created At" disabled append-icon="mdi-calendar" />
                                    </v-col>
                                    <v-col>
                                        <v-text-field :value="updateTime" label="Updated At" disabled append-icon="mdi-calendar" />
                                    </v-col>

                                </v-row>
                                <v-row>
                                    <v-col>
                                        <v-switch label="Feature" :input-value="feature" @change="handleSwitch" :disabled="formDisable"></v-switch>
                                    </v-col>
                                    <v-col>
                                        <v-text-field label="View" disabled :value="hit" append-icon="mdi-cursor-default-click" />
                                    </v-col>
                                </v-row>
                                <v-btn block color="primary" type="submit" :disabled="formDisable">Save</v-btn>
                           </v-card-text>
                       </v-card>
                   </v-col>
               </v-row>
            </v-form>

        </v-col>
        <v-col cols="12">
            <v-toolbar>
                <v-tabs>
                    <v-tab @click="currentTab = 1">Link Click List</v-tab>
                    <v-tab @click="currentTab = 2">Quick Analytic</v-tab>
                </v-tabs>
            </v-toolbar>
            <v-card class="mt-4">
                <v-toolbar>
                    <v-toolbar-title>
                        <template  v-if="currentTab === 1">
                            Link Click List
                        </template>
                        <template v-else>
                            <v-icon left>mdi-chart-pie</v-icon> Quick Analytic
                        </template>
                    </v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
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
                            </v-btn>
                        </template>
                        <v-list class="fix-height">
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
                <v-card-text  v-if="currentTab === 1">
                    <v-data-table
                        :headers="headers"
                        :items="linkHit"
                        :hide-default-footer="true"
                        disable-pagination
                        :loading="loading"
                        class="elevation-1"
                    >
                        <template #[`item.referal`]="{item}">
                            <div class="text-truncate" style="max-width: 250px; width: 100%: text-align:center; margin: 0 auto; cursor: pointer;" :title="item.referal">
                                {{ item.referal }}
                            </div>
                        </template>
                    </v-data-table>
                </v-card-text>
                <v-card-text  v-if="currentTab === 2">
                    <v-row v-if="topReferal && linkHit.length">
                        <v-col>
                            <v-card>
                                <v-card-text>
                                    <v-list-item class="text-h6">Top Referal: {{ topReferal.refer }} ({{ topReferal.number }}) </v-list-item>
                                    <v-list-item class="text-h6">Total Click: {{ linkHit.length }} </v-list-item>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <v-card>
                                <v-card-title>
                                    Link Click Chart
                                </v-card-title>
                                <v-card-text>
                                    <LineChart :chart-data="lineChartData" :options="optionChartBar" />
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="6">
                            <v-card>
                                <v-card-title>
                                    Most Use Device
                                </v-card-title>
                                <v-card-text>
                                    <PieChart :chart-data="deviceChartData" :options="optionChartBar" />
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col cols="6">
                            <v-card>
                                <v-card-title>
                                    Most Use OS
                                </v-card-title>
                                <v-card-text>
                                    <PieChart :chart-data="osPlatformChartData" :options="optionChartBar" />
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import PieChart from '../components/PieChart.vue'
import LineChart from '../components/LineChart.vue';

export default {
    components: {
        PieChart,
        LineChart
    },
    data () {
        return {
            link: '',
            name: '',
            feature: '',
            created_at: '',
            updated_at: '',
            folder_id: '',
            id: '',
            redirector: '',
            optionChartBar: {responsive: true, maintainAspectRatio: false},
            currentTab: 1,
            hit: 0,
            headers: [
                { text: 'ID', value: 'id', align: 'center', sortable: true,  width: '10%' },
                { text: 'Browser', value: 'browser', align: 'center', sortable: true,  width: '10%' },
                { text: 'Device', value: 'device', align: 'center', sortable: true,  width: '10%' },
                { text: 'OS', value: 'os_platform', align: 'center', sortable: true,  width: '10%' },
                { text: 'Referal', value: 'referal', align: 'center', sortable: true,  width: '30%' },
                { text: 'Ip Address', value: 'ip', align: 'center', sortable: true,  width: '15%' },
                { text: 'Time', value: 'nice_date', align: 'center', sortable: true,  width: '15%' },
            ],
            dropItems: [
                { name: 'Today', val: 'today' },
                { name: 'This Week', val: 'thisweek' },
                { name: 'Last 2 Weeks', val: 'twoweek' },
                { name: 'Last 3 Weeks', val: 'threeweek' },
                { name: '1 Months', val: 'onemonth' },
                // { name: 'All', val: 'all' }
            ],
            loading: false,
            currentOpt: { name: 'Today', val: 'today' },
            dates: [],
            previewDialog: false,
            formDisable: true,
            openMedia: false
        }
    },
    computed: {
        ...mapGetters('enterLink', {
            enterLinkDetail: 'getEnterLinkDetail',
            linkHit: 'getEnterLinkHit',
            folderList: 'getFolderLinkList'
        }),
        createTime () {
            if (this.created_at) {
                return this.getTime(this.created_at);
            }
            return ''
        },
        updateTime () {
            if (this.updated_at) {
                return this.getTime(this.updated_at);
            }
            return ''
        },
        deviceChartData () {
            return this.createChatData(
                'device',
                { Desktop: 'rgb(255, 99, 132)', Mobile: 'rgb(54, 162, 235)', Tablet: 'rgb(255, 205, 86)' },
                'Most used Device'
            );
        },
        // browserChartData () {
        //     return this.createChatData(
        //         'browser',
        //         {
        //             Chrome: 'rgb(255, 99, 132)', 'Mozilla Firefox': 'rgb(54, 162, 235)', IE: 'rgb(255, 205, 86)',
        //             Safari: 'rgb(255, 204, 204)', Opera: 'rgb(255, 255, 153)', Netscape: 'rgb(153, 51, 51)',
        //             'Internet Explorer': 'rgb(102, 0, 51)'
        //         },
        //         'Most used Browser'
        //     );
        // },
        osPlatformChartData () {
            return this.createChatData(
                'os_platform',
                {
                    linux: 'rgb(255, 99, 132)', mac: 'rgb(54, 162, 235)', windows: 'rgb(255, 205, 86)'
                },
                'Most used Os PlatForm'
            );
        },
        topReferal () {
            if (this.linkHit.length) {
                const refers = {};
                this.linkHit.forEach(item => {
                    if (item.referal) {
                        if (refers[item.referal]) {
                            refers[item.referal] = refers[item.referal] + 1;
                        }
                        else {
                            refers[item.referal] = 1;
                        }
                    }
                });

                let toprefer = null;
                let check = 0;
                Object.keys(refers).forEach(refer => {
                    if (refers[refer]) {
                        const number = refers[refer];
                        if (number > check) {
                            check = number;
                            toprefer = { refer, number }
                        }
                    }
                });
                return toprefer
            }
            return null;
        },
        dateRange () {
            if (this.dates.length === 2) {
                const from = ((new Date(this.dates[0])).getTime()) > ((new Date(this.dates[1])).getTime()) ? this.formatDate(this.dates[1]) : this.formatDate(this.dates[0]);
                const to = ((new Date(this.dates[0])).getTime()) > ((new Date(this.dates[1])).getTime()) ? this.formatDate(this.dates[0]) : this.formatDate(this.dates[1]);
                return `From ${from} - To ${to}`;
            }
            return 'Choose Date Range';
        },
        lineChartData () {
            if (this.linkHit.length) {
                const groupLink = {};
                const linkHit = [...this.linkHit];

                (linkHit.reverse()).forEach(item => {
                    if (groupLink[item.created_at]) {
                        groupLink[item.created_at].push(item);
                    }
                    else {
                        groupLink[item.created_at] = [];
                        groupLink[item.created_at].push(item);
                    }
                });
                const dates = [];
                const dataCollection = []
                Object.keys(groupLink).forEach(date => {
                    dates.push(date);
                    dataCollection.push(groupLink[date].length);
                })
                return {
                    labels: dates,
                    datasets: [
                        {
                            label: 'Link Click',
                            backgroundColor: '#f87979',
                            data: dataCollection
                        }
                    ]
                };
            }
            return {}
        }
    },
    async created () {
        const id = this.$route.params.id;
        try {
            const [link, cateogry, linkHit] = await Promise.all([
                this.getEnterLinkDetail(id),
                this.getFolderLinkList(),
                this.getEnterLinkHit({ id, day: 'today' })
            ]);
            this.createInitData(link)
        } catch (err) {
            console.log(err);
        }
    },
    methods: {
        ...mapActions('enterLink', [
            'getEnterLinkDetail',
            'updateEnterLink',
            'getEnterLinkHit',
            'getFolderLinkList'
        ]),
        createInitData (data) {
            Object.keys(data).forEach(key => {
                if (data[key] && this.hasOwnProperty(key)) {
                    this[key] = data[key];
                }
            });
        },
        handleSwitch () {
            if (parseInt(this.feature)) {
                this.feature = 0
            } else {
                this.feature = 1
            }
        },
        handleUpdateLink () {
            if (this.link.trim() === '' || this.folder_id === '' || this.name.trim() === '') {
                alert('Link, Name and Folder is Required');
            };
            const data = {
                link: this.link,
                name: this.name,
                feature: this.feature,
                folder_id: this.folder_id,
                id: this.id
            };

            this.updateEnterLink(data).then(resp => {
                alert('Update Success');
            }).catch(err => {
                console.log(err)
            });

        },
        getTime(time) {
            const date = new Date(time);
            return (date.getDate().toString()).padStart(2, '0') + '/' + ((date.getMonth() + 1).toString()).padStart(2, '0') + '/' + date.getFullYear() + ' ' + (date.getHours().toString()).padStart(2, '0') + ':' + (date.getMinutes().toString()).padStart(2, '0');
        },
        handleOpenUpload () {
            this.$refs.uploadFile.click()
        },
        handleUploadFile () {
            const file = this.$refs.uploadFile.files[0];
            if (file) {
                const data = new FormData();
                data.append('image', file);
                this.uploadImage(data)
                    .then(resp => {
                       this.image = resp.image
                    })
                    .catch(err => console.log(err))
            }
        },
        createChatData (key, mapColor, label) {
            if (this.linkHit.length) {
                const device = {};
                this.linkHit.forEach((item) => {
                    if (device[item[key]]) {
                        device[item[key]] = device[item[key]] + 1;
                    }
                    else {
                        device[item[key]] = 1;
                    }
                })
                const labels = [];
                const data = [];
                const backgroundColor = [];
                // const mapColor = { Desktop: 'rgb(255, 99, 132)', Mobile: 'rgb(54, 162, 235)', Tablet: 'rgb(255, 205, 86)' };
                Object.keys(device).forEach(name => {
                    if (device[name]) {
                        labels.push(name);
                        data.push(device[name]);
                        backgroundColor.push(mapColor[name]);
                    }
                });

                return {
                    labels,
                    datasets: [{
                        label,
                        data,
                        backgroundColor,
                        hoverOffset: 4
                    }]
                }
            }

            return {}
        },
        handleSelectOption (opt) {
            this.currentOpt = opt;
            this.loading = true;
            this.getEnterLinkHit({ id: this.$route.params.id, day: this.currentOpt.val })
                .then(resp => {
                    this.loading = false;
                })
                .catch (err => {
                    this.loading = false;
                    console.log(err);
                })
        },
        formatDate(date) {
            const [year, month, day] = date.split('-');
            return day + '/' + month + '/' + year
        },
        handleLoadLinkHitByDateRange () {
            if (this.dates.length < 2) {
                alert('Pls select date range');
                return;
            }
            const from = ((new Date(this.dates[0])).getTime()) > ((new Date(this.dates[1])).getTime()) ? this.dates[1] : this.dates[0];
            const to = ((new Date(this.dates[0])).getTime()) > ((new Date(this.dates[1])).getTime()) ? this.dates[0] : this.dates[1];
            this.getEnterLinkHit({ id: this.$route.params.id, from, to })
                .then(resp => {
                    this.loading = false;
                })
                .catch (err => {
                    this.loading = false;
                    console.log(err);
                })
        },
        handleCloseMedia (item) {
            this.openMedia = false;
            if (item) {
                this.image = item.path;
            }
        },
    }
}
</script>

<style>

</style>
