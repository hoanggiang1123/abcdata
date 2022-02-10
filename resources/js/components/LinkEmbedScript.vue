<template>
    <v-sheet class="d-flex pa-2 flex-column">
        <codemirror v-model="code" :options="cmOptions" disabled />
        <div class="d-flex mt-2">
            <v-btn small v-clipboard="code" color="pink">Copy Script</v-btn>
        </div>
    </v-sheet>
</template>

<script>
import { codemirror } from 'vue-codemirror'
export default {
    props: ['script_url'],
    components: {
        codemirror
    },
    data () {
        return {
            cmOptions: {
                tabSize: 4,
                mode: 'text/javascript',
                theme: 'base16-dark',
                lineNumbers: true,
                line: true,
                // more CodeMirror options...
            }
        }
    },
    computed: {
        code () {
            if (this.script_url) {
                return "<script>\n(function(link){\n    var lscript = document.createElement('script');\n    lscript.type = 'text/javascript';\n    lscript.src = link;\n    let head = document.head || document.getElementsByTagName('head')[0];\n    head.appendChild(lscript);\n})('" + this.script_url + "');\n<\/script>";
            }
        }
    }
}
</script>

<style>

</style>
