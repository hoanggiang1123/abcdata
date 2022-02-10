<template>
    <div class="text-editor">
         <quill-editor
            style="min-height:300px;"
            ref="myQuillEditor"
            v-model="content"
            :options="editorOption"
            @ready="onEditorReady($event)"
            @change="onEditorChange($event)"
        />
        <Media :openMedia="openMedia" @close-media="handleCloseMedia" />
    </div>
</template>

<script>
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'

import { quillEditor } from 'vue-quill-editor';
import Media from './Media.vue';
export default {
    props: ['value'],
    components: {
        quillEditor,
        Media
    },
    data () {
        return {
            content: this.value,
            editorOption: {
                modules: {
                    toolbar: {
                        container: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            ['bold', 'italic', 'underline'],
                            ['blockquote'],
                            [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'script': 'sub' }, { 'script': 'super' }],
                            // [{ 'size': ['small', false, 'large', 'huge'] }],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'align': [] }],
                            ['clean'],
                            ['link', 'image']
                        ],
                        handlers: {
                            'image': () => {
                                this.openMedia = true;
                            }
                        }
                    },
                    syntax: {
                        highlight: text => hljs.highlightAuto(text).value
                    }
                }
            },
            openMedia: false
        }
    },
    methods: {
        onEditorReady(quill) {
            quill.root.setAttribute('spellcheck', 'false');
        },
        onEditorChange({ quill, html, text }) {
            // console.log('editor change!', quill, html, text)
            this.content = html;
            this.$emit('input', html);
        },
        handleCloseMedia (data) {
            this.openMedia = false;
            if (data) {
                this.editor.focus();
                const range = this.editor.getSelection();
                this.editor.insertEmbed(range.index, 'image', data.image_url);
            }
        }
    },
    computed: {
        editor() {
            return this.$refs.myQuillEditor.quill
        }
    }
}
</script>

<style>
.ql-container {
    min-height: inherit;
}
.ql-snow .ql-stroke {
    stroke: #fff;
}
.ql-snow .ql-fill, .ql-snow .ql-stroke.ql-fill {
    fill: #fff;
}
.ql-snow .ql-picker {
    color: #FFF;
}
.ql-snow .ql-picker-options {
    background: #1e1e1e;
}
.ql-editor.ql-blank::before {
    color: grey;
}
</style>
