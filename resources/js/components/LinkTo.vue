<template>
    <div class="d-flex flex-column">
        <v-btn v-for="(id, index) in idArray" :key="index" small class="ma-2" :to="/banner/ + id" :color="randomColor()">Link {{ id }}</v-btn>
    </div>
</template>

<script>
export default {
    props: ['ids'],
    computed: {
        idArray () {
            if (this.ids !== '') {
                const idsArr = this.ids.split(',');
                if (idsArr.length) return idsArr
                return []
            }
            return [];
        }
    },
    methods: {
        randomColor () {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    }
}
</script>

<style>

</style>
