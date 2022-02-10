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
                fixed-header
                height="80vh"
            >
                <template #[`top`]>
                    <v-sheet class="d-flex pa-2 align-center" color="#363636">
                        <div class="d-flex flex-column">

                            <div class="text-h5">
                                List Sales
                            </div>
                        </div>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <div class="head-tool d-flex align-center flex-column"  style="width: 80%;">
                            <div class="d-flex align-center" style="width: 100%;">
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
                                        Filter
                                        </v-btn>
                                    </template>

                                    <v-card width="600">
                                        <v-card-text>
                                            <v-row>
                                                <v-col cols="12">
                                                    <div class="search d-flex align-center">
                                                        <div style="max-width: 150px;">
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
                                                                class="mr-2"
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
                                                        >
                                                        </v-text-field>
                                                    </div>
                                                </v-col>
                                                <v-col cols="12">
                                                    <v-sheet class="d-flex flex-wrap">
                                                        <template v-for="search in listSearchFilter">
                                                            <v-btn  class="ma-1" v-if="search.value" :key="search.name" :color="search.color">
                                                                {{search.name}}: {{ search.value }}
                                                                <span style="position:absolute; top: -15px; right: -15px; width: 20px; height: 20px; background: red; border-radius: 50%; color: white; font-weight: bold; display: flex; align-items:center; justify-content:center;" @click="removeSearchFilter(search.index)">X</span>
                                                            </v-btn>
                                                        </template>
                                                    </v-sheet>
                                                </v-col>
                                                <v-col cols="12">
                                                    <v-divider horizontal></v-divider>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-autocomplete
                                                        :items="statuses"
                                                        v-model="filterStatus"
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
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-autocomplete
                                                        :items="teleUserF"
                                                        v-model="filterAssignId"
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
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-autocomplete
                                                        :items="lineListF"
                                                        v-model="filterLineId"
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
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-autocomplete
                                                        v-if="agentListF.length"
                                                        :items="agentListF"
                                                        v-model="filterAgent"
                                                        item-text="name"
                                                        item-value="id"
                                                        label="Agent"
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
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-autocomplete
                                                        :items="vipListF"
                                                        v-model="filterVipId"
                                                        item-text="name"
                                                        item-value="id"
                                                        label="Vip Level"
                                                        hide-details=""
                                                        solo
                                                        background-color="warning"
                                                        class="ma-1"
                                                        dense
                                                        clear-icon="mdi-close"
                                                        clearable
                                                        @change="handleSelectFilterTele"
                                                    >
                                                    </v-autocomplete>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-autocomplete
                                                        :items="cateogryListF"
                                                        v-model="filterCategoryId"
                                                        item-text="name"
                                                        item-value="id"
                                                        label="Category"
                                                        hide-details=""
                                                        solo
                                                        background-color="warning"
                                                        class="ma-1"
                                                        dense
                                                        clear-icon="mdi-close"
                                                        clearable
                                                        @change="handleSelectFilterTele"
                                                    >
                                                    </v-autocomplete>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-autocomplete
                                                        :items="listSource"
                                                        v-model="filterSource"
                                                        label="Domain"
                                                        hide-details=""
                                                        solo
                                                        background-color="warning"
                                                        class="ma-1"
                                                        dense
                                                        clear-icon="mdi-close"
                                                        clearable
                                                        @change="handleSelectFilterTele"
                                                    >
                                                    </v-autocomplete>
                                                </v-col>
                                                <v-col cols="12">
                                                    <v-divider horizontal></v-divider>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-menu
                                                        offset-y

                                                    >
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-btn
                                                                color="success"
                                                                dark
                                                                v-bind="attrs"
                                                                v-on="on"
                                                                style="width: 100%;"
                                                                >
                                                            {{ filterCallDate ? filterCallDate : 'Call date' }}
                                                            </v-btn>
                                                        </template>
                                                        <v-date-picker
                                                            v-model="filterCallDate"
                                                            @change="handleFilterCallDate"
                                                        ></v-date-picker>
                                                    </v-menu>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-btn :disabled="dup" color="red" @click="handleCheckDuplicate" style="width: 100%;">Duplicate</v-btn>
                                                </v-col>
                                                <v-col cols="12">
                                                    <v-divider horizontal></v-divider>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-btn color="red" @click="handleClearFilter" style="width: 100%;"><v-icon left>mdi-close</v-icon> Remove Filter</v-btn>
                                                </v-col>
                                                <v-col cols="6">
                                                    <v-btn color="warning" style="width: 100%;" @click="openFilter = false">Close</v-btn>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-card>
                                </v-menu>
                                <div style="width: 140px;">
                                    <v-autocomplete
                                        :items="bulk_actions_list"
                                        v-model="bulk_val"
                                        label="BULK EDIT"
                                        hide-details=""
                                        solo
                                        background-color="pink"
                                        class="ma-1"
                                        dense
                                        clear-icon="mdi-close"
                                        clearable
                                        :disabled="!selected.length"
                                    >
                                    </v-autocomplete>
                                </div>
                                <v-btn v-if="canDelete" color="red" class="mr-2" :disabled="!selected.length" @click="handleDeleteMany">Bulk Delete</v-btn>
                                <input type="file" hidden ref="excelFile" @change="handleImportData" />
                                <v-btn v-if="canImport" class="mr-2" color="success" @click="$refs.excelFile.click()"><v-icon left>mdi-cloud</v-icon> Import Data</v-btn>
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
                                            class="mr-2"
                                        >
                                            <v-icon left>mdi-cog</v-icon>
                                            Fields
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
                                <v-dialog
                                    v-model="dialog"
                                    width="500"
                                    >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                        color="red lighten-2"
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        class="mr-2"
                                        >
                                        <v-icon>mdi-information-variant</v-icon>
                                        </v-btn>
                                    </template>

                                    <v-card>
                                        <v-card-title class="text-h5 indigo lighten-2">
                                            Status Explaination
                                        </v-card-title>

                                        <v-card-text>
                                            <v-list>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">No action:</strong> Số mới, chưa update
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">No call:</strong> Không gọi, thuộc line Đại lý khác
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">Not exist (No owner):</strong> Không tồn tại (số không ai xài hoặc đã bị hủy)
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">Existed (Mobile off):</strong> Số tồn tại nhưng không gọi được, hết pin hoặc tắt nguồn
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">Not answer:</strong> Số reo 1-2 phút nhưng không có người trả lời
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">Answered (Line off):</strong> Đã trả lời nhưng chưa tiện nghe máy
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">Not interested (Line off):</strong> Đã trả lời nhưng chưa hứng thú
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">Interested:</strong> Đã trả lời, có hứng thú nhưng chưa đăng ký
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">Registered:</strong> Đã đăng ký nhưng chưa nạp tiền
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">Freebet:</strong> Đã nhận FB nh chưa nạp tiền
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">FD, SD, TD:</strong> Đã nạp tiền
                                                </v-list-item>
                                                <v-list-item>
                                                    <strong style="color:green; font-weight: 700; text-transform: uppercase; margin-right: 10px;">Retention:</strong> Đã nạp lại nhiều lần
                                                </v-list-item>
                                            </v-list>
                                        </v-card-text>
                                    </v-card>
                                </v-dialog>
                                <v-btn :disabled="!isPhoneAllow" color="success" @click="call_dialog = true"><v-icon left>mdi-phone</v-icon> Phone</v-btn>
                            </div>

                            <v-divider horizontal style="width: 100%;" class="my-2" v-if="bulk_val"></v-divider>
                            <div class="bulk-action d-flex align-center" v-if="bulk_val && selected.length" style="width: 100%;">
                                <span class="mr-4">Bulk Edit: </span>
                                <div class="d-flex align-center" v-if="bulk_val">
                                    <template v-if="bulk_val === 'Line & Agent'">
                                        <v-autocomplete
                                            :items="lineListBulk"
                                            v-model="bulkEditLineAgent"
                                            item-text="name"
                                            item-value="id"
                                            label="Line"
                                            hide-details=""
                                            solo
                                            background-color="indigo"
                                            class="mr-2"
                                            dense
                                            clear-icon="mdi-close"
                                            clearable
                                        >
                                        </v-autocomplete>
                                        <v-autocomplete
                                            v-if="agentListBulk.length"
                                            :items="agentListBulk"
                                            v-model="bulkEditAgent"
                                            item-text="name"
                                            item-value="id"
                                            label="Agent"
                                            hide-details=""
                                            solo
                                            background-color="indigo"
                                            class="mr-2"
                                            dense
                                            clear-icon="mdi-close"
                                            clearable
                                        >
                                        </v-autocomplete>
                                        <v-btn color="warning" @click="saveBulkEditAgent">Save</v-btn>
                                    </template>
                                    <template v-if="bulk_val === 'Full name' || bulk_val === 'Phone' || bulk_val === 'Email' || bulk_val === 'DOB' || bulk_val === 'Call date' || bulk_val === 'Note' || bulk_val === 'User name'">
                                        <v-text-field
                                            v-model="bulkEditFieldVal"
                                            :label="bulk_val"
                                            outlined
                                            dense
                                            hide-details=""
                                            class="mr-2"
                                        >
                                        </v-text-field>
                                        <v-btn color="warning" @click="saveBulkEditCommon">Save</v-btn>
                                    </template>
                                    <template v-if="bulk_val === 'Status'">
                                        <v-autocomplete
                                            :items="statuses"
                                            v-model="bulkEditFieldVal"
                                            item-text="name"
                                            item-value="value"
                                            label="Status"
                                            hide-details=""
                                            solo
                                            background-color="indigo"
                                            class="mr-2"
                                            dense
                                            clear-icon="mdi-close"
                                            clearable
                                        >
                                        </v-autocomplete>
                                        <v-btn color="warning" @click="saveBulkEditCommon">Save</v-btn>
                                    </template>
                                    <template v-if="bulk_val === 'Vip level'">
                                        <v-autocomplete
                                            :items="vipList"
                                            v-model="bulkEditFieldVal"
                                            item-text="name"
                                            item-value="id"
                                            label="Vip Level"
                                            hide-details=""
                                            solo
                                            background-color="indigo"
                                            class="mr-2"
                                            dense
                                            clear-icon="mdi-close"
                                            clearable
                                        >
                                        </v-autocomplete>
                                        <v-btn color="warning" @click="saveBulkEditCommon">Save</v-btn>
                                    </template>
                                    <template v-if="bulk_val === 'Category'">
                                        <v-autocomplete
                                            :items="cateogryList"
                                            v-model="bulkEditFieldVal"
                                            item-text="name"
                                            item-value="id"
                                            label="Category"
                                            hide-details=""
                                            solo
                                            background-color="indigo"
                                            class="mr-2"
                                            dense
                                            clear-icon="mdi-close"
                                            clearable
                                        >
                                        </v-autocomplete>
                                        <v-btn color="warning" @click="saveBulkEditCommon">Save</v-btn>
                                    </template>
                                    <template v-if="bulk_val === 'Assign'">
                                        <template v-if="canAssign">
                                            <v-autocomplete
                                                :items="teleUserF"
                                                v-model="bulkEditFieldVal"
                                                item-text="name"
                                                item-value="id"
                                                label="Assign To"
                                                hide-details=""
                                                solo
                                                background-color="indigo"
                                                class="mr-2"
                                                dense
                                                clear-icon="mdi-close"
                                                clearable
                                            >
                                            </v-autocomplete>
                                            <v-btn color="warning" @click="saveBulkEditCommon">Save</v-btn>
                                        </template>
                                        <template v-else>
                                            <span>You have no authorization</span>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <v-spacer></v-spacer>
                        <div class="d-flex flex-column">
                            <v-btn color="primary" @click="openCreateDialog = true" class="mb-2"><v-icon>mdi-plus</v-icon>Create</v-btn>
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
                <template #[`item.call`]="{item}">
                    <v-btn icon @click="handleOpenCallDialog(item)"><v-icon color="primary">mdi-phone</v-icon></v-btn>
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
                <template #[`item.vip_id`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'vip_id')"
                        @save="handleSaveItem('vip_id')"
                    >
                        <v-list-item>
                            {{ item.vip && item.vip.name ?  item.vip.name : 'Add'}}
                        </v-list-item>
                        <template #input>
                            <v-autocomplete
                                v-model="vip_id"
                                :items="vipList"
                                label="Vip Level"
                                item-text="name"
                                item-value="id"
                                class="mt-4"
                            >
                            </v-autocomplete>
                        </template>
                    </v-edit-dialog>
                </template>
                <template #[`item.category_id`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'category_id')"
                        @save="handleSaveItem('category_id')"
                    >
                        <v-list-item>
                            {{ item.category && item.category.name ?  item.category.name : 'Add'}}
                        </v-list-item>
                        <template #input>
                            <v-autocomplete
                                v-model="category_id"
                                :items="cateogryList"
                                label="Category"
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
                <template #[`item.source`]="{item}">
                    <v-edit-dialog
                        :return-value.sync="item.id"
                        @open="handelOpenEditItem(item, 'source')"
                        @save="handleSaveItem('source')"
                    >
                        <v-list-item :title="item.source" style="width: 150px; overflow: hidden;" two-line class="text-truncate">{{ item.source }}</v-list-item>
                        <template #input>
                            <v-text-field label="Source" v-model="source"></v-text-field>
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
                                <TeleSaleLog v-if="item.logs && item.logs.length" :logs="item.logs" :categories="cateogryList" :lines="lineList" :vips="vipList" :agents="agentList"/>
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
        <CreateTeleSaleDialog :openCreateDialog="openCreateDialog" @close-create-dialog="handleCloseCreateDialog" :statuses="statuses" :lines="lineList" :vips="vipList" :categories="cateogryList" />
        <v-dialog
            v-model="call_dialog"
            max-width="315"
            persistent
            >
            <v-card>
                <div style="width: 100%">
                    <v-tabs
                        centered
                        dark
                        v-model="phoneTab"
                    >
                        <v-tab @click="handleFocusInput('agentInput')" class="flex-1">Internal Call</v-tab>
                        <v-tab  @click="handleFocusInput('phoneInput')" class="flex-1">Outbound Call</v-tab>
                    </v-tabs>
                </div>
                <v-card-text>
                    <v-row>
                        <v-col cols="12">

                        </v-col>
                    </v-row>
                    <v-row v-if="phoneTab">
                        <v-col cols="12" v-if="!calling">
                            <div class="call-number">
                                <input type="text" class="number-to-call" v-model="countryCode" />
                                <input type="text" class="number-to-call" v-model="numberToCall" placeholder="Enter number" ref="phoneInput" />
                            </div>
                        </v-col>
                    </v-row>
                    <v-row v-if="!phoneTab">
                        <v-col cols="12" v-if="!calling">
                            <input type="text" class="number-to-call" v-model="agentToCall" placeholder="Enter Agent" ref="agentInput" />
                        </v-col>
                    </v-row>
                    <v-row v-if="calling">
                        <v-col cols="12" class="d-flex align-center justify-center">
                            <h3 v-if="outboundCall" class="text-h5">Calling {{ phoneTab ? countryCode + numberToCall : agentToCall }}</h3>
                            <h3 v-else class="text-h5">{{ incommingIdentity }}</h3>
                        </v-col>
                        <v-col cols="12">
                           <div class="d-flex align-center">
                                <div class="mr-2">Mic: </div>
                                <div :style="`height: 10px; background-color: ${inputColor}; width: ${inputWidth}`"></div>
                           </div>
                        </v-col>
                        <v-col cols="12">
                            <div class="d-flex align-center">
                                <div class="mr-2">Speaker: </div>
                                <div :style="`height: 10px; background-color: ${outputColor}; width: ${outputWidth}`"></div>
                            </div>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" v-if="!calling">

                            <div class="phone-pad" v-if="phoneTab">
                                <v-btn class="phone-number" v-for="p in phoneFieldArr" :key="p" color="pink" @click="handlDialNumber(p)">
                                    {{ p }}
                                </v-btn>
                                <v-btn class="phone-back" color="red" @click="handleBackSpace">
                                    <v-icon>mdi-backspace</v-icon>
                                </v-btn>
                            </div>
                            <v-list v-if="!phoneTab" style="max-height: 300px" class="overflow-y-auto nice-scroll">
                                <template v-for="(agent, agentIndex) in agentLists">
                                    <v-list-item :key="agent.id" link @click="agentToCall = agent.name">
                                        <v-list-item-icon>
                                            <v-icon>mdi-account</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title>{{ agent.name }}</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-divider
                                        :key="agentIndex + 'divider'"
                                    ></v-divider>
                                </template>

                            </v-list>

                        </v-col>
                        <v-col class="d-flex align-center justify-center" cols="12">
                            <v-btn color="success" @click="handleCall('outbound')" v-if="!calling && phoneTab" fab :disabled="numberToCall.length < 3"><v-icon>mdi-phone</v-icon></v-btn>
                            <v-btn color="success" @click="handleCall('internal')" v-if="!calling && !phoneTab" fab :disabled="!agentToCall"><v-icon>mdi-phone</v-icon></v-btn>
                            <v-btn :color="callMute ? 'red' : 'success'" @click="handleToggleMute" v-if="calling" fab class="mr-8" small>
                                <v-icon>
                                    {{ callMute ? 'mdi-microphone-off' : 'mdi-microphone' }}
                                </v-icon>
                            </v-btn>
                            <v-btn color="red" @click="handleHangup" v-if="calling" fab small><v-icon>mdi-phone-hangup</v-icon></v-btn>
                        </v-col>
                        <!-- <v-col class="d-flex align-center justify-center">
                            <v-btn color="red" @click="handleHangup"><v-icon left>mdi-phone-hangup</v-icon>Hangup</v-btn>
                        </v-col> -->
                        <v-col cols="12">
                            <v-btn color="warning" @click="handleClosePhoneDialog" block>Close</v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog
            v-model="incomming_dialog"
            max-width="315"
            persistent
        >
            <v-card>
                <v-card-title>
                    Calling from {{ incommingIdentity }}
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="6" class="d-flex align-center justify-center">
                             <v-btn color="success" @click="handleInComingCall('accept')"><v-icon>mdi-phone</v-icon></v-btn>
                        </v-col>
                        <v-col cols="6" class="d-flex align-center justify-center">
                            <v-btn color="red" @click="handleInComingCall('reject')"><v-icon>mdi-phone-hangup</v-icon></v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

