<template>
    <div v-if="Object.keys(oldValues).length && Object.keys(newValues).length">
        <div class="log" v-for="field in Object.keys(oldValues)" :key="field">
            <template v-if="field === 'status'">
                <div>status</div>
                <div>{{ statuses[oldValues[field]] ?  statuses[oldValues[field]] : 'NULL'}}</div>
                <div>{{ statuses[newValues[field]] ?  statuses[newValues[field]] : 'NULL'}}</div>
            </template>
            <template v-else-if="field === 'category_id'">
                <div>category</div>
                <TeleSaleLogItemMap :items="categories" :field="oldValues[field]"/>
                <TeleSaleLogItemMap :items="categories" :field="newValues[field]"/>
            </template>
             <template v-else-if="field === 'line_id'">
                <div>line</div>
                <TeleSaleLogItemMap :items="lines" :field="oldValues[field]"/>
                <TeleSaleLogItemMap :items="lines" :field="newValues[field]"/>
            </template>
            <template v-else-if="field === 'vip_id'">
                <div>vip</div>
                <TeleSaleLogItemMap :items="vips" :field="oldValues[field]"/>
                <TeleSaleLogItemMap :items="vips" :field="newValues[field]"/>
            </template>
            <template v-else-if="field === 'agent_id'">
                <div>Agent</div>
                <TeleSaleLogItemMap :items="agents" :field="oldValues[field]"/>
                <TeleSaleLogItemMap :items="agents" :field="newValues[field]"/>
            </template>
            <template v-else>
                 <div>{{ field }}</div>
                <div>{{ oldValues[field] ?  oldValues[field] : 'NULL'}}</div>
                <div>{{ newValues[field] ?  newValues[field] : 'NULL'}}</div>
            </template>
            <div>{{ log.user.name }} at {{ log.created_at | datetime }}</div>
        </div>
    </div>
</template>

<script>
import TeleSaleLogItemMap from './TeleSaleLogItemMap.vue';
export default {
    components: {
        TeleSaleLogItemMap
    },
    props: ['log', 'categories', 'lines', 'vips', 'agents'],
    data () {
        return {
            statuses: {
                '0': 'No call',
                '1': 'Not exist',
                '2': 'Not answered',
                '3': 'Answered',
                '4': 'Not interested',
                '5': 'Interested',
                '6': 'Registered',
                '7': 'First Deposit',
                '8': 'Second Deposit',
                '9': 'Third Deposit',
                '10': 'Retention',
                '11': 'No Action',
                '12': 'Freebet',
                '13': 'Existed',
                '14': 'Signed Up',
            }
        }
    },
    computed: {
        oldValues () {
            if (this.log.old_values) {
                return JSON.parse(this.log.old_values)
            }
            return {}
        },
        newValues () {
            if (this.log.new_values) {
                return JSON.parse(this.log.new_values)
            }
            return {}
        },
    }
}
</script>

<style lang="scss" scoped>
.log {
    display: grid;
    grid-template-columns: 1fr 2fr 2fr 1fr;
    grid-gap: 5px;
    margin-bottom: 10px;
    div {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }
}
</style>
