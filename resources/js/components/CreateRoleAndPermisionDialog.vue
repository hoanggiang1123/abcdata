<template>
    <v-dialog
        max-width="900px"
        v-model="dialog"
        persistent
    >
        <v-card>
            <v-card-title>
                Create Role & Permision
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleCreateRoleAndPermision">
                    <h2 class="mb-4"><v-icon left>mdi-account-supervisor</v-icon> Role</h2>
                    <v-text-field label="Name" v-model="name" />
                    <v-text-field label="Description" v-model="description" />
                    <h2 class="mb-4"><v-icon left>mdi-account-key</v-icon> Permision</h2>
                    <template v-if="permisions">
                        <v-sheet style="max-height: 600px;" class="fix-height mb-4">
                            <v-card v-for="permision in permisions" :key="permision.id" color="#313131" class="mb-2">
                                <template v-if="permision.permision_children.length">
                                    <v-card-title>
                                        <v-checkbox
                                            :label="permision.description"
                                            color="red"
                                            :value="1"
                                            hide-details
                                            @change="handleSelectAllPermision($event, permision)"
                                        >
                                        </v-checkbox>
                                    </v-card-title>
                                    <v-divider horizontal></v-divider>
                                    <v-card-text>
                                        <v-row>
                                            <v-col cols="4" v-for="per in permision.permision_children" :key="per.id">
                                                <v-checkbox
                                                    v-model="permision_ids"
                                                    :label="per.name"
                                                    color="red"
                                                    :value="per.id"
                                                    hide-details
                                                    >
                                                </v-checkbox>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                </template>
                            </v-card>
                        </v-sheet>
                    </template>
                    <v-row>
                        <v-col>
                            <v-btn color="primary" type="submit" block>Save</v-btn>
                        </v-col>
                        <v-col>
                            <v-btn color="warning" block @click="$emit('close-create-dialog')">Cancel</v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions } from 'vuex';
export default {
    props: ['permisions', 'openCreateDialog'],
    data () {
        return {
            dialog: false,
            name: '',
            description: '',
            permision_ids: []
        }
    },
    watch: {
        openCreateDialog (val) {
            if (val) {
                this.dialog = true;
            } else {
                this.dialog = false;
                this.name = '';
                this.description = '';
            }
        }
    },
    methods: {
        ...mapActions('roleAndPermision', ['createRoleAndPermision']),
        handleSelectAllPermision (value, permision) {
            const data = permision.permision_children;
            const permisionIds = [];
            data.forEach(item => {
                permisionIds.push(item.id);
            });
            if (value) {
                this.permision_ids = this.permision_ids.filter(item => !permisionIds.includes(item));
                this.permision_ids = [...this.permision_ids, ...permisionIds]
            } else {
                this.permision_ids = this.permision_ids.filter(item => !permisionIds.includes(item));
            }
        },
        handleCreateRoleAndPermision () {
            if (!this.name || this.name.trim() === '' ||  this.name.length < 4) {
                alert('Role Name is required');
                return false;
            }

            const data = {
                name: this.name,
                description: this.description,
                permision_ids: this.permision_ids
            }

            this.createRoleAndPermision(data)
                .then(resp => {
                    console.log(resp);
                    this.$emit('close-create-dialog', true);
                })
                .catch(err => {
                    console.log(err);
                })

        }
    }
}
</script>

<style>

</style>
