<template>
    <v-dialog
        max-width="900px"
        v-model="dialog"
        persistent
    >
        <v-card>
            <v-card-title>
                Create Record
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleCreateTele">
                    <v-row>
                        <v-col cols="6">
                            <v-text-field label="Full Name" v-model="full_name" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Phone" v-model="phone" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Email" v-model="email" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="DOB" v-model="dob" placeholder="yyyy-mm-dd" />
                        </v-col>
                        <v-col cols="6">
                            <v-autocomplete
                                :items="statuses"
                                v-model="status"
                                item-text="name"
                                item-value="value"
                                label="Status"
                            />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Note" v-model="note" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="User Name" v-model="username" />
                        </v-col>
                        <v-col cols="6">
                           <v-autocomplete
                                :items="categories"
                                v-model="category_id"
                                item-text="name"
                                item-value="id"
                                label="Category"
                            />
                        </v-col>
                        <v-col cols="6">
                            <v-autocomplete
                                :items="lines"
                                v-model="line_id"
                                item-text="name"
                                item-value="id"
                                label="Line"
                                @change="handleChangeLine"
                            />
                        </v-col>
                        <v-col cols="6">
                            <v-autocomplete
                                v-if="agents.length"
                                :items="agents"
                                v-model="agent_id"
                                item-text="name"
                                item-value="id"
                                label="Agent"
                            />
                        </v-col>
                        <v-col cols="12">
                           <v-autocomplete
                                :items="vips"
                                v-model="vip_id"
                                item-text="name"
                                item-value="id"
                                label="Vip level"
                            />
                        </v-col>
                        <v-col cols="6">
                            <v-btn block color="warning" type="submit">Save</v-btn>
                        </v-col>
                        <v-col cols="6">
                            <v-btn color="pink" block @click="$emit('close-create-dialog')">Cancel</v-btn>
                        </v-col>
                    </v-row>

                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions } from 'vuex';
export default {
    props: ['statuses', 'openCreateDialog', 'lines', 'vips', 'categories'],
    data () {
        return {
            full_name: '',
            phone: '',
            email: '',
            dob: '',
            status: 11,
            note: '',
            username: '',
            line_id: '',
            agent_id: '',
            category_id: '',
            vip_id: '',
            dialog: false,
            agents: []

        }
    },

    watch: {
        openCreateDialog (val) {
            if (val) {
                this.dialog = true;
            } else {
                this.dialog = false;
                this.full_name = '';
                this.email = '';
                this.phone = '';
                this.dob = '';
                this.status = 11;
                this.line_id = '';
                this.agent = '';
                this.username = '';
                this.vip_id = '';
                this.category_id = '';
                this.agents = [];
                this.agent_id = '';
            }
        }
    },
    methods: {
        ...mapActions('teleSale', ['createTeleSale', 'getListAgentFilter']),
        handleCreateTele () {
            if (this.full_name === '' || this.full_name.trim() === '' || this.phone === '' || this.phone.trim() === '' || this.status === '') {
                alert('Full Name, phone, status are requied');
                return false;
            }

            const data = {
                full_name: this.full_name,
                email: this.email,
                phone: this.phone,
                dob: this.dob,
                status: this.status,
                line_id: this.line_id,
                agent_id: this.agent_id,
                category_id: this.category_id,
                vip_id: this.vip_id,
                username: this.username,
                note: this.note
            }

            this.createTeleSale(data)
                .then(resp => {
                    this.$emit('close-create-dialog', true);
                })
                .catch (err => {
                    alert(err.response && err.response.data && err.response.data.message ? err.response.data.message : 'err');
                })
        },
        async handleChangeLine (val) {
            if (val) {
                try {
                    const resp = await this.getListAgentFilter({ id: val });
                    this.agents = resp;
                } catch (err) {
                    console.log(err)
                }
            } else {
                this.agents = []
            }
        }
    }
}
</script>

<style>

</style>
