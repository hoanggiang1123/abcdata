<template>
    <div>{{ fieldText }}</div>
</template>

<script>
export default {
    props: ['items', 'field'],
    computed: {
        fieldText () {
            if (this.items.length) {
                let text = 'NULL';
                for (let i = 0; i < this.items.length; i++) {
                    if (this.items[i].id === this.field) {
                        text = this.items[i].name;
                        break;
                    }
                }
                return text
            }
            return 'NULL'
        }
    }
}
</script>

<style>

</style>
