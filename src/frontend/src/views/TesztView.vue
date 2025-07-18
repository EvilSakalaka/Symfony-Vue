<script setup>
    import { ref, onMounted } from 'vue';

    const loading = ref(true);
    const error = ref(null);
    const apidata = ref(null);
    const apiUrl = 'http://localhost:8080/api/teszt';

    onMounted(async () => {
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(apiUrl , {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
                });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            apidata.value = await response.json();
        } catch (err) {
            error.value = `Hiba történt: ${err.message}`;
        } finally {
            loading.value = false;
        }

    });
</script>

<template>
    <main>
        <h1>Test View</h1>
        <p>This is a test view to demonstrate routing in Vue.js.</p>
        <p v-if="loading">Betöltés...</p>
        <p v-if="error">{{ error }}</p>
        <div v-if="apidata">
            <h3>Api Message:</h3>
            <p>{{ apidata.message }}</p>
            <h3>Api path:</h3>
            <p>{{ apidata.path }}</p>
        </div>
    </main>
</template>