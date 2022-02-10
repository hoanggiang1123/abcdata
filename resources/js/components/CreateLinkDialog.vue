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
                    <v-text-field v-model="description" label="Description" />
                    <v-row>
                        <v-col cols="9">
                            <v-text-field label="Image" hide-details v-model="image" single-line />
                        </v-col>
                        <v-col cols="3">
                            <input type="file" name="image" hidden ref="uploadFile" @change="handleUploadFile" />
                            <v-btn color="accent" block @click="handleOpenUpload">Upload <v-icon right>mdi-cloud-upload</v-icon></v-btn>
                        </v-col>
                        <v-col v-if="image" cols="12">
                            <div style="height: 200px; width: auto;">
                                <img :src="image" height="100%">
                            </div>
                        </v-col>
                        <template>
                            <v-col cols="6">
                                <v-autocomplete
                                    v-model="link_directory_id"
                                    :items="categoryList"
                                    label="Category"
                                    item-text="name"
                                    item-value="id"
                                    class="mt-4"
                                    :disabled="Object.keys(category).length && category.id ? true : false"
                                >
                                </v-autocomplete>
                            </v-col>
                        </template>
                        <v-col cols="6">
                            <v-autocomplete
                                    v-model="position"
                                    :items="positionList"
                                    label="Position"
                                    class="mt-4"
                                >
                                </v-autocomplete>
                        </v-col>
                    </v-row>
                    <v-switch label="Status" :input-value="status" @change="handleSwitch"></v-switch>
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
        <Media :openMedia="openMedia" @close-media="handleCloseMedia" />
    </v-dialog>
</template>

<script>
import { mapActions } from 'vuex';
import Media from './Media.vue';
export default {
    props: {
        openCreateDialog: {
            type: Boolean,
            default: false
        },
        categoryList: {
            type: Array,
            default: () =>[]
        },
        category: {
            type: Object,
            default: () => {}
        }
    },
    components: {
        Media
    },
    data () {
        return {
            dialog: false,
            link: '',
            description: '',
            status: 1,
            image: '',
            link_directory_id: '',
            position: '',
            positionList: ['middle_left', 'middle_right', 'bottom_left', 'bottom_right'],
            openMedia: false
        }
    },
    watch: {
        openCreateDialog (val) {
            if (val) {
                this.dialog = true;
            } else {
                this.status = 1;
                this.link = '';
                this.description = '';
                this.image = '';
                this.dialog = false;
                this.link_directory_id = '';
                this.position = '';
            }
        },
        category (val) {
            if (val.id) {
                this.link_directory_id = val.id;
            }
        }
  },
    methods: {
        ...mapActions('link', ['createLink', 'uploadImage']),
        handleSwitch () {
            const status = this.status === 0 ? 1 : 0;
            this.feature = status;
        },
        handleCreateLink () {
            if (this.link.trim() === '' || this.image.trim() === '' || !this.link_directory_id || !this.position) {
                alert('Link and Image and Category, Position is Required');
                return;
            };
            const data = {
                link: this.link,
                image: this.image,
                status: this.status,
                description: this.description,
                link_directory_id: this.link_directory_id,
                position: this.position
            }
            this.createLink(data)
                .then(resp => {
                    console.log(resp);
                    this.$emit('close-create-dialog', true);
                })
                .catch(err => {
                    console.log(err);
                })
        },
        handleOpenUpload () {
            // this.$refs.uploadFile.click();
            this.openMedia = true;
        },
        handleUploadFile () {
            const file = this.$refs.uploadFile.files[0];
            if (file) {
                const data = new FormData();
                data.append('image', file);
                this.uploadImage(data)
                    .then(resp => {
                       this.image = resp.image
                    })
                    .catch(err => console.log(err))
            }
        },
        handleCloseMedia (item) {
            this.openMedia = false;
            this.link = item.link;
            this.image = item.path;
        }
    }
}
</script>

<style>

</style>
