<template>
    <v-row>
        <v-col cols="12">
            <v-data-table
                v-model="selected"
                :headers="headerFilter"
                :items="teleList"
                :hide-default-footer="true"
                disable-pagination
                :loading="loading"
                show-select
                :expanded.sync="expanded"
                show-expand
                :single-expand="true"
                class="elevation-1"
            >
                <template #[`top`]>
                    <v-sheet class="d-flex pa-2 align-center" color="#363636">
                        <div class="text-h5">
                            List Sales
                        </div>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <div class="head-tool d-flex align-center flex-column">
                            <div class="d-flex ma-1">
                                <div class="d-flex align-center justify-center" style="max-width: 450px;">
                                    <v-autocomplete
                                        :items="statuses"
                                        v-model="status_id"
                                        item-text="name"
                                        item-value="value"
                                        label="Status"
                                        hide-details=""
                                        solo
                                        background-color="pink"
                                        class="ma-1"
                                        dense
                                        clear-icon="mdi-close"
                                        clearable
                                        @change="handleSelectFilterTele"
                                    >
                                    </v-autocomplete>
                                    <v-autocomplete
                                        :items="teleUserF"
                                        v-model="assign_to"
                                        item-text="name"
                                        item-value="id"
                                        label="Assigned"
                                        hide-details=""
                                        solo
                                        background-color="purple"
                                        class="ma-1"
                                        dense
                                        clear-icon="mdi-close"
                                        clearable
                                        @change="handleSelectFilterTele()"
                                    >
                                    </v-autocomplete>
                                    <v-autocomplete
                                        :items="lineListF"
                                        v-model="lineId"
                                        item-text="name"
                                        item-value="id"
                                        label="Line"
                                        hide-details=""
                                        solo
                                        background-color="indigo"
                                        class="ma-1"
                                        dense
                                        clear-icon="mdi-close"
                                        clearable
                                        @change="handleSelectFilterTele"
                                    >
                                    </v-autocomplete>
                                </div>
                                <v-menu
                                    offset-y

                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            color="success"
                                            dark
                                            v-bind="attrs"
                                            v-on="on"
                                            class="ma-1"
                                            >
                                           {{ call_date_time ? call_date_time : 'Filter call date' }}
                                        </v-btn>
                                    </template>
                                    <v-date-picker
                                        v-model="call_date_time"
                                        @change="handleFilterCallDate"
                                    ></v-date-picker>
                                </v-menu>
                                <div class="d-flex ma-1" style="max-width: 120px;">
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
                                >
                                </v-text-field>
                            </div>
                            <div class="d-flex ma-1 ml-4 align-center" style="width: 100%">
                                <v-btn :disabled="dup" color="red" class="ma-1" @click="handleCheckDuplicate">Check Duplicate</v-btn>
                                <v-btn class="ma-1" color="red" @click="handleClearFilter"><v-icon left>mdi-close</v-icon> Clear</v-btn>
                                <div class="mr-4 ml-2" v-if="canAssign">Assign</div>
                                <div v-if="canAssign" class="d-flex mr-8" style="width: 150px;">
                                    <v-autocomplete
                                        :items="teleUserC"
                                        v-model="assignedTo"
                                        item-text="name"
                                        item-value="id"
                                        label="To"
                                        hide-details=""
                                        solo
                                        dense
                                        background-color="success"
                                        @change="handleSelectAssign"
                                        :disabled="!selected.length"
                                    >
                                    </v-autocomplete>
                                </div>
                                <v-btn v-if="canDelete" color="red" class="ma-1 ml-8 mr-auto" :disabled="!selected.length" @click="handleDeleteMany">Delete Record</v-btn>
                                <input type="file" hidden ref="excelFile" @change="handleImportData" />
                                <v-btn v-if="canImport" class="ma-1 mr-4" color="success" @click="$refs.excelFile.click()"><v-icon left>mdi-cloud</v-icon> Import Data</v-btn>
                            </div>
                        </div>
                        <v-spacer></v-spacer>
                        <div class="d-flex flex-column">
                            <v-btn color="primary" @click="openCreateDialog = true" class="mb-2"><v-icon>mdi-plus</v-icon>Create</v-btn>
                            <v-menu
                                offset-y
                                :close-on-content-click="false"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="purple"
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                    >
                                        <v-icon left>mdi-cog</v-icon>
                                        Field Setting
                                    </v-btn>
                                </template>
                                <v-list>
                                    <v-list-item
                                        v-for="(checkbox, index) in checkboxHeaders"
                                        :key="index +'checkbox'">
                                        <v-checkbox
                                            v-model="checkboxSelected"
                                            :label="checkbox"
                                            :value="checkbox"
                                            @change="handleSaveCheckBoxHead"
                                            >
                                        </v-checkbox>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>
                    </v-sheet>
                </template>
                <template #[`item.status`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'status')"
                        @save="handleSaveItem('status')"
                    >
                        <v-btn small :color="statuses[item.status] && statuses[item.status].color ? statuses[item.status].color : ''">{{ statuses[item.status] && statuses[item.status].name  ? statuses[item.status].name : 'No Action' }}</v-btn>
                        <template #input>
                            <v-autocomplete
                                v-model="status"
                                :items="statuses"
                                label="Status"
                                item-text="name"
                                item-value="value"
                                class="mt-4"
                            >
                            </v-autocomplete>
                        </template>
                    </v-edit-dialog>
                </template>
                 <template #[`item.username`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'username')"
                        @save="handleSaveItem('username')"
                    >
                        <v-list-item>{{ item.username }}</v-list-item>
                        <template #input>
                            <v-text-field label="User Name" v-model="username"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.full_name`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'full_name')"
                        @save="handleSaveItem('full_name')"
                    >
                        <v-list-item>{{ item.full_name ? item.full_name : 'No Name' }}</v-list-item>
                        <template #input>
                            <v-text-field label="Full name" v-model="full_name"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.phone`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'phone')"
                        @save="handleSaveItem('phone')"
                    >
                        <v-list-item>{{ item.phone ? item.phone : 'No Phone' }}</v-list-item>
                        <template #input>
                            <v-text-field label="Phone" v-model="phone"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.call_date`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'call_date')"
                        @save="handleSaveItem('call_date')"
                    >
                        <v-list-item :title="item.call_date ? item.call_date : ''" style="width: 130px; overflow: hidden;" class="text-truncate">{{ item.call_date ? item.call_date : 'add date' }}</v-list-item>
                        <template #input>
                            <v-text-field label="Call date" v-model="call_date"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.line_id`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'line_id')"
                        @save="handleSaveLine()"
                    >
                        <v-list-item>
                             <template v-if="item.line && item.line.name">
                                {{ item.line.name  }}
                            </template>
                            <template v-else>
                                <v-icon>mdi-plus</v-icon>
                            </template>
                        </v-list-item>
                        <template #input>
                            <v-autocomplete
                                v-model="line_id"
                                :items="lineListC"
                                label="Line"
                                item-text="name"
                                item-value="id"
                                class="mt-4"
                            >
                            </v-autocomplete>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.email`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'email')"
                        @save="handleSaveItem('email')"
                    >
                        <v-list-item>{{ item.email ? item.email : 'No Email' }}</v-list-item>
                        <template #input>
                            <v-text-field label="Email" v-model="email"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.agent`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'agent')"
                        @save="handleSaveAgent()"
                    >
                        <v-list-item>
                            <template v-if="item.agent_l && item.agent_l.name">
                                {{ item.agent_l.name }}
                            </template>
                            <template v-else>
                                <v-icon>mdi-plus</v-icon>
                            </template>
                        </v-list-item>
                        <template #input>
                            <v-autocomplete
                                v-model="agent"
                                :items="item.line && item.line.agents ? item.line.agents : []"
                                label="Agent"
                                item-text="name"
                                item-value="id"
                            >
                            </v-autocomplete>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.dob`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'dob')"
                        @save="handleSaveItem('dob')"
                    >
                        <v-list-item>{{ item.dob | dateString }}</v-list-item>
                        <template #input>
                            <v-text-field label="DOB" placeholder="yyyy-mm-dd" v-model="dob"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.note`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'note')"
                        @save="handleSaveItem('note')"
                    >
                        <v-list-item :title="item.note" style="width: 150px; overflow: hidden;" two-line class="text-truncate">{{ item.note }}</v-list-item>
                        <template #input>
                            <v-text-field label="Note" v-model="note"></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.assign_id`]="{item}">
                    <v-edit-dialog
                        v-if="canAssign"
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'assign_id')"
                        @save="handleSaveAssign()"
                    >
                        <v-list-item>
                            <template v-if="item.assigned_to_user && item.assigned_to_user.name">
                                {{ item.assigned_to_user.name }}
                            </template>
                            <template v-else>
                                <v-icon>mdi-plus</v-icon>
                            </template>
                        </v-list-item>
                        <template #input>
                            <v-autocomplete
                                v-model="assign_id"
                                :items="teleUserC"
                                label="Assign To User"
                                item-text="name"
                                item-value="id"
                            >
                            </v-autocomplete>
                        </template>
                    </v-edit-dialog>
                    <v-list-item v-else>{{ item.assigned_to_user && item.assigned_to_user.name ? item.assigned_to_user.name : '' }}</v-list-item>
                </template>
                <template #[`item.action`]="{item}">
                    <v-btn v-if="canDelete" color="red" text small class="ma-1" @click="handleDeleteTele(item.id)"><v-icon>mdi-delete</v-icon></v-btn>
                 </template>
                 <template v-slot:expanded-item="{ headers, item }">
                    <td :colspan="headers.length" class="pa-4">
                        <v-card>
                            <v-card-title>
                                Logs:
                            </v-card-title>
                            <v-card-text>
                                <TeleSaleLog v-if="item.logs && item.logs.length" :logs="item.logs" />
                                <p v-else>No Log</p>
                            </v-card-text>
                        </v-card>
                    </td>
                </template>
            </v-data-table>
            <div v-if="teleList.length" class="text-center d-flex align-center justify-center">
                <v-btn text style="margin-top: 16px;">
                    Total: {{ pagination.total }}
                </v-btn>
                <div style="width:100px;margin-right: 5px; margin-left: 20px;"  class="mt-4">
                    <v-text-field solo hide-details="" dense labe="page" v-model="jump"></v-text-field>
                </div>
                <v-btn class="mt-4" color="indigo" @click="hanldeJumpPage">Go</v-btn>
                <v-pagination v-if="pagination.page_count > 1" v-model="currentPage" :value="currentPage" class="mt-4" :length="pagination.page_count" :total-visible="7" @input="handleNewPage" />
            </div>
        </v-col>
        <CreateTeleSaleDialog :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" :statuses="statuses" :lines="lineList" />
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import TeleSaleLog from '../components/TeleSaleLog';
import CreateTeleSaleDialog from '../components/CreateTeleSaleDialog';

export default {
    components: {
        TeleSaleLog,
        CreateTeleSaleDialog
    },
    data () {
        return {
            headers: [
                { text: 'ID', value: 'id', align: 'center'},
                { text: 'Full name', value: 'full_name', align: 'center', width: '15%'},
                { text: 'Phone', value: 'phone', align: 'center'},
                { text: 'Email', value: 'email', align: 'center'},
                { text: 'DOB', value: 'dob', align: 'center'},
                { text: 'Status', value: 'status', align: 'center'},
                { text: 'Call Date', value: 'call_date', align: 'center'},
                { text: 'Note', value: 'note', align: 'center', width: '20%'},
                { text: 'User Name', value: 'username', align: 'center'},
                { text: 'Line', value: 'line_id', align: 'center'},
                { text: 'Agent', value: 'agent', align: 'center'},
                { text: 'Assign To', value: 'assign_id', align: 'center'},
                { text: 'Action', value: 'action', align: 'center', sortable: false},
                { text: '', value: 'data-table-expand' },
            ],
            selected: [],
            expanded: [],
            loading: false,
            statuses: [
                {
                    name: 'No call',
                    color: 'grey',
                    value: 0
                },
                {
                    name: 'Not exist',
                    color: 'red darken-4',
                    value: 1
                },
                {
                    name: 'Not answered',
                    color: 'red',
                    value: 2
                },
                {
                    name: 'Answered',
                    color: 'success',
                    value: 3
                },
                {
                    name: 'Not interested',
                    color: 'warning',
                    value: 4
                },
                {
                    name: 'Interested',
                    color: 'pink',
                    value: 5
                },
                {
                    name: 'Registered',
                    color: 'primary',
                    value: 6
                },
                {
                    name: 'First Deposit',
                    color: 'accent',
                    value: 7
                },
                {
                    name: 'Second Deposit',
                    color: 'purple',
                    value: 8
                },
                {
                    name: 'Third Deposit',
                    color: 'indigo',
                    value: 9
                },
                {
                    name: 'Retention',
                    color: 'cyan',
                    value: 10
                },
                {
                    name: 'No Action',
                    color: 'accent',
                    value: 11
                },
                {
                    name: 'Freebet',
                    color: 'red lighten-1',
                    value: 12
                },
                {
                    name: 'Existed',
                    color: '#662b00',
                    value: 13
                },
                {
                    name: 'Signed Up',
                    color: '#0a5a0d',
                    value: 14
                },
            ],
            status: '',
            editItem: {},
            dob: '',
            email: '',
            phone: '',
            username: '',
            full_name: '',
            line_id: '',
            agent: '',
            assign_id: '',
            note: '',
            assign_to: '',
            lineId: this.$route.query.line_id ? this.$route.query.line_id : '',
            status_id: '',
            per_page: 30,
            currentPage: this.$route.query.current_page ? parseInt(this.$route.query.current_page) : 1,
            fields: [
                {
                    name: 'Full name',
                    value: 'full_name'
                },
                {
                    name: 'Phone',
                    value: 'phone'
                },
                {
                    name: 'Email',
                    value: 'email'
                },
                {
                    name: 'User name',
                    value: 'username'
                },
                {
                    name: 'Agent',
                    value: 'agent'
                }
            ],
            fieldName: '',
            fieldVal: '',
            assignedTo: '',
            openCreateDialog: false,
            jump: '',
            call_date: '',
            checkboxHeaders: [
                'id', 'full_name', 'phone', 'email', 'dob', 'status', 'call_date', 'note', 'username', 'line_id', 'agent', 'assign_id', 'action', 'data-table-expand'
            ],
            checkboxSelected: (JSON.parse(localStorage.getItem('checkboxhead'))) && (JSON.parse(localStorage.getItem('checkboxhead'))).length ? JSON.parse(localStorage.getItem('checkboxhead')) : ['id', 'full_name', 'phone', 'email', 'dob', 'status', 'call_date', 'note', 'username', 'line_id', 'agent', 'assign_id', 'action', 'data-table-expand'],
            call_date_time: '',
            dup: false
        }
    },
    computed: {
        ...mapGetters('teleSale', {
            teleList: 'getTeleSaleList',
            lineList: 'getTeleSaleLineList',
            teleUser: 'getTeleSaleUser',
            pagination: 'getTeleSalePagination'
        }),
        ...mapGetters('user', {
            userInfo: 'getUserLogInfo'
        }),
        canAssign () {
            return this.checkPermision('TeleSaleController@updateAssign')
        },
        canDelete () {
            return this.checkPermision('TeleSaleController@deleteMany')
        },
        canImport () {
            return this.checkPermision('TeleSaleController@import')
        },
        canSeeDatList () {
            return this.checkPermision('TeleSaleController@index');
        },
        lineListC () {
            return [{ id: null, name: 'Empty' } , ...this.lineList]
        },
        teleUserC () {
            return [{ id: null, name: 'Empty' } , ...this.teleUser]
        },
        lineListF () {
            return [{ id: 'null', name: 'Empty' } , ...this.lineList]
        },
        teleUserF () {
            return [{ id: 'null', name: 'Empty' } , ...this.teleUser]
        },
        headerFilter () {
            return this.headers.filter(item => this.checkboxSelected.includes(item.value));
        }
    },
    created () {
        this.loadPageData();
    },
    methods: {
        ...mapActions('teleSale', ['getTeleSaleList', 'updateTeleSale', 'getTeleSaleLine', 'getTeleSaleUser', 'getDuplicate', 'updateAssignToUser', 'deleteTelesales', 'importTeleSale', 'getTeleSaleLineOwn', 'updateLine', 'updateAgent']),
        async loadPageData (type = 'init') {
            const func = { init: 'getTeleSaleList', dup: 'getDuplicate' }
            try {
                this.loading = true;
                await Promise.all([
                    this[func[type]]({
                        status: this.status_id,
                        assign_id: this.assign_to,
                        line_id: this.lineId,
                        full_name: this.full_name ? this.full_name : this.fieldName === 'full_name' ? this.fieldVal : '',
                        phone: this.phone ? this.phone : this.fieldName === 'phone' ? this.fieldVal : '',
                        email: this.email ? this.email : this.fieldName === 'email' ? this.fieldVal : '',
                        username: this.username ? this.username : this.fieldName === 'username' ? this.fieldVal : '',
                        agent: this.agent ? this.agent : this.fieldName === 'agent' ? this.fieldVal : '',
                        per_page: this.per_page,
                        page: this.currentPage,
                        call_date_time: this.call_date_time,
                        dup: this.dup
                    }),
                    this.getTeleSaleLineOwn(),
                    this.getTeleSaleUser()
                ]);
                this.loading = false;
            } catch (err) {
                this.loading = false;
                if (type === 'dup') {
                    alert('No Duplicate');
                }
                alert(err.response && err.response.data && err.response.data.message ? err.response.data.message : 'err');
            }
        },
        checkPermision (permision) {
            if (this.userInfo.roles && this.userInfo.roles.length) {
                let check = false;
                for (let i = 0; i < this.userInfo.roles.length; i++) {
                    const role = this.userInfo.roles[i];

                    if (role.permisions && role.permisions.length) {

                        for (let j = 0; j < role.permisions.length; j++) {
                            if (((role.permisions)[j]).controller === permision) {
                                check = true;
                                break;
                            }
                        }
                    }
                    if (check === true) break;
                }
                return check;
            }
            return false;
        },
        async loadTeleSaleList () {
            try {
                this.loading = true;
                const params = {
                    status: this.status_id,
                    assign_id: this.assign_to,
                    line_id: this.lineId,
                    full_name: this.full_name,
                    phone: this.phone,
                    email: this.email,
                    username: this.username,
                    agent: this.agent,
                    per_page: this.per_page,
                    page: this.currentPage,
                    call_date_time: this.call_date_time,
                    dup: this.dup
                }
                await this.getTeleSaleList(params),
                this.loading = false;
            } catch (err) {
                this.loading = false;
                alert(err.response && err.response.data && err.response.data.message ? err.response.data.message : 'err');
            }
        },
        handelOpenEditItem(item, index) {
            this.editItem = item;
            if (index === 'call_date') {
                const date = new Date();
                const dateStr =  date.getFullYear()  + '-' + ((date.getMonth() + 1).toString()).padStart(2, '0') + '-' + (date.getDate().toString()).padStart(2, '0') + ' ' + (date.getHours().toString()).padStart(2, '0') + ':' + (date.getMinutes().toString()).padStart(2, '0');
                this[index] = dateStr;
            } else {
                if (index === 'agent') {
                    this[index] = item.agent_l.id;
                } else {
                    this[index] = item[index];
                }
            }


        },
        handleSaveItem (index) {
            let update = {...this.editItem, [index]: this[index] };
            this.loading = true;
            this.updateTeleSale(update)
                .then(resp => {
                    this.resetPageData();
                    this.loadPageData();
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    this.resetPageData();
                    // this.loading = false;
                    // console.log(err.response)
                    // alert(err.response && err.response.data && err.response.data.message ? err.response.data.message : 'err');
                });
        },
        resetPageData () {
            this.status = '';
            this.assign_id = '';
            this.line_id = '';
            this.full_name = '';
            this.phone = '';
            this.email = '',
            this.username = '',
            this.agent = '';
            this.call_date_time = '';
        },
        handleSaveAssign () {
            const data = {
                ids: [this.editItem.id],
                assign_id: this.assign_id
            }
            this.updateAssignToUser(data)
                .then(resp => {
                    this.resetPageData();
                    this.loadTeleSaleList();
                })
                .catch (err => {
                    console.log(err)
                })
        },
        handleSaveAgent () {
             const data = {
                id: this.editItem.id,
                agent_id: this.agent
            }
            this.updateAgent(data)
                .then(resp => {
                    this.resetPageData();
                    this.loadTeleSaleList();
                })
                .catch (err => {
                    console.log(err)
                })
        },
        handleSaveLine () {
             const data = {
                id: this.editItem.id,
                line_id: this.line_id
            }
            this.updateLine(data)
                .then(resp => {
                    // this.resetPageData();
                    this.loadTeleSaleList();
                })
                .catch (err => {
                    console.log(err)
                })
        },
        handleSelectAssign () {
            const ids = this.selected.map(item => item.id);
            this.updateAssignToUser({ ids, assign_id: this.assignedTo === 'null' ? null : this.assignedTo })
                .then(resp => {
                    this.selected = [];
                    this.assignedTo = '';
                    this.loadTeleSaleList();
                })
                .catch (err => {
                    console.log(err)
                })

        },
        handleSelectFilterTele () {
            this.loadTeleSaleList();
        },
        handleSearchByField () {
            if (!this.fieldName) {
                alert('Pls select Field Before Search');
                return;
            }
            if (this.fieldVal) {
                this[this.fieldName] = this.fieldVal;
                this.loadTeleSaleList();
            }
        },
        handleCheckDuplicate () {
            this.dup = true;
            this.currentPage = 1;
            this.loadPageData();
        },
        handleClearFilter () {
            this.full_name = '';
            this.line_id = '';
            this.email = '';
            this.phone = '';
            this.assign_to = '';
            this.username = '';
            this.status = '';
            this.agent = '';
            this.fieldName = '';
            this.fieldVal = '';
            this.call_date_time = '';
            this.dup = false;

            this.loadPageData();
        },
        handleDeleteTele (id) {
            const deleteConfirm = confirm('Are you sure to delete ?');

            if (deleteConfirm) {
                this.deleteTelesales({ ids: [id] })
                .then(resp => {
                    this.loadTeleSaleList();
                })
                .catch (err => {
                    console.log(err)
                })
            }

        },
        handleDeleteMany () {
            const deleteConfirm = confirm('Are you sure to delete ?');

            if (deleteConfirm) {

                const ids = this.selected.map(item => item.id);

                this.deleteTelesales({ ids })
                .then(resp => {
                    this.loadTeleSaleList();
                })
                .catch (err => {
                    console.log(err)
                })
            }
        },
        handleNewPage (currentPage) {
            this.currentPage = currentPage;
            this.loadTeleSaleList();
        },
        handleCloseCreateDialog (update) {
            this.openCreateDialog = false;
            if (update) this.loadTeleSaleList();
        },
        handleImportData () {
            const data = new FormData();
            const file = this.$refs.excelFile.files[0];
            data.append('excel', file);
            this.importTeleSale(data)
            .then(resp => {
                this.loadTeleSaleList();
                this.$refs.excelFile.value = ''
            }).catch (err => {
                console.log(err)
            })
        },
        hanldeJumpPage () {
            if (!this.jump || isNaN(this.jump)) {
                alert('page format is not correct');
                this.jump = '';
                return false;
            }
            this.currentPage = parseInt(this.jump);
            // this.$router.push('/data?current_page=' + this.jump);
            this.loadTeleSaleList()
        },
        handleSaveCheckBoxHead () {
            const checkboxStr = JSON.stringify(this.checkboxSelected);
            window.localStorage.setItem('checkboxhead', checkboxStr);
        },
        handleFilterCallDate () {
            this.loadTeleSaleList();
        }
    }
}
</script>

<style>

</style>
