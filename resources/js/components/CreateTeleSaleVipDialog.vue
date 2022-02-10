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
                <v-form @submit.prevent="handleCreateTeleSaleAgent">
                    <v-row>

                        <v-col cols="12">
                            <v-text-field label="Name" v-model="name" />
                        </v-col>
                        <v-col cols="12">
                            <v-text-field label="Description" v-model="description" />
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
    props: ['openCreateDialog'],
    data () {
        return {
            name: '',
            description: '',
            dialog: false
        }
    },
    watch: {
        openCreateDialog (val) {
            if (val) {
                this.dialog = true;
            } else {
                this.dialog = false;
                this.name = '';
                this.description = '';
            }
        }
    },
    methods: {
        ...mapActions('teleSale', ['createTeleSaleVip']),
        handleCreateTeleSaleAgent () {
            if (this.name === '' || this.name.trim() === '') {
                alert('Name are requied');
                return false;
            }

            const data = {
                name: this.name,
                description: this.description
            }

            this.createTeleSaleVip(data)
                .then(resp => {
                    this.$emit('close-create-dialog', true);
                })
                .catch (err => {
                    alert(err.response && err.response.data && err.response.data.message ? err.response.data.message : 'err');
                })
        }
    }
}
</script>

<style>

</style>