import TeleSaleLog from '../components/TeleSaleLog';
import CreateTeleSaleDialog from '../components/CreateTeleSaleDialog';

import { Device } from 'twilio-client';

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
                { text: 'Call', value: 'call', align: 'center'},
                { text: 'Email', value: 'email', align: 'center'},
                { text: 'DOB', value: 'dob', align: 'center'},
                { text: 'Status', value: 'status', align: 'center'},
                { text: 'Call Date', value: 'call_date', align: 'center'},
                { text: 'Note', value: 'note', align: 'center', width: '20%'},
                { text: 'User Name', value: 'username', align: 'center'},
                { text: 'Line', value: 'line_id', align: 'center'},
                { text: 'Agent', value: 'agent', align: 'center'},
                { text: 'Vip Level', value: 'vip_id', align: 'center'},
                { text: 'Category', value: 'category_id', align: 'center'},
                { text: 'Source', value: 'source', align: 'center'},
                { text: 'Assign To', value: 'assign_id', align: 'center'},
                { text: 'Action', value: 'action', align: 'center', sortable: false},
                { text: '', value: 'data-table-expand' },
            ],
            selected: [],
            expanded: [],
            loading: false,
            editItem: {},
            status: '',
            dob: '',
            email: '',
            phone: '',
            username: '',
            full_name: '',
            line_id: '',
            agent_id: '',
            assign_id: '',
            note: '',
            filterStatus: '',
            filterDob: '',
            filterEmail: '',
            filterPhone: '',
            filterUserName: '',
            filterFullName: '',
            filterLineId: this.$route.query.line_id ? this.$route.query.line_id : '',
            filterAgent: this.$route.query.agent ? this.$route.query.agent : '',
            filterAssignId: '',
            filterCallDate: '',
            status_id: '',
            per_page: 30,
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
            currentPage: this.$route.query.current_page ? parseInt(this.$route.query.current_page) : 1,
            fields: [
                {
                    name: 'Full name',
                    value: 'filterFullName'
                },
                {
                    name: 'Phone',
                    value: 'filterPhone'
                },
                {
                    name: 'Email',
                    value: 'filterEmail'
                },
                {
                    name: 'User name',
                    value: 'filterUserName'
                },
                {
                    name: 'Note',
                    value: 'filterNote'
                }
            ],
            fieldName: 'filterPhone',
            fieldVal: '',
            assignedTo: '',
            openCreateDialog: false,
            jump: '',
            call_date: '',
            checkboxHeaders: [
                'id', 'full_name', 'phone', 'call', 'email', 'dob', 'status', 'call_date', 'note', 'username', 'line_id', 'agent', 'vip_id', 'category_id', 'source', 'assign_id', 'action', 'data-table-expand'
            ],
            checkboxSelected: (JSON.parse(localStorage.getItem('checkboxhead'))) && (JSON.parse(localStorage.getItem('checkboxhead'))).length ? JSON.parse(localStorage.getItem('checkboxhead')) : ['id', 'full_name', 'phone', 'call', 'email', 'dob', 'status', 'call_date', 'note', 'username', 'line_id', 'agent', 'vip_id', 'category_id', 'source', 'assign_id', 'action', 'data-table-expand'],
            call_date_time: '',
            dup: false,
            agentListF: [],
            agent: '',
            bulk_actions_list: ['Line & Agent', 'Assign', 'Vip level', 'Category', 'Full name', 'Phone', 'Email', 'DOB', 'Status', 'Call date', 'Note', 'User name'],
            bulk_val: '',
            bulkEditLine: '',
            bulkEditLineAgent: '',
            bulkEditAgent: '',
            agentListBulk: [],
            vip_id: '',
            filterVipId: this.$route.query.vip_id ? this.$route.query.vip_id : '',
            category_id: '',
            filterCategoryId: this.$route.query.category_id ? this.$route.query.category_id : '',
            dialog: false,
            openFilter: false,
            bulkEditFieldVal: '',
            filter_all: '',
            listSearchFilter: [],
            filterNote: '',
            source: '',
            filterSource: '',
            device: null,
            calling: false,
            inputColor: 'red',
            inputWidth: '0px',
            outputColor: 'red',
            outputWidth: '0px',
            call_dialog: false,
            personToCall: '',
            numberToCall: '',
            phoneFieldArr: [1, 2, 3, 4, 5, 6, 7, 8 , 9, 0],
            countryCode: '+84',
            callStatus: '',
            callMute: false,
            outgoingConnection: null,
            phoneTab: 1,
            agentToCall: '',
            incommingIdentity: '',
            incomming_dialog: false,
            outboundCall: true
        }
    },
    computed: {
        ...mapGetters('teleSale', {
            teleList: 'getTeleSaleList',
            lineList: 'getTeleSaleLineList',
            teleUser: 'getTeleSaleUser',
            pagination: 'getTeleSalePagination',
            vipList: 'getTeleSaleVip',
            cateogryList: 'getTeleSaleCategory',
            agentList: 'getTeleSaleAgentListLog',
            listSource: 'getTeleSourceFrom'
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
        lineListBulk () {
            return [{ id: 'null', name: 'Empty' } , ...this.lineList]
        },
        headerFilter () {
            const header = this.headers.filter(item => this.checkboxSelected.includes(item.value));
            if (this.canEmail) return header;
            return header.filter(ite => ite.value !== 'email');
        },
        canClearFilter () {
            return this.filterEmail !== '' || this.filterStatus !== '' || this.filterDob !== '' || this.filterCallDate !== '' || this.filterPhone !== '' || this.filterUserName !== '' || this.filterLineId !== '' || this.filterAssignId !== '' || this.filterAgent!== '' || this.filterCallDate !== '' || this.filterFullName || this.dup === true || this.filterVipId !== '' || this.filterCategoryId !== ''
        },
        canEmail () {
            return this.checkPermision('DataEmailController@index')
        },
        vipListF () {
            return [{ id: 'null', name: 'Empty' } , ...this.vipList]
        },
        cateogryListF () {
            return [{ id: 'null', name: 'Empty' } , ...this.cateogryList]
        },
        agentLists () {
            if (this.userInfo.name && this.teleUser.length) {
                const agents = this.teleUser.filter(user => user.name !== this.userInfo.name);
                return agents;
            }
            return []
        },
        isPhoneAllow () {
            if (this.userInfo.roles && this.userInfo.roles.length) {

                let check = false;

                const allow = ['Admin', 'Developer'];

                for (let i = 0; i < this.userInfo.roles.length; i++) {
                    const role = this.userInfo.roles[i];
                    if (allow.includes(role.name)) {
                        check = true;
                    }
                }

                return check;
            }
            return false;
        }
    },
    watch: {
        filterLineId (val) {
            if (!val) {
                this.agentListF =  [];
            }
            else {
                this.getFilterListAgent()
            }
        },
        bulkEditLineAgent (val) {
            if (!val) {
                this.agentListBulk =  [];
            }
            else {
                this.getFilterListBulkAgent()
            }
        },
        userInfo (val) {
            if (val.name) {
                this.getCallToken({ identity: val.name });
            }
        },
        device (val) {
            if(val) {
                this.addDeviceListener();
            }
        }
    },
    created () {
        this.loadPageData();
        // console.log(Device)
        if (this.userInfo.name) {
            this.getCallToken({ identity: this.userInfo.name });
        }

    },

    methods: {
        ...mapActions('teleSale', ['getTeleSaleList', 'updateTeleSale', 'getTeleSaleLine', 'getTeleSaleUser', 'getDuplicate', 'updateAssignToUser', 'deleteTelesales', 'importTeleSale', 'getTeleSaleLineOwn', 'updateLine', 'updateAgent', 'getListAgentFilter', 'bulkEditAction', 'getTeleSaleVip', 'getTeleSaleCategory', 'bulkActionUpdate', 'getTeleSourceFrom', 'getVoiceToken', 'saveCallRecord']),
        async loadPageData (type = 'init') {
            const func = { init: 'getTeleSaleList', dup: 'getDuplicate' }
            try {
                this.loading = true;
                await Promise.all([
                    this[func[type]]({
                        status: this.filterStatus,
                        assign_id: this.filterAssignId,
                        line_id: this.filterLineId,
                        full_name: this.filterFullName,
                        phone: this.filterPhone,
                        email: this.filterEmail,
                        username: this.filterUserName,
                        agent_id: this.filterAgent,
                        per_page: this.per_page,
                        page: this.currentPage,
                        call_date_time: this.filterCallDate,
                        vip_id: this.filterVipId,
                        category_id: this.filterCategoryId,
                        filter_all: this.filter_all,
                        note: this.filterNote,
                        source: this.filterSource,
                        dup: this.dup
                    }),
                    this.getTeleSaleLineOwn(),
                    this.getTeleSaleUser(),
                    this.getTeleSaleVip(),
                    this.getTeleSaleCategory(),
                    this.getListAgentFilter(),
                    this.getTeleSourceFrom()
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
        async getFilterListAgent () {
            const data = { id: this.filterLineId };
            try {
                const resp = await this.getListAgentFilter(data)
                const newListFilter = [{ id: 'null', name: 'Empty' }, ...resp];
                this.agentListF = newListFilter;
            } catch (err) {
                console.log(err)
            }
        },
        async getFilterListBulkAgent () {
            const data = { id: this.bulkEditLineAgent };
            try {
                const resp = await this.getListAgentFilter(data)
                const newListFilter = [{ id: 'null', name: 'Empty' }, ...resp];
                this.agentListBulk = newListFilter;
            } catch (err) {
                console.log(err)
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
                    status: this.filterStatus,
                    assign_id: this.filterAssignId,
                    line_id: this.filterLineId,
                    full_name: this.filterFullName,
                    phone: this.filterPhone,
                    email: this.filterEmail,
                    username: this.filterUserName,
                    agent_id: this.filterAgent,
                    per_page: this.per_page,
                    page: this.currentPage,
                    call_date_time: this.filterCallDate,
                    dup: this.dup,
                    vip_id: this.filterVipId,
                    category_id: this.filterCategoryId,
                    filter_all: this.filter_all,
                    note: this.filterNote,
                    source: this.filterSource,
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
                    this[index] = item.agent_l && item.agent_l.id ? item.agent_l.id : '';
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
                    this.loadTeleSaleList();
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
            this.agent_id = '';
            this.agent = '';
            this.call_date_time = '';
            this.vip_id = '';
            this.category_id = '';
            this.filter_all = '';
            this.note = '';
            this.source = '';
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
                    this.resetPageData();
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
                if (!this.fieldVal) {
                    alert('Pls check input data');
                    return;
                }
                this.filter_all = this.fieldVal;
            }
            else {
                this[this.fieldName] = this.fieldVal ? this.fieldVal : 'null';
            }

            this.loadTeleSaleList();
            this.createListSearchFilter();
            this.fieldVal = '';
            this.fieldName = '';
        },
        handleCheckDuplicate () {
            this.dup = true;
            this.currentPage = 1;
            this.loadPageData();
        },
        handleClearFilter () {
            this.filterCallDate = '';
            this.filterStatus= '';
            this.filterEmail = '';
            this.filterPhone = '';
            this.filterAssignId = '';
            this.filterUserName = '';
            this.filterAgent = '';
            this.fieldName = '';
            this.fieldVal = '';
            this.filterFullName = '';
            this.filterLineId = '';
            this.filterVipId = '';
            this.filterCategoryId = '';
            this.filterNote = '';
            this.filter_all = '';
            this.filterSource = '';
            this.dup = false;
            this.createListSearchFilter();
            this.loadTeleSaleList();
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
        },
        async saveBulkEditAgent () {
            if (!this.bulkEditLineAgent || this.bulkEditLineAgent === '') {
                alert('Line value can not be empty');
                return false;
            }
            try {
                const ids = this.selected.map(item => item.id);
                await this.bulkEditAction({ line: this.bulkEditLineAgent, agent: this.bulkEditAgent, ids });
                this.bulkEditLineAgent = '';
                this.bulkEditAgent = ''
                this.agentListBulk = [];
                this.bulk_val = null;
                this.selected = [];
                this.loadTeleSaleList();
            } catch (err) {
                console.log(err)
            }
        },
        async saveBulkEditCommon () {
            try {
                const mapFiled = { 'Full name': 'full_name', 'Phone' : 'phone', 'Email': 'email', 'DOB': 'dob', 'Status': 'status', 'Call date': 'call_date', 'Note': 'note', 'User name': 'username', 'Vip level': 'vip_id', 'Category': 'category_id', 'Assign': 'assign_id' };
                const ids = this.selected.map(item => item.id);
                const fieldName = mapFiled[this.bulk_val];
                const data = { update_field: fieldName, value: this.bulkEditFieldVal === 'null' ?  null : this.bulkEditFieldVal, ids };
                await this.bulkActionUpdate(data);
                this.loadTeleSaleList();
                this.bulkEditFieldVal = '';
                this.selected = [];
                this.bulk_val = '';
            } catch (err) {
                alert('Có lỗi xảy ra, vui lòng kiểm tra lại input data')
                console.log(err)
            }
        },
        createListSearchFilter() {
            const searchFilter =  [
                { name: 'Full name', value: this.filterFullName, index: 'filterFullName', color: 'red' },
                { name: 'Phone', value: this.filterPhone, index: 'filterPhone', color: 'green' },
                { name: 'Email', value: this.filterEmail, index: 'filterEmail', color: 'blue' },
                { name: 'User Name', value: this.filterUserName, index: 'filterUserName', color: 'purple' },
                { name: 'Note', value: this.filterNote, index: 'filterNote', color: 'indigo' }
            ];
            this.listSearchFilter = searchFilter;
        },
        removeSearchFilter (index) {
            this[index] = '';
            this.createListSearchFilter();
            this.loadTeleSaleList();
        },
        async getCallToken(params) {
            try {
                const resp = await this.getVoiceToken(params);
                this.device = new Device(resp.token,  {enableRingingState: true})
            } catch (err) {
                console.log(err);
            }
        },
        bindVolumn (connection) {
            connection.volume((inputVolume, outputVolume) => {
                if (inputVolume < .50) {
                    this.inputColor = 'green';
                } else if (inputVolume < .75) {
                    this.inputColor = 'yellow';
                }
                this.inputWidth = Math.floor(inputVolume * 300) + 'px';

                if (outputVolume < .50) {
                    this.outputColor = 'green';
                } else if (outputVolume < .75) {
                    this.outputColor = 'yellow';
                }
                this.outputWidth = Math.floor(outputVolume * 300) + 'px';
            });
        },
        handleOpenCallDialog (item) {
            this.editItem = item;
            this.personToCall = item.full_name;
            this.numberToCall = item.phone;
            this.call_dialog = true;
            this.handleFocusInput('phoneInput');
        },
        handleFocusInput (key) {
            setTimeout(() => {
                const input = this.$refs[key];
                input.focus();
            }, 300)
        },
        handleCall (type) {

            if (Object.keys(this.editItem).length) {
                const date = new Date();
                const dateStr =  date.getFullYear()  + '-' + ((date.getMonth() + 1).toString()).padStart(2, '0') + '-' + (date.getDate().toString()).padStart(2, '0') + ' ' + (date.getHours().toString()).padStart(2, '0') + ':' + (date.getMinutes().toString()).padStart(2, '0');
                this.call_date = dateStr;
                this.handleSaveItem('call_date');
            }
            var params = {
                To: type === 'outbound' ? this.countryCode +  this.numberToCall : this.agentToCall,
                type: type,
                identity: this.userInfo.name
            };

            this.outgoingConnection  = this.device.connect(params);
        },
        handleHangup () {
            this.device.disconnectAll();
        },
        handlDialNumber (number) {
            this.numberToCall = this.numberToCall + number;
            this.playSound();
        },
        playSound() {
            const audio = new Audio('/sound/phone_press.mp3');
            audio.play();
        },
        handleBackSpace () {
            if (this.numberToCall) {
                this.numberToCall = this.numberToCall.slice(0, -1);
            }
        },
        addDeviceListener () {

            this.device.on("error", function (error) {
                console.log(error.message);
            });

            this.device.on("connect", (conn) => {
                // console.log('connect', conn)
                this.calling = true;
                this.callMute = false;
                this.bindVolumn(conn);
            });

            this.device.on("disconnect", (conn) => {
                console.log("Call ended.")
                this.calling = false;
                // console.log(conn)
                // this.saveCallRecord({ call_id: conn.parameters.CallSid })

            });
            this.device.on("incoming", (conn) => {

                this.outgoingConnection = conn;

                this.outboundCall = false;

                conn.customParameters.forEach((val,key) => {
                    if(key == "identity") {
                        this.incommingIdentity = val;
                        this.incomming_dialog = true;
                    }
                });

            });
        },
        handleToggleMute () {
            if (this.device && this.outgoingConnection) {
                this.callMute = !this.callMute;
                this.outgoingConnection.mute(this.callMute);
            }
        },
        handleClosePhoneDialog () {
            this.call_dialog = false;
            this.calling = false;
            this.callMute = false;
            this.numberToCall = '';
            if (this.device && this.outgoingConnection) {
                this.device.disconnectAll();
            }
        },
        handleInComingCall (type) {
            if (type === 'accept') {
                this.outgoingConnection.accept();
                this.incomming_dialog = false;
                this.call_dialog = true;
                // this.calling = true;
            }
            if (type === 'reject') {
                this.outgoingConnection.reject();
                this.incommingIdentity = '';
                this.incomming_dialog = false;
            }
        }
    }
}
</script>

<style scoped>
.phone-pad {
    display: flex;
    flex-wrap: wrap;
}
.phone-number {
    flex: 1;
    min-width: 26% !important;
    margin: 5px;
    padding: 25px !important;
    font-size: 20px;
}
.phone-back {
    margin: 5px;
    padding: 25px !important;
}
.call-number {
    display: grid;
    grid-template-columns: 2fr 8fr;
    grid-gap: 8px;
}
input.number-to-call {
    color: #fff;
    font-size: 25px;
    outline: none;
    width: 100%;
    padding: 5px;
}
</style>
