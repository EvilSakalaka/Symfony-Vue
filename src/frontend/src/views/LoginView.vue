<script setup>
import { ref } from 'vue';
import * as jwt_decode from 'jwt-decode';
import { useRouter } from 'vue-router';

const router = useRouter();
const formData = ref({
        username: '',
        password: ''
    });
const error = ref(null);
const apiUrl = 'http://localhost:8080/api/login_check';

async function login() {
    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username: formData.value.username, password: formData.value.password })
        });
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        const token = data.token;
        localStorage.setItem('token', token);
        const decodedToken = jwt_decode.jwtDecode(token);
        router.push('/teszt'); // Redirect to test view after successful login
    } catch (err) {
        error.value = `Error occurred: ${err.message}`;
    }
}
</script>

<template>
    <form @submit.prevent="login">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" v-model="formData.username" required />
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" v-model="formData.password" required />
        </div>
        <button type="submit">Login</button>
        <div v-if="error" class="error">{{ error }}</div>
    </form>
</template>