<template>
    <b-container fluid>
        <b-row mb="4">
            <b-table show-empty
                stacked="md"
                :items="players"
                :fields="fields"
                :current-page="currentPage"
                :per-page="20"
            >
                <div slot="index" slot-scope="data">{{ (data.index + 1) + (perPage * (currentPage - 1)) }}</div>
                <router-link slot="name" slot-scope="data" class="a-link" :to="{ name: 'PlayerDetail', params: { id: data.item.id }}" target="_blank">
                    {{ data.item.name }}
                </router-link>
                <div slot="custom" slot-scope="data">
                    {{ 
                        calculateCustomValue(data.item)
                    }}
                </div>
            </b-table>
        </b-row>
        <b-row class="mtr__pagination">
            <b-col md="6" class="my-1">
                <b-pagination :total-rows="players.length" :per-page="perPage" v-model="currentPage" class="mtr__pagination-items" />
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    import {HTTP, Routes} from '@/components/Routes/Routes'

    export default {
        name: 'StatisticsTable',
        data() {
            return {
                fields: [
                    { key: 'index', label: '№', sortable: true },
                    { key: 'name', label: 'Игрок'},
                    { key: 'custom', label: this.keyParam.title, sortable: true },
                ]

            }
        },
        props: {
            players: {
                type: Array,
                required: true
            },
            keyParam: {
                type: Array,
                required: true
            },
            currentPage: {
                type: Number,
                required: true
            },
            perPage: {
                type: Number,
                required: true
            }
        },
        created: function() {
            this.currentPage = 1;
            console.log(this.players);
        },
        methods: {
            calculateCustomValue: function(data) {
                var result = data;
                for(var i = 0; i < this.keyParam.keys.length; i++) {
                    result = result[this.keyParam.keys[i]];
                }
                return result;
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>