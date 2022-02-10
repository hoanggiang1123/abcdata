<template>
    <v-dialog v-model="dialog" persistent>
        <v-card>
            <v-toolbar>
                <v-toolbar-title
                    class="d-flex justify-center"
                    style="width: 300px;"
                >
                    Media Library
                </v-toolbar-title>
                <v-divider vertical class="mx-2" />
                <v-tabs v-model="activeTab">
                    <v-tab v-for="tab in tabs" :key="tab.index" >
                        {{ tab.name }}
                    </v-tab>
                </v-tabs>
                <v-spacer></v-spacer>
                <v-btn color="warning" icon @click="$emit('close-media')"><v-icon>mdi-close</v-icon></v-btn>
            </v-toolbar>
            <v-card-text class="mt-2" v-show="activeTab === 0">
                <div class="upload d-flex align-center justify-center pa-4">
                    <v-sheet
                        color="info"
                        elevation="10"
                        style="width: 90%; max-width: 600px; min-height: 400px; cursor: pointer; border-radius: 5px;"
                        class="d-flex flex-column align-center justify-center"
                        @click="handleOpenUploadFile"
                    >
                        <v-icon size="100">mdi-cloud-upload</v-icon>
                        <div class="text-h6">Upload Images</div>
                        <input
                            type="file"
                            name="image"
                            multiple
                            hidden
                            ref="image"
                            @change="handleUploadFile"
                        />
                    </v-sheet>
                </div>
                <div class="upload-progress mt-4" v-if="previewImages.length">
                    <v-container>
                        <v-row>
                            <v-col
                                cols="12"
                                sm="8"
                                md="6"
                                lg="3"
                                v-for="(preview, index) in previewImages"
                                :key="index"
                            >
                                <div class="preview-pic">
                                    <v-img :src="preview.base64Url" alt="" />
                                    <div class="progress">
                                        <div
                                            class="percent"
                                            :style="
                                                `width: ${preview.uploadProgress}%`
                                            "
                                        ></div>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </v-card-text>
            <v-card-text class="mt-2" v-show="activeTab === 1">
                <v-row v-if="medias">
                    <v-col :cols="`${Object.keys(editItem).length ? 9: 12 }`">
                        <div class="media-list" ref="mediaList">
                            <div class="media-item" v-for="media in mediaList" :key="media.id" :class="{active: media.id === editItem.id}" @click="editItem = media">
                                <div :style="`background-image: url(${media.image_url}); background-size:cover; background-position: center; width: 100%; height: 100%;`"></div>
                                <div class="check" v-if="media.id === editItem.id">
                                    <v-icon color="danger">mdi-check-underline</v-icon>
                                </div>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="3" v-if="Object.keys(editItem).length">
                        <div class="media-edit d-flex flex-column" style="height: 80vh;">
                            <v-card style="margin-bottom: auto;">
                                <v-card-title>
                                    <v-icon left class="spinning" v-if="loading">mdi-loading</v-icon>
                                    Edit Image Info
                                </v-card-title>
                                <v-card-text>
                                    <div class="peview" style="width: 100%; margin-bottom: 20px; height: 200px">
                                        <img :src="editItem.path" alt="" style="max-width: 100%; max-height: 100%;">
                                    </div>
                                    <div class="mb-2" style="width: 100%">
                                        <v-btn color="red" small outlined @click="handleDeleteMedia(editItem.id)"><v-icon left>mdi-delete</v-icon>Delete</v-btn>
                                    </div>
                                    <div class="date d-flex flex-column">
                                        <p style="font-style: italic">Created: {{ editItem.created_at | datetime }}</p>
                                        <p style="font-style: italic">Updated: {{ editItem.updated_at | datetime}}</p>
                                        <p style="font-style: italic">Link: {{ editItem.image_url }}</p>
                                    </div>
                                    <v-btn color="pink" class="mb-4" v-clipboard="editItem.image_url"><v-icon left>mdi-content-copy</v-icon>Copy link</v-btn>
                                    <v-text-field label="Link" v-model="link" outlined dense @focus="focus = true" @blur=
                                        handleEditImage />
                                    <v-text-field label="Name" v-model="name" outlined dense @focus="focus = true" @blur=
                                    handleEditImage />
                                    <v-text-field label="Alt" v-model="alt" outlined dense @focus="focus = true" @blur=
                                    handleEditImage />
                                </v-card-text>
                            </v-card>
                            <div class="action">
                                <v-btn color="success" class="ma-2" @click="handleInsertImage()" :disabled="focus">Insert</v-btn>
                                <v-btn color="warning" class="ma-2" @click="$emit('close-media')">Cancel</v-btn>
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import api from '../api';
export default {
    props: ['openMedia'],
    components: {},
    data() {
        return {
            dialog: false,
            tab: null,
            activeTab: 1,
            tabs: [
                { index: 0, name: 'Upload' },
                { index: 1, name: 'Library' }
            ],
            images: [],
            loadCount: 0,
            per_page: 30,
            mediaList: [],
            editItem: {},
            link: '',
            alt: '',
            name: '',
            focus: false,
            loading: false,
            page: 1
        };
    },
    computed: {
        ...mapGetters('media', {
            medias: 'getMediaList',
            pagination: 'getPaination'
        }),
        previewImages() {
            const images = [...this.images];
            images.map(img => {
                if (img.base64Url) {
                    return {
                        base64Url: img.base64Url,
                        uploadProgress: img.uploadProgress
                            ? img.uploadProgress
                            : 0
                    };
                }
            });
            return images;
        }
    },
    watch: {
        openMedia (val) {
            if (val) {
                this.dialog = val;
                this.editItem = {};
                this.loadMediaList();
                setTimeout(() => {
                    this.addScrollListener();
                }, 1000)
            } else {
                this.resetData();
                this.removeListener();
            }

        },
        editItem (val) {
            if (val.id) {
                this.link = val.link ? val.link : '';
                this.name = val.name ? val.name : '';
                this.alt = val.alt ? val.alt : ''
            }
        }
    },
    created () {
        // this.loadMediaList();
    },

    methods: {
        ...mapActions('media', ['uploadMedia', 'getMediaList', 'editMedia', 'deleteMedia']),
        handleOpenUploadFile() {
            this.$refs.image.click();
        },
        async loadMediaList () {
            try {
                const resp = await this.getMediaList({ per_page: this.per_page, page: this.page });
                this.mediaList = [...this.mediaList, ...resp.data]
            } catch (err) {
                console.log(err)
            }
        },
        handleUploadFile() {
            if (this.$refs.image.files) {
                const images = [...this.$refs.image.files];
                this.images = images.map(item => ({
                    file: item,
                    base64Url: "",
                    uploadProgress: ""
                }));

                for (let i = 0; i < this.images.length; i++) {
                    const file = this.images[i].file;
                    this.previewImage(file, i);
                    this.processUploadFile(file, i);
                }
            }
        },
        processUploadFile(image, i) {
            const data = new FormData();
            data.append("image", image);
            api.media.uploadMedia(data, {
                headers: {
                'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: ({ loaded, total }) => {
                    if (total === loaded) this.loadCount++;
                    this.images[i].uploadProgress = parseInt( Math.round( ( loaded / total ) * 100 ));
                    if (this.loadCount === this.previewImages.length) {
                        this.getMediaList({ per_page: this.per_page })
                            .then(resp => {
                                this.mediaList = resp.data;
                                this.activeTab = 1;
                            })
                            .catch(err => {
                                console.log(err);
                            })
                    }
                }
            }).then(res => {
                console.log(res);
            }).catch(err => console.log(err))
        },
        previewImage(image, i) {
            const reader = new FileReader();
            reader.addEventListener(
                "load",
                () => {
                    this.images[i].base64Url = reader.result;
                },
                false
            );
            if (image) reader.readAsDataURL(image);
        },
        handleEditImage () {
            if (this.link.trim() !== '' || this.name.trim() !== '' || this.alt.trim() !== '') {
                if (this.focus) {
                    const data = { ...this.editItem, link: this.link, name: this.name, alt: this.alt };
                    this.loading = true;
                    this.editMedia(data)
                        .then(resp => {
                            this.editItem = resp;
                            this.updateMediaList(resp);
                            this.focus = false;
                            this.loading = false;
                        })
                        .catch(err => {
                            this.loading = false;
                            console.log(err)
                        })
                }
            }
        },
        updateMediaList (edit) {
            for (let i = 0; i < this.mediaList.length; i++) {
                if (edit.id === this.mediaList[i].id) {
                    this.mediaList[i] = edit;
                    break;
                }
            }
        },
        handleInsertImage () {
            this.$emit('close-media', this.editItem);
        },
        addScrollListener () {
            const mediaList = document.querySelector('.media-list');
            if (mediaList) {
                mediaList.addEventListener('scroll', this.scrollLoadMore);
            }
        },
        scrollLoadMore () {
            const mediaList = document.querySelector('.media-list');
            if( mediaList.scrollTop === (mediaList.scrollHeight - mediaList.offsetHeight)) {
                const page = this.page + 1;
                if (page <= this.pagination.page_count) {
                    this.page = page;
                    this.loadMediaList();
                }
            }
        },
        removeListener () {
            const mediaList = document.querySelector('.media-list');
            mediaList.removeEventListener('scroll', this.scrollLoadMore);
        },
        handleDeleteMedia (id) {
            const res = confirm('Are you sure want to delete?');
            if (res) {
                this.deleteMedia({ id })
                .then(resp => {
                    this.mediaList = this.mediaList.filter(item => item.id !== id);
                    this.editItem = {};
                })
                .catch (err => {
                    console.log(err);
                });
            }
        },
        resetData () {
            this.dialog = false;
            this.tab = null;
            this.activeTab = 1;
            this.tabs = [
                { index: 0, name: 'Upload' },
                { index: 1, name: 'Library' }
            ];
            this.images =  [];
            this.loadCount =  0;
            this.per_page =  30;
            this.mediaList =  [];
            this.editItem =  {};
            this.link =  '';
            this.alt =  '';
            this.name =  '';
            this.focus =  false;
            this.loading =  false;
            this.page =  1;
        }
    }
};
</script>

