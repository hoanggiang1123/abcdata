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
                    <v-text-field label="Keyword" v-model="name" />
                    <v-text-field label="Content" v-model="content" />
                    <v-text-field label="Appearable Time" v-model="appearable_time" type="number"></v-text-field>
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
            content: '',
            appearable_time: 0
        }
    },
    watch: {
        openCreateDialog (val) {
            if (val) {
                this.dialog = true;
            } else {
                this.dialog = false;
                this.name = '';
                this.content = '';
                this.appearable_time = 0;
            }
        }
    },
    methods: {
        ...mapActions('keyword', ['createKeyword']),
        handleCreateKeyword () {
            if (!this.name || this.name.trim() === '' ||  !this.content || this.content.trim() === '') {
                alert('Name and Content is required');
                return false;
            }

            const data = {
                name: this.name,
                content: this.content,
                appearable_time: this.appearable_time
            }

            this.createKeyword(data)
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
