<template>
    <div class="login">
        <v-card width="600px">
            <v-card-title>
                Login
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleLogin">
                    <v-text-field
                        v-model="username"
                        label="User Name"
                        required
                    />
                    <v-text-field
                        v-model="password"
                        label="Password"
                        required
                        type="password"
                    />
                    <v-btn color="primary" block type="submit">Login</v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapActions } from 'vuex';
export default {
    data () {
        return {
            username: '',
            password: ''
        }
    },
    methods: {
        ...mapActions('user', ['userLogin', 'autoRefresh']),
        handleLogin() {
            if (this.username.trim() === '' || this.password.trim() === '') {
                alert('username and password is required');
                return;
            }
            this.userLogin({ username: this.username, password: this.password })
            .then((resp) => {
                this.$router.push('/');
                this.autoRefresh();
            }).catch (err => {
                console.log(err)
            })


        }
    }
}
</script>

<style lang="scss" scoped>
    .login {
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
