<template>
    <v-dialog
        max-width="900px"
        v-model="dialog"
        persistent
    >
        <v-card>
            <v-card-title>
                Create Post
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleCreateKeyword">
                    <v-text-field label="Name" v-model="name" />
                    <v-text-field label="Description" v-model="description" />
                    <v-row>
                        <v-col>
                            <v-btn color="primary" type="submit" block>Save</v-btn>
                        </v-col>
                        <v-col>
                            <v-btn color="warning" block @click="$emit('close-create-dialog')">Cancel</v-btn>
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
            dialog: false,
            name: '',
            description: '',
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
        ...mapActions('keyword', ['createSatiliteDomain']),
        handleCreateKeyword () {
            if (!this.name || this.name.trim() === '') {
                alert('Name is required');
                return false;
            }

            const data = {
                name: this.name,
                description: this.description
            }

            this.createSatiliteDomain(data)
                .then(resp => {
                    console.log(resp);
                    this.$emit('close-create-dialog', true);
                })
                .catch(err => {
                    console.log(err);
                })
        }
    }
}
</script>

<style>

</style>
