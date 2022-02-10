<template>
    <v-dialog
        max-width="900px"
        v-model="dialog"
        persistent
    >
        <v-card>
            <v-card-title>
                Create Plugin Repo
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleCreatePluginRepo">
                    <v-row>
                        <v-col cols="6">
                            <v-text-field label="Name" v-model="name" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Slug" v-model="slug" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Author" v-model="author" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Author Profile" v-model="author_profile" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Version" v-model="version" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Requires" v-model="requires" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Require php" v-model="requires_php" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Tested" v-model="tested" />
                        </v-col>
                        <v-col cols="6">
                            <v-text-field label="Download" v-model="download_url" />
                        </v-col>
                        <v-col cols="6">
                            <input type="file" hidden ref="pluginFile" @change="handleUploadPlugin" />
                            <v-btn color="pink" @click="$refs.pluginFile.click()"><v-icon left>mdi-cloud</v-icon> Upload</v-btn>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-btn color="primary" block type="submit">Save</v-btn>
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
    props: ['permisions', 'openCreateDialog'],
    data () {
        return {
            dialog: false,
            name: '',
            slug: '',
            author: '',
            author_profile: '',
            version: '',
            download_url: '',
            requires: '',
            tested: '',
            requires_php: ''
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
        ...mapActions('pluginRepo', ['createPluginRepo', 'uploadPluginFile']),
        handleCreatePluginRepo () {
            if (this.name === '' || this.slug === '' || this.version === '' || this.download_url === '' || this.requires === '') {
                alert('Name, Slug, Version, Download url and require can not be empty');
                return false;
            }
            const data = {
                name: this.name,
                slug: this.slug,
                author: this.author,
                author_profile: this.author_profile,
                version: this.version,
                download_url: this.download_url,
                requires: this.requires,
                tested: this.tested,
                requires_php: this.requires_php
            }

            this.createPluginRepo(data)
                .then(resp => {
                    console.log(resp)
                    this.$emit('close-create-dialog', true);
                })
                .catch (err => {
                    console.log(err)
                })
        },
        handleUploadPlugin () {
            const plugin = this.$refs.pluginFile.files[0];
            const testzip = ((plugin.name).split('.')).pop();
            if (testzip !== 'zip') {
                alert('Only Zip file allowed');
                return false;
            }
            const data = new FormData();
            data.append('plugin', plugin);

            this.uploadPluginFile(data)
                .then (resp => {
                    this.download_url = resp;
                })
                .catch(err => {
                    console.log(err)
                })
        }
    }
}
</script>

<style>

</style>