<style lang="scss" scoped>
.preview-pic {
    position: relative;
    .progress {
        position: absolute;
        width: 80%;
        height: 10px;
        background: black;
        top: 90%;
        left: 50%;
        border-radius: 5px;
        transform: translate(-50%, -50%);
        .percent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: green;
            border-radius: 5px;
            display: block;
        }
    }
}
.media-list {
    width: 100%;
    height: 80vh;
    overflow: auto;
    cursor: pointer;
    .media-item {
        float: left;
        width: calc(100% / 4);
        padding: 4px;
        height: 200px;
    }
}
.media-item.active {
    position: relative;
    border: 4px solid #e91e63;
    .check {
        position: absolute;
        top: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background: #4caf50;
        display: flex;
        align-items: center;
        justify-content: center;
    }
}

.media-list::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	background-color: #F5F5F5;
}

.media-list::-webkit-scrollbar
{
	width: 10px;
	background-color: #F5F5F5;
}

.media-list::-webkit-scrollbar-thumb
{
	background-color: #F90;
	background-image: -webkit-linear-gradient(45deg,
	                                          rgba(255, 255, 255, .2) 25%,
											  transparent 25%,
											  transparent 50%,
											  rgba(255, 255, 255, .2) 50%,
											  rgba(255, 255, 255, .2) 75%,
											  transparent 75%,
											  transparent)
}
.spinning {
    animation: spin 2s linear infinite;
}
@-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
</style>
