<template>
    <div class="logs">
        <div class="log head">
            <div>Field Change</div>
            <div>Old Value</div>
            <div>New Value</div>
            <div>Changed By</div>
        </div>
        <hr class="mb-4">
        <template v-for="log in logs">
            <TeleSaleLogItem :log="log" :key="log.id" :categories="categories" :lines="lines" :vips="vips" :agents="agents" />
        </template>
    </div>
</template>

<script>
import TeleSaleLogItem from './TeleSaleLogItem';
export default {
    components: {
        TeleSaleLogItem
    },
    props: ['logs', 'categories', 'lines', 'vips', 'agents']
}
</script>

<style lang="scss" scoped>
    .logs {
        width: 100%;
        .log {
            display: grid;
            grid-template-columns: 1fr 2fr 2fr 1fr;
            grid-gap: 5px;
            div {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 5px;
            }
            &.head {
                margin-bottom: 10px;
                div {
                    font-weight: bold;
                }
            }
        }
    }
</style>
