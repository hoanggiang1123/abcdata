<template>
    <v-dialog
        max-width="900px"
        v-model="dialog"
        persistent
    >
        <v-card>
            <v-card-title>
                Create user
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleCreateUser">
                    <v-text-field label="Name" v-model="name"></v-text-field>
                    <v-text-field label="User Name" v-model="username"></v-text-field>
                    <v-text-field label="Email" v-model="email"></v-text-field>
                    <v-text-field label="Password" v-model="password"></v-text-field>
                    <v-row>
                        <v-col cols="4" v-for="rol in roles" :key="rol.id">
                            <v-checkbox
                                v-model="role_ids"
                                :label="rol.name"
                                color="red"
                                :value="rol.id"
                                hide-details
                                >
                            </v-checkbox>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-btn color="primary" block type="submit">Save</v-btn>
                        </v-col>
                        <v-col>
                            <v-btn outlined color="warning" block @click="$emit('close-create-dialog')">Cancel</v-btn>
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
    props: ['roles', 'openCreateDialog'],
    data () {
        return {
            name: '',
            email: '',
            username: '',
            password: '',
            role_ids: [],
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
                this.email = '';
                this.password = '';
                this.role_ids = '';
            }
        }
    },
    methods: {
        ...mapActions('user', ['createUser']),
        handleCreateUser () {
            if (this.username === '' || this.username.trim() === '' || this.email === '' || this.email.trim() === '' || this.password === '' || this.password.trim() === '') {
                alert('username, email and passowrd are requied');
                return false;
            }

            const data = {
                name: this.name,
                email: this.email,
                username: this.username,
                password: this.password,
                role_ids: this.role_ids
            }

            this.createUser(data)
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
