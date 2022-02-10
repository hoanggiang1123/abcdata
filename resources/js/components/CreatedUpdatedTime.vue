<template>
    <div class="d-flex flex-column align-center">
        <div class="d-flex">
            Create: {{ createTime }}
        </div>
        <div class="d-flex">
             Update: {{ updateTime }}
        </div>
    </div>
</template>

<script>
export default {
    props: {
        item: {
            type: Object,
            default: () => {}
        }
    },
    computed: {
        createTime () {
            if (this.item.id && this.item.created_at) {
                return this.getTime(this.item.created_at);
            }
            return ''
        },
        updateTime () {
            if (this.item.id && this.item.updated_at) {
                return this.getTime(this.item.updated_at);
            }
            return ''
        },
    },
    methods: {
        getTime(time) {
            const date = new Date(time);
            return (date.getDate().toString()).padStart(2, '0') + '/' + ((date.getMonth() + 1).toString()).padStart(2, '0') + '/' + date.getFullYear() + ' ' + (date.getHours().toString()).padStart(2, '0') + ':' + (date.getMinutes().toString()).padStart(2, '0');
        }
    }
}
</script>

<style>

</style>
