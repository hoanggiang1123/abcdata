<template>
    <div class="text-editor">
        <v-btn color="warning" class="mb-2" @click="openMedia = true">
            <v-icon left>mdi-image</v-icon> Media
        </v-btn>
        <ckeditor
            v-model="editorData"
            :config="editorConfig"
            :editor-url="editorUrl"
            @ready="onEditorReady"
            @namespaceloaded="onNamespaceLoaded"
            ref="editor"
        >
        </ckeditor>
        <Media :openMedia="openMedia" @close-media="handleCloseMedia" />
    </div>
</template>

<script>
import CKEditor from 'ckeditor4-vue';
import Media from './Media.vue';
export default {
    props: ['value'],
    components: {
        ckeditor: CKEditor.component,
        Media
    },
    data () {
        return {
            editorUrl: 'https://cdn.ckeditor.com/4.14.1/full-all/ckeditor.js',
            editorData: this.value,
            editorConfig: {
                toolbarGroups: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                    { name: 'forms', groups: [ 'forms' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'insert', groups: [ 'insert' ] },
                    { name: 'styles', groups: [ 'styles' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    { name: 'tools', groups: [ 'tools' ] },
                    { name: 'others', groups: [ 'others' ] },
                    { name: 'about', groups: [ 'about' ] }
                ],
	            removeButtons: 'NewPage,ExportPdf,Preview,Print,Templates,Save,Find,SelectAll,Scayt,Cut,Undo,Form,Copy,Redo,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,PasteFromWord,PasteText,Paste,Replace,CopyFormatting,RemoveFormat,Language,Flash,About,Maximize,ShowBlocks,Image,Flash'
            },
            openMedia: false
        }
    },
    watch: {
        editorData (val) {
            this.$emit('input', val);
        },
        value (val) {
            this.editorData = val;
        }
    },
    mounted () {
    },
    methods: {
        onEditorReady (CKEDITOR) {
        },
        onNamespaceLoaded (CKEDITOR) {

        },
        handleCloseMedia (data) {
            this.openMedia = false;
            if (data) {
                const img = `<img src="${data.image_url}" />`;
                let html = '';
                if (data.link) {
                    html = `<a href="${data.link}">${img}</a>`;
                }
                else {
                    html = img;
                }

                this.$refs.editor.instance.insertHtml(html)
            }
        }
    }
}
</script>

<style>

</style>
