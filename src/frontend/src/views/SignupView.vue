<script setup>
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';

    const router = useRouter();
    const formData = ref({
        username: '',
        password: ''
    });
    const error = ref(null);
    const apiUrl = 'http://localhost:8080/auth/signup';
    
    const handleSubmit = async () => {
        try {
            console.log('Submitting signup form with data:', formData.value);
            console.log('JSON form data:', JSON.stringify(formData.value));

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData.value)
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log('Signup response data:', data);
            router.push('/login'); // Redirect to login after successful signup
        } catch (err) {
            error.value = `Hiba történt: ${err.message}`;
        }
    };

</script>

<template>
    <form @submit.prevent="handleSubmit">
        <h1>Signup</h1>
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" v-model="formData.username" required />
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" v-model="formData.password" required />
        </div>
        <button type="submit">Sign Up</button>
    </form>
        <p v-if="error">{{ error }}</p>
</template>