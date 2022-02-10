<template>
    <v-dialog
        max-width="680px"
        v-model="dialog"
        persistent
    >
        <v-card>
            <v-card-title>
                Create New Folder
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleCreateCategory">
                    <v-text-field v-model="name" label="Name" />
                    <v-text-field v-model="description" label="Description" />
                    <v-row>
                        <v-col>
                            <v-btn color="primary" block type="submit">Save</v-btn>
                        </v-col>
                        <v-col>
                            <v-btn color="warning" block @click="$emit('close-create-dialog', false)">Cancel</v-btn>
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
    props: {
        openCreateDialog: {
            type: Boolean,
            default: false
        }
    },
    data () {
        return {
            dialog: false,
            name: '',
            description: ''
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
        ...mapActions('enterLink', ['createFolder']),
        handleCreateCategory () {
            if (this.name.trim() === '' || this.description.trim() === '') {
                alert('Name and Description is Required');
                return;
            };
            const data = {
                name: this.name,
                description: this.description
            }
            this.createFolder(data)
                .then(resp => {
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
