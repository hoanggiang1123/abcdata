<template>
    <v-dialog
        max-width="900px"
        v-model="dialog"
        persistent
    >
        <v-card>
            <v-card-title>
                Create Popup
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleCreatePost">
                    <v-text-field label="Domain" v-model="domain" />
                    <v-text-field label="Name" v-model="name" />
                    <v-text-field label="Content" v-model="content" />
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
            domain: '',
            content: '',
            name: ''
        }
    },
    watch: {
        openCreateDialog (val) {
            if (val) {
                this.dialog = true;
            } else {
                this.dialog = false;
                this.domain = '';
                this.content = '';
                this.name = '';
            }
        }
    },
    methods: {
        ...mapActions('popup', ['createPopup']),
        handleCreatePost () {
            if (!this.domain || this.domain.trim() === '' ||  !this.content || this.content.trim() === '') {
                alert('Domain and Content is required');
                return false;
            }

            const data = {
                domain: this.domain,
                content: this.content,
                name: this.name
            }

            this.createPopup(data)
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
