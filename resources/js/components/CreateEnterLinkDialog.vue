<template>
    <v-dialog
        max-width="680px"
        v-model="dialog"
        persistent
    >
        <v-card>
            <v-card-title>
                Create New Link
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleCreateLink">
                    <v-text-field v-model="link" label="Link" />
                    <v-text-field v-model="name" label="Name" />
                    <v-switch label="Feature" :input-value="feature" @change="handleSwitch"></v-switch>
                    <v-autocomplete
                        v-model="folder_id"
                        :items="folderList"
                        label="Folder"
                        item-text="name"
                        item-value="id"
                        class="mt-4"
                    >
                    </v-autocomplete>
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
        },
        folderList: {
            type: Array,
            default: () =>[]
        }
    },
    data () {
        return {
            dialog: false,
            link: '',
            name: '',
            feature: 1,
            folder_id: ''
        }
    },
    watch: {
        openCreateDialog (val) {
            if (val) {
                this.dialog = true;
            } else {
                this.feature = 1;
                this.link = '';
                this.name = '';
                this.dialog = false;
                this.folder_id = '';
            }
        }
    },
    methods: {
        ...mapActions('enterLink', ['createEnterLink']),
        handleSwitch () {
            const feature = this.feature === 0 ? 1 : 0;
            this.feature = feature;
        },
        handleCreateLink () {
            if (this.link.trim() === '' || !this.folder_id || !this.name) {
                alert('Link and Name and Folder is Required');
                return;
            };
            const data = {
                link: this.link,
                feature: this.feature,
                name: this.name,
                folder_id: this.folder_id
            }
            this.createEnterLink(data)
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
